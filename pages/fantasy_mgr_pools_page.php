<?php


function fantasy_pools_page(){
    global $wpdb;

    $pools_table = $wpdb->prefix . 'fantasy_pools';
	$prizes_table = $wpdb->prefix . 'fantasy_pool_prizes';
	
    // ---------- ADD POOL ----------
    if(isset($_POST['add_pool'])){
        $wpdb->insert($pools_table, [
            'pool_name' => sanitize_text_field($_POST['pool_name']),
        ]);
    }

    // ---------- DELETE POOL ----------
    if(isset($_GET['delete_pool'])){
        $wpdb->delete($pools_table, ['pool_id' => intval($_GET['delete_pool'])]);
    }

    // GET ALL POOLS
    $pools = $wpdb->get_results("SELECT * FROM $pools_table ORDER BY pool_id DESC");

    ?>
    <div class="wrap">
        <h1>Fantasy Pools</h1>

        <hr>

        <h2>Add New Pool</h2>

        <form method="POST" style="margin-bottom:20px;">
            <table class="form-table">
                <tr>
                    <th>Pool Name</th>
                    <td><input type="text" name="pool_name" required></td>
                </tr>
			<!-- <tr>
                    <th>League ID</th>
                    <td><input type="number" name="league_id" required></td>
                 </tr> -->
            </table>

            <button class="button button-primary" name="add_pool" type="submit">Add Pool</button>
        </form>
		<hr>
   
        <h2>All Pools</h2>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pool Name</th>
                    <th>Prize Distribution</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach($pools as $pool){ 
                // quick summary of prize count
                $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$prizes_table} WHERE pool_id=%d", $pool->pool_id));
            ?>
                <tr id="fc-pool-row-<?php echo $pool->pool_id; ?>">
                    <td><?php echo $pool->pool_id; ?></td>
                    <td><?php echo esc_html($pool->pool_name); ?></td>
                    <td>
                        <?php echo intval($count); ?> configuration<?php echo $count!=1?'s':''; ?>
                    </td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=fantasy_boxes&pool_id='.$pool->pool_id); ?>"
                           class="button button-secondary">Go to Boxes</a>

                        <button class="button fc-manage-prizes-btn" 
                                data-pool-id="<?php echo $pool->pool_id; ?>">
                            Manage Prizes
                        </button>

                        <a href="<?php echo admin_url('admin.php?page=fantasy_pools&delete_pool='.$pool->pool_id); ?>"
                           class="button button-danger"
                           onclick="return confirm('Delete this pool?');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal container (hidden) -->
    <div id="fc-prize-modal" style="display:none;">
        <div class="fc-prize-modal-backdrop"></div>
        <div class="fc-prize-modal-panel" role="dialog" aria-modal="true">
            <button class="fc-prize-close" title="Close">×</button>

            <div class="fc-prize-header" style="display:flex;align-items:center;gap:16px;">
                <img src="/mnt/data/1056ea6a-b8ae-49fb-afaa-91c344783073.png" alt="prize" style="width:120px;height:auto;border-radius:8px;">
                <div>
                    <h2 style="margin:0 0 6px;">Manage Prize Distribution</h2>
                    <div style="opacity:0.8;">Pool ID: <span id="fc-prize-pool-id"></span></div>
                </div>
            </div>

            <div class="fc-prize-body" style="margin-top:18px;">
                <table class="widefat" id="fc-prize-table">
                    <thead>
                        <tr>
                            <th style="width:140px">Position (0 = All)</th>
                            <th>Prize amount (%)</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <p style="margin-top:12px;">
                    <button class="button button-secondary" id="fc-add-prize-row">+ Add Row</button>
                </p>

                <p style="margin-top:14px;">
                    <label style="font-weight:600">Remaining members prize (if you want fixed fallback):</label><br>
                    <input type="text" id="fc-remaining-prize" style="width:220px;" placeholder="">
                </p>

            </div>

            <div class="fc-prize-footer" style="margin-top:18px;text-align:right;">
                <span id="fc-prize-status" style="margin-right:12px;color:green;"></span>
                <button class="button" id="fc-prize-cancel">Cancel</button>
                <button class="button button-primary" id="fc-prize-save">Save Prizes</button>
            </div>
        </div>
    </div>
	<style>
		/* Modal styles */
		#fc-prize-modal .fc-prize-modal-backdrop {
			position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9998;
		}
		#fc-prize-modal .fc-prize-modal-panel {
			position:fixed;left:50%;top:50%;transform:translate(-50%,-50%);
			background:#fff; color:#fff; padding:22px; z-index:9999; width:820px; border-radius:12px;
			box-shadow:0 12px 40px rgba(0,0,0,0.6);
		}
		#fc-prize-modal .fc-prize-close { position:absolute; right:12px; top:10px; background:transparent; color:#fff; border:none; font-size:26px; cursor:pointer;}
		#fc-prize-table input { width:100%; box-sizing:border-box; padding:6px; background:#fff; color:#111; border-radius:4px; border:1px solid #ddd;}
		#fc-prize-table td, #fc-prize-table th { padding:8px; vertical-align:middle; color:#fff; }
		#fc-prize-table thead th { color:#ddd; }
		.fc-prize-remove { color:#fff; background:#a00; border:1px solid #c33; padding:6px 10px; border-radius:6px; cursor:pointer; }
	</style>

    <script>
    (function($){
        var ajaxUrl = ajaxurl; // WP admin ajaxurl
        var nonce = '<?php echo $ajax_nonce; ?>';

        function openModal(poolId){
            $('#fc-prize-pool-id').text(poolId);
            $('#fc-prize-status').text('');
            $('#fc-remaining-prize').val('');
            $('#fc-prize-table tbody').html('<tr><td colspan="3">Loading…</td></tr>');
            $('#fc-prize-modal').show();

            // load existing prizes via AJAX
            $.post(ajaxUrl, {
                action: 'fc_get_pool_prizes',
                pool_id: poolId,
                nonce: nonce
            }, function(resp){
                if(!resp.success){
                    $('#fc-prize-table tbody').html('<tr><td colspan="3">No prizes yet. Add rows below.</td></tr>');
                    return;
                }
                var rows = resp.data.prizes || [];
                var rem = resp.data.remaining_prize || '';
                $('#fc-remaining-prize').val(rem);

                if(rows.length === 0){
                    $('#fc-prize-table tbody').html('<tr><td colspan="3">No prizes yet. Add rows below.</td></tr>');
                    return;
                }

                var html = '';
                rows.forEach(function(r){
                    html += '<tr data-id="'+(r.id||'')+'">'+
                                '<td><input type="number" class="fc-pos" value="'+r.position+'"></td>'+
                                '<td><input type="number" step="0.1" class="fc-amt" value="'+r.amount+'"></td>'+
                                '<td><button class="fc-prize-remove button" type="button">Remove</button></td>'+
                            '</tr>';
                });
                $('#fc-prize-table tbody').html(html);
            }, 'json');
        }

        function closeModal(){
            $('#fc-prize-modal').hide();
        }

        $(document).ready(function(){
            // open modal on click
            $('.fc-manage-prizes-btn').on('click', function(e){
                e.preventDefault();
                var poolId = $(this).data('pool-id');
                openModal(poolId);
            });

            // close
            $(document).on('click', '#fc-prize-cancel, .fc-prize-close', function(){
                closeModal();
            });

            // add new row
            $(document).on('click', '#fc-add-prize-row', function(){
                var row = '<tr>'+
                            '<td><input type="number" class="fc-pos" placeholder="Position (0 = all)"></td>'+
                            '<td><input type="number" step="0.1" class="fc-amt" placeholder="%"></td>'+
                            '<td><button class="fc-prize-remove button" type="button">Remove</button></td>'+
                          '</tr>';
                $('#fc-prize-table tbody').append(row);
            });

            // remove row
            $(document).on('click', '.fc-prize-remove', function(){
                $(this).closest('tr').remove();
            });

            // save prizes
            $(document).on('click', '#fc-prize-save', function(){
                var poolId = parseInt($('#fc-prize-pool-id').text(), 10);
                if(!poolId) return alert('Invalid pool ID');

                var remaining = $('#fc-remaining-prize').val();

                var prizes = [];
                $('#fc-prize-table tbody tr').each(function(){
                    var pos = $(this).find('.fc-pos').val();
                    var amt = $(this).find('.fc-amt').val();
                    if(pos === undefined || amt === undefined) return;
                    pos = pos.toString().trim();
                    amt = amt.toString().trim();
                    if(pos === '' && amt === '') return;
                    // ensure numeric
                    prizes.push({ position: pos === '' ? 0 : parseInt(pos,10), amount: parseFloat(amt || 0) });
                });

                // send AJAX save
                $('#fc-prize-status').css('color','green').text('Saving...');
                $.post(ajaxUrl, {
                    action: 'fc_save_pool_prizes',
                    pool_id: poolId,
                    prizes: JSON.stringify(prizes),
                    remaining_prize: remaining,
                    nonce: nonce
                }, function(resp){
                    if(!resp.success){
                        $('#fc-prize-status').css('color','red').text(resp.data || 'Save failed');
                        return;
                    }
                    $('#fc-prize-status').css('color','green').text('Saved.');
                    // update the row summary count
                    $('#fc-pool-row-' + poolId + ' td:nth-child(3)').text(prizes.length + ' prize' + (prizes.length!=1?'s':''));
                    // close after short delay
                    setTimeout(closeModal, 600);
                }, 'json');
            });

            // close modal on ESC
            $(document).on('keydown', function(e){
                if(e.key === 'Escape') closeModal();
            });
        });

    })(jQuery);
    </script>
    </div>
<?php
}
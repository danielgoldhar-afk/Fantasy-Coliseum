jQuery(function($){

    function loadPools(){
        $.post(fm_ajax.ajax_url, {action:'fm_get_pools',nonce:fm_ajax.nonce}, function(r){
            if(r.success){
                var html='';
                r.data.forEach(function(pool){
                    html+='<tr>';
                    html+='<td>'+pool.pool_id+'</td>';
                    html+='<td>'+pool.name+'</td>';
                    html+='<td>'+pool.league_name+'</td>';
                    html+='<td><button class="editPool" data-id="'+pool.pool_id+'">Manage Boxes</button></td>';
                    html+='<td><button class="deletePool" data-id="'+pool.pool_id+'">Delete</button></td>';
                    html+='</tr>';
                });
                $('#fmPoolsTable tbody').html(html);
            }
        });
    }

    loadPools();

    $('#addPoolBtn').on('click',function(){
        $('#fmPoolModal').show();
        $('#modalTitle').text('Add Pool');
        $('#pool_id').val('');
        $('#pool_name').val('');
    });

    $('#savePool').on('click',function(){
        var data={action:'fm_save_pool',nonce:fm_ajax.nonce,
            pool_id:$('#pool_id').val(),
            name:$('#pool_name').val(),
            league_id:$('#pool_league').val()
        };
        $.post(fm_ajax.ajax_url,data,function(r){
            if(r.success){
                alert('Pool saved!');
                $('#fmPoolModal').hide();
                loadPools();
            }
        });
    });

    $('#addBoxBtn').on('click',function(){
        var data={action:'fm_add_box',nonce:fm_ajax.nonce,
            pool_id:$('#pool_id').val(),
            name:$('#box_name').val(),
            max_entries:$('#box_max').val()
        };
        $.post(fm_ajax.ajax_url,data,function(r){
            if(r.success){
                alert('Box added!');
                // optionally reload boxes table here
            }
        });
    });
    
    
    // Inline Edit Box
    $(document).on('click','.editBox',function(){
        var row = $(this).closest('tr');
        var box_id = $(this).data('id');
        var name = row.find('.boxName').text();
        var max = row.find('.boxMax').text();
    
        var newName = prompt("Edit Box Name", name);
        if(newName === null) return; // Cancel
        var newMax = prompt("Edit Max Entries", max);
        if(newMax === null) return;
    
        $.post(fm_ajax.ajax_url,{
            action:'fm_edit_box',
            nonce:fm_ajax.nonce,
            box_id: box_id,
            name: newName,
            max_entries: newMax
        }, function(r){
            if(r.success){
                alert('Box updated!');
                row.find('.boxName').text(newName);
                row.find('.boxMax').text(newMax);
            }
        });
    });
    
    // Delete Box
    $(document).on('click','.deleteBox',function(){
        if(!confirm('Are you sure you want to delete this box?')) return;
        var row = $(this).closest('tr');
        var box_id = $(this).data('id');
    
        $.post(fm_ajax.ajax_url,{
            action:'fm_delete_box',
            nonce:fm_ajax.nonce,
            box_id: box_id
        }, function(r){
            if(r.success){
                alert('Box deleted!');
                row.remove();
            }
        });
    });


});
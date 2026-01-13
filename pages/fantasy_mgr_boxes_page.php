<?php


function fantasy_boxes_page(){
    global $wpdb;

    $pools_table = $wpdb->prefix . 'fantasy_pools';
    $boxes_table = $wpdb->prefix . 'fantasy_boxes';

    // GET POOL ID
    $pool_id = isset($_GET['pool_id']) ? intval($_GET['pool_id']) : 0;

    if(!$pool_id){
        echo "<div class='wrap'><h2>No Pool Selected</h2>
              <p>Please go to <strong>Fantasy Manager → Pools</strong> and click “Manage Boxes”.</p></div>";
        return;
    }

    // GET POOL NAME
    $pool_name = $wpdb->get_var("SELECT pool_name FROM $pools_table WHERE pool_id = $pool_id");

    // ---------------- ADD BOX ----------------
    if(isset($_POST['add_box'])){
        $wpdb->insert($boxes_table, [
            'pool_id'   => $pool_id,
            'box_name'  => sanitize_text_field($_POST['box_name']),
            'box_order' => intval($_POST['box_order']),
        ]);
    }

    // ---------------- DELETE BOX ----------------
    if(isset($_GET['delete_box'])){
        $wpdb->delete($boxes_table, ['box_id' => intval($_GET['delete_box'])]);
    }

    // GET ALL BOXES OF THIS POOL
    $boxes = $wpdb->get_results("SELECT * FROM $boxes_table WHERE pool_id = $pool_id ORDER BY box_order ASC, box_id DESC");
    ?>

    <div class="wrap">

        <h1>Boxes for Pool: <?php echo esc_html($pool_name); ?></h1>
        <a href="<?php echo admin_url('admin.php?page=fantasy_pools'); ?>" class="button">← Back to Pools</a>

        <hr>

        <h2>Add New Box</h2>

        <form method="POST" style="margin-bottom:20px;">
            <table class="form-table">
                <tr>
                    <th>Box Name</th>
                    <td><input type="text" name="box_name" required></td>
                </tr>

                <tr>
                    <th>Order</th>
                    <td><input type="number" name="box_order" value="0"></td>
                </tr>
            </table>

            <button class="button button-primary" name="add_box" type="submit">Add Box</button>
        </form>

        <h2>Existing Boxes</h2>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Box Name</th>
                    <th>Order</th>
                    <th>Players</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($boxes as $box){ ?>
                <tr>
                    <td><?php echo $box->box_id; ?></td>
                    <td><?php echo esc_html($box->box_name); ?></td>
                    <td><?php echo $box->box_order; ?></td>

                    <td>
                        <a href="<?php echo admin_url('admin.php?page=fantasy_pool_players&box_id='.$box->box_id.'&pool_id='.$pool_id); ?>"
                           class="button button-secondary">
                           Manage Players
                        </a>
                    </td>

                    <td>
                        <a href="<?php echo admin_url('admin.php?page=fantasy_boxes&pool_id='.$pool_id.'&delete_box='.$box->box_id); ?>"
                           class="button button-danger"
                           onclick="return confirm('Delete this box? All players inside will also be deleted!');">
                           Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>

    </div>

<?php
}
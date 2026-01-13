<?php

function fantasy_manager_home_page() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_error_logs';
    $per_page   = 20; // logs per page
    $paged      = isset($_GET['cpage']) ? max(1, intval($_GET['cpage'])) : 1;
    $offset     = ($paged - 1) * $per_page;

    // Fetch total logs count
    $total_logs = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

    // Fetch logs for current page
    $logs = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY error_time DESC LIMIT %d OFFSET %d", $per_page, $offset), ARRAY_A);
    $total_pages = ceil($total_logs / $per_page);
    ?>
    <div class="wrap">
        <h1>Fantasy Manager</h1>
        <p>Welcome to the Fantasy System Manager.</p>
        <ul>
            <li><a class="button" href="<?php echo admin_url('admin.php?page=fantasy_pools'); ?>">Manage Pools</a></li>
        </ul>
        <br>
	<?php
	
	global $wpdb;

    $table_name = $wpdb->prefix . 'custom_error_logs';
    $per_page   = 20;
    $paged      = isset($_GET['cpage']) ? max(1, intval($_GET['cpage'])) : 1;
    $offset     = ($paged - 1) * $per_page;

    // Total general logs
    $total_logs = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE error_type=%s", 'general'));

    $logs = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name WHERE error_type=%s ORDER BY error_time DESC LIMIT %d OFFSET %d",
        'general', $per_page, $offset
    ), ARRAY_A);

    $total_pages = ceil($total_logs / $per_page);
    ?>
    <div class="wrap">
        <h1>Fantasy Manager – General Error Logs</h1>

        <?php if(empty($logs)) : ?>
            <p>No general error logs found.</p>
        <?php else: ?>
            <table class="custom-error-logs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Strength</th>
                        <th>Description</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($logs as $log): ?>
                        <tr>
                            <td><?php echo esc_html($log['id']); ?></td>
                            <td><?php echo esc_html($log['error_title']); ?></td>
                            <td><?php echo esc_html($log['error_code']); ?></td>
                            <td><?php echo esc_html($log['error_strength']); ?></td>
                            <td><?php echo esc_html($log['error_desc']); ?></td>
                            <td><?php echo esc_html($log['error_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if($total_pages > 1): ?>
                <div class="pagination">
                    <?php
                    $pages_to_show = [];
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i <= 3 || $i > $total_pages - 3 || abs($i - $paged) <= 1) {
                            $pages_to_show[] = $i;
                        }
                    }
                    $last_page = 0;
                    foreach ($pages_to_show as $page) {
                        if ($page - $last_page > 1) echo '<span>...</span>';
                        if ($page == $paged) echo '<span class="current-page">' . $page . '</span>';
                        else echo '<a href="' . add_query_arg('cpage', $page) . '">' . $page . '</a>';
                        $last_page = $page;
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <style>
        .custom-error-logs { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .custom-error-logs th, .custom-error-logs td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .custom-error-logs th { background-color: #f5f5f5; }
        .custom-error-logs tr:nth-child(even) { background-color: #f9f9f9; }
        .pagination { margin-top: 15px; }
        .pagination a, .pagination span { margin: 0 5px; padding: 4px 8px; border-radius: 4px; border: 1px solid #ccc; text-decoration: none; }
        .pagination .current-page { font-weight: bold; background-color: #ddd; }
    </style>

<?php } ?>


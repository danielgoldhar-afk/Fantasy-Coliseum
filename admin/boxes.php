<?php

if (!defined('ABSPATH')) exit;

global $wpdb;
$boxes_table = $wpdb->prefix . 'fantasy_boxes';
$pools_table = $wpdb->prefix . 'fantasy_pools';

// Fetch all pools for dropdown
$pools = $wpdb->get_results("SELECT * FROM $pools_table ORDER BY pool_id DESC");

// ───────────── SAVE NEW BOX ─────────────
if (isset($_POST['fm_add_box'])) {
    $name = sanitize_text_field($_POST['box_name']);
    $pool_id = intval($_POST['pool_id']);
    $max_entries = intval($_POST['max_entries']);

    if ($name && $pool_id && $max_entries) {
        $wpdb->insert($boxes_table, [
            'pool_id' => $pool_id,
            'name' => $name,
            'max_entries' => $max_entries,
            'created_at' => current_time('mysql')
        ]);

        echo '<div class="notice notice-success"><p>Box created successfully.</p></div>';
    }
}

// ───────────── GET ALL BOXES ─────────────
$boxes = $wpdb->get_results("
    SELECT b.*, p.name AS pool_name
    FROM {$boxes_table} AS b
    LEFT JOIN {$pools_table} AS p ON b.pool_id = p.pool_id
    ORDER BY b.box_id DESC
");

?>

<div class="wrap">
    <h1>Fantasy Boxes</h1>

    <h2>Add New Box</h2>

    <form method="post">
        <table class="form-table">
            <tr>
                <th>Box Name</th>
                <td><input type="text" name="box_name" class="regular-text" required></td>
            </tr>

            <tr>
                <th>Pool</th>
                <td>
                    <select name="pool_id" required>
                        <option value="">Select Pool</option>
                        <?php foreach ($pools as $pool): ?>
                            <option value="<?php echo $pool->pool_id; ?>">
                                <?php echo esc_html($pool->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Max Entries</th>
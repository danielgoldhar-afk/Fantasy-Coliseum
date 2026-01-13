<?php

if (!defined('ABSPATH')) exit;

global $wpdb;
$pools_table = $wpdb->prefix . 'fantasy_pools';
$leagues = get_posts([
    'post_type' => 'league',
    'post_status' => 'publish',
    'numberposts' => -1
]);

// ─────────────── SAVE POOL ───────────────
if (isset($_POST['fm_add_pool'])) {
    $name = sanitize_text_field($_POST['pool_name']);
    $league_id = intval($_POST['league_id']);

    if ($name && $league_id) {
        $wpdb->insert($pools_table, [
            'name' => $name,
            'league_id' => $league_id,
            'created_at' => current_time('mysql')
        ]);
        echo '<div class="notice notice-success"><p>Pool created successfully.</p></div>';
    }
}

// ─────────────── GET POOLS ───────────────
$pools = $wpdb->get_results("
    SELECT p.*, l.post_title AS league_name
    FROM {$pools_table} AS p
    LEFT JOIN {$wpdb->posts} AS l ON p.league_id = l.ID
    ORDER BY p.pool_id DESC
");

?>

<div class="wrap">
    <h1>Fantasy Pools</h1>

    <h2>Add New Pool</h2>
    <form method="post">
        <table class="form-table">
            <tr>
                <th>Pool Name</th>
                <td><input type="text" name="pool_name" class="regular-text" required></td>
            </tr>

            <tr>
                <th>League</th>
                <td>
                    <select name="league_id" required>
                        <option value="">Select League</option>
                        <?php foreach ($leagues as $league): ?>
                            <option value="<?php echo $league->ID; ?>">
                                <?php echo $league->post_title; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>

        <p><button type="submit" name="fm_add_pool" class="button button-primary">Create Pool</button></p>
    </form>

    <hr>

    <h2>All Pools</h2>

    <table class="widefat striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pool Name</th>
                <th>League</th>
                <th>Created</th>
            </tr>
        </thead>

        <tbody>
            <?php if ($pools): foreach ($pools as $p): ?>
                <tr>
                    <td><?php echo $p->pool_id; ?></td>
                    <td><?php echo esc_html($p->name); ?></td>
                    <td><?php echo esc_html($p->league_name); ?></td>
                    <td><?php echo $p->created_at; ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="4">No pools found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
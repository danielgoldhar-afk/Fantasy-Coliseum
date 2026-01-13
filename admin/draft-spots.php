<?php

if (!defined('ABSPATH')) exit;

global $wpdb;

$boxes_table = $wpdb->prefix . 'fantasy_boxes';
$spots_table = $wpdb->prefix . 'fantasy_draft_spots';
$entries_table = $wpdb->prefix . 'fantasy_entries';

// Fetch all boxes
$boxes = $wpdb->get_results("SELECT * FROM $boxes_table ORDER BY box_id DESC");

// ───────────── GENERATE SPOTS ─────────────
if (isset($_POST['generate_spots'])) {
    $box_id = intval($_POST['box_id']);

    if ($box_id) {
        // Get box details
        $box = $wpdb->get_row("SELECT * FROM $boxes_table WHERE box_id = $box_id");

        if ($box) {
            // Delete old spots
            $wpdb->delete($spots_table, ['box_id' => $box_id]);

            // Create new spots
            for ($i = 1; $i <= $box->max_entries; $i++) {
                $wpdb->insert($spots_table, [
                    'box_id' => $box_id,
                    'spot_number' => $i,
                    'taken_by' => null,
                    'created_at' => current_time('mysql')
                ]);
            }

            echo '<div class="notice notice-success"><p>Draft spots created successfully.</p></div>';
        }
    }
}

// Selected box for viewing spots
$selected_box_id = isset($_POST['box_select']) ? intval($_POST['box_select']) : 0;

$spots = [];
if ($selected_box_id) {
    $spots = $wpdb->get_results("
        SELECT s.*, e.user_id 
        FROM $spots_table AS s
        LEFT JOIN $entries_table AS e ON s.taken_by = e.entry_id
        WHERE s.box_id = $selected_box_id
        ORDER BY s.spot_number ASC
    ");
}

?>

<div class="wrap">
    <h1>Draft Spots Manager</h1>

    <form method="post">
        <h2>Select Box</h2>

        <select name="box_select">
            <option value="">Select a Box</option>
            <?php foreach ($boxes as $box): ?>
                <option value="<?php echo $box->box_id; ?>"
                    <?php selected($selected_box_id, $box->box_id); ?>>
                    <?php echo "#" . $box->box_id . " — " . esc_html($box->name); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="button button-primary">Load Spots</button>
    </form>

    <?php if ($selected_box_id): ?>

        <hr>

        <h2>Create / Regenerate Spots</h2>
        <form method="post">
            <input type="hidden" name="box_id" value="<?php echo $selected_box_id; ?>">
            <button class="button button-secondary" name="generate_spots">
                Generate Spots According to Max Entries
            </button>
        </form>

        <hr>

        <h2>Draft Spots</h2>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Spot #</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($spots): foreach ($spots as $s): ?>
                    <tr>
                        <td><?php echo $s->spot_number; ?></td>

                        <td>
                            <?php echo $s->taken_by ? '<span style="color:green;font-weight:bold;">Taken</span>' : 'Free'; ?>
                        </td>

                        <td>
                            <?php
                                echo $s->taken_by
                                    ? "User ID: " . $s->user_id
                                    : "-";
                            ?>
                        </td>

                        <td><?php echo $s->created_at; ?></td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4">No spots found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>
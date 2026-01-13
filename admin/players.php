<?php

if (!defined('ABSPATH')) exit;

global $wpdb;

$players_table = $wpdb->prefix . 'fantasy_players';
$boxes_table   = $wpdb->prefix . 'fantasy_boxes';

// ───────── SAVE PLAYER ─────────
if (isset($_POST['save_player'])) {

    $wpdb->insert($players_table, [
        'box_id'      => intval($_POST['box_id']),
        'player_name' => sanitize_text_field($_POST['player_name']),
        'team'        => sanitize_text_field($_POST['team']),
        'position'    => sanitize_text_field($_POST['position']),
        'image_url'   => esc_url_raw($_POST['image_url']),
        'created_at'  => current_time('mysql')
    ]);

    echo '<div class="notice notice-success"><p>Player added successfully.</p></div>';
}

// ───────── DELETE PLAYER ─────────
if (isset($_GET['delete'])) {
    $wpdb->delete($players_table, ['player_id' => intval($_GET['delete'])]);
    echo '<div class="notice notice-success"><p>Player deleted.</p></div>';
}

$players = $wpdb->get_results("
    SELECT p.*, b.name AS box_name
    FROM $players_table AS p
    LEFT JOIN $boxes_table AS b ON b.box_id = p.box_id
    ORDER BY p.player_id DESC
");

$boxes = $wpdb->get_results("SELECT * FROM $boxes_table ORDER BY box_id DESC");

?>

<div class="wrap">
    <h1>Players Manager</h1>

    <h2>Add New Player</h2>

    <form method="post">

        <input type="hidden" name="save_player" value="1">

        <table class="form-table">
            <tr>
                <th><label>Box</label></th>
                <td>
                    <select name="box_id" required>
                        <option value="">Select Box</option>
                        <?php foreach ($boxes as $box): ?>
                            <option value="<?php echo $box->box_id; ?>">
                                #<?php echo $box->box_id; ?> - <?php echo esc_html($box->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th><label>Player Name</label></th>
                <td>
                    <input type="text" name="player_name" required class="regular-text">
                </td>
            </tr>

            <tr>
                <th><label>Team</label></th>
                <td>
                    <input type="text" name="team" class="regular-text">
                </td>
            </tr>

            <tr>
                <th><label>Position</label></th>
                <td>
                    <input type="text" name="position" class="regular-text">
                </td>
            </tr>

            <tr>
                <th><label>Image URL</label></th>
                <td>
                    <input type="url" name="image_url" class="regular-text">
                    <p class="description">Paste full image URL (or use Media Library URL)</p>
                </td>
            </tr>
        </table>

        <button class="button button-primary">Add Player</button>
    </form>


    <div id="fmPlayerModal" style="display:none;">
        <h2 id="playerModalTitle">Add Player</h2>
    
        <input type="hidden" id="player_id">
        <label>Box</label>
        <select id="player_box">
            <?php foreach($boxes as $box): ?>
                <option value="<?php echo $box->box_id; ?>"><?php echo esc_html($box->name); ?></option>
            <?php endforeach; ?>
        </select>
    
        <label>Player Name</label>
        <input type="text" id="player_name">
    
        <label>Team</label>
        <input type="text" id="player_team">
    
        <label>Position</label>
        <input type="text" id="player_position">
    
        <label>Image URL</label>
        <input type="url" id="player_image">
    
        <button id="savePlayer" class="button button-primary">Save Player</button>
    </div>


    <hr>

    <h2>All Players</h2>

    <table class="widefat striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Box</th>
                <th>Player</th>
                <th>Team</th>
                <th>Position</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($players): foreach ($players as $p): ?>
            <tr>
                <td><?php echo $p->player_id; ?></td>
                <td><?php echo esc_html($p->box_name); ?></td>
                <td><?php echo esc_html($p->player_name); ?></td>
                <td><?php echo esc_html($p->team); ?></td>
                <td><?php echo esc_html($p->position); ?></td>
                <td>
                    <?php if ($p->image_url): ?>
                        <img src="<?php echo esc_url($p->image_url); ?>"
                             width="50" height="50">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="?page=fantasy_players&delete=<?php echo $p->player_id; ?>"
                        class="button button-small">Delete</a>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr><td colspan="7">No players found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<div class="wrap">
    <h1>Fantasy Pools Manager</h1>

    <button id="addPoolBtn" class="button button-primary">Add New Pool</button>

    <table class="widefat striped" id="fmPoolsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pool Name</th>
                <th>League</th>
                <th>Boxes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Filled dynamically via AJAX -->
        </tbody>
    </table>
</div>

<!-- Pool Modal -->
<div id="fmPoolModal" style="display:none;">
    <h2 id="modalTitle">Add Pool</h2>
    <input type="hidden" id="pool_id">
    <label>Pool Name</label>
    <input type="text" id="pool_name">
    <label>Select League</label>
    <select id="pool_league">
        <?php 
            $leagues = get_posts(['post_type'=>'league','numberposts'=>-1]);
            foreach($leagues as $l) {
                echo "<option value='{$l->ID}'>{$l->post_title}</option>";
            }
        ?>
    </select>
    <button id="savePool" class="button button-primary">Save Pool</button>

    <hr>
    <h3>Boxes</h3>
    <table id="boxesTable" class="widefat">
        <thead><tr><th>Name</th><th>Max Entries</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($boxes_for_pool as $b): ?>
        <tr>
            <td class="boxName"><?php echo esc_html($b->name); ?></td>
            <td class="boxMax"><?php echo intval($b->max_entries); ?></td>
            <td>
                <button class="button editBox" data-id="<?php echo $b->box_id; ?>">Edit</button>
                <button class="button deleteBox" data-id="<?php echo $b->box_id; ?>">Delete</button>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
>
    </table>

    <h4>Add Box</h4>
    <input type="text" id="box_name" placeholder="Box Name">
    <input type="number" id="box_max" placeholder="Max Entries" value="1">
    <button id="addBoxBtn" class="button button-secondary">Add Box</button>
</div>
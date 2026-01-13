<?php 

add_action('init', 'fc_start_session', 1);
function fc_start_session() {
    if (!session_id()) {
        session_start();
    }
}


/**
 * Store a custom error log in the database
 *
 * @param string $title      Short title of the error (required)
 * @param string $type       Type/category of the error (default: 'general')
 * @param string $code       Error code (optional)
 * @param string $strength   Severity: low, medium, high (default: 'low')
 * @param string $desc       Full description (optional)
 */
function log_custom_error($title, $type = 'general', $code = '', $strength = 'low', $desc = '') {
    if (empty($title)) return; // Require at least a title

    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_error_logs';

    $wpdb->insert(
        $table_name,
        [
            'error_title'    => $title,
            'error_type'     => $type,
            'error_code'     => $code,
            'error_strength' => $strength,
            'error_desc'     => $desc,
            'error_time'     => current_time('mysql')
        ],
        ['%s','%s','%s','%s','%s','%s']
    );
}




function fantasy_remove_duplicate_player_stats_keep_oldest($table_name) {
    global $wpdb;

    $table = $wpdb->prefix . $table_name;

     $rows = $wpdb->get_results("
        SELECT id, player_id
        FROM {$table}
        ORDER BY player_id, id ASC
    ");

    $seen = [];
    $deleted = 0;

    foreach ($rows as $row) {
        if (isset($seen[$row->player_id])) {
            $wpdb->delete($table, ['id' => $row->id], ['%d']);
            $deleted++;
        } else {
            $seen[$row->player_id] = true;
        }
    }

    return $deleted;
}


function fantasy_remove_duplicate_players_keep_oldest($table_name) {
    global $wpdb;

    $table = $wpdb->prefix . $table_name;
 	$rows = $wpdb->get_results("
        SELECT id, player_api_id
        FROM {$table}
        ORDER BY player_api_id, id ASC
    ");

    $seen = [];
    $deleted = 0;

    foreach ($rows as $row) {
        if (isset($seen[$row->player_id])) {
            $wpdb->delete($table, ['id' => $row->id], ['%d']);
            $deleted++;
        } else {
            $seen[$row->player_id] = true;
        }
    }

    return $deleted;
}




function fantasy_get_wallet_balance($user_id) {
    global $wpdb;

    $table = $wpdb->prefix . 'fantasy_wallets';

    $balance = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT balance 
             FROM $table 
             WHERE user_id = %d 
             
             LIMIT 1",
            $user_id
        )
    );

    return $balance !== null ? floatval($balance) : 0;
}



function wp_default_site_logo_field_callback() {

    $logo_id  = get_theme_mod( 'custom_logo' );
    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';
    ?>

    <div>
        <img
            id="wp-site-logo-preview"
            src="<?php echo esc_url( $logo_url ); ?>"
            style="max-width:150px; margin-bottom:10px; <?php echo $logo_url ? '' : 'display:none;'; ?>"
        />

        <input
            type="hidden"
            id="wp_default_site_logo"
            value="<?php echo esc_attr( $logo_id ); ?>"
        />

        <button type="button" class="button" id="wp-site-logo-upload">
            Select Logo
        </button>

        <button
            type="button"
            class="button"
            id="wp-site-logo-remove"
            style="<?php echo $logo_url ? '' : 'display:none;'; ?>"
        >
            Remove
        </button>
    </div>

    <?php
}

/**
 * Add default WordPress Site Logo to General Settings
 */
function wp_add_default_site_logo_to_general_settings() {

    add_settings_field(
        'wp_default_site_logo',
        'Site Logo',
        'wp_default_site_logo_field_callback',
        'general'
    );

}
add_action( 'admin_init', 'wp_add_default_site_logo_to_general_settings' );



function wp_site_logo_general_scripts( $hook ) {

    if ( $hook !== 'options-general.php' ) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script( 'jquery' );

    wp_add_inline_script(
        'jquery',
        "
        jQuery(function($){

            let frame;

            $('#wp-site-logo-upload').on('click', function(e){
                e.preventDefault();

                if (frame) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: 'Select Site Logo',
                    button: { text: 'Use as Site Logo' },
                    multiple: false
                });

                frame.on('select', function(){
                    const attachment = frame.state().get('selection').first().toJSON();

                    $('#wp_default_site_logo').val(attachment.id);
                    $('#wp-site-logo-preview')
                        .attr('src', attachment.url)
                        .show();

                    $('#wp-site-logo-remove').show();

                    saveSiteLogo(attachment.id);
                });

                frame.open();
            });

            $('#wp-site-logo-remove').on('click', function(){
                $('#wp_default_site_logo').val('');
                $('#wp-site-logo-preview').hide();
                $(this).hide();

                saveSiteLogo('');
            });

            function saveSiteLogo(logo_id){
                $.post(ajaxurl, {
                    action: 'save_default_site_logo',
                    logo_id: logo_id,
                    _wpnonce: '" . wp_create_nonce( 'save_site_logo' ) . "'
                });
            }

        });
        "
    );
}
add_action( 'admin_enqueue_scripts', 'wp_site_logo_general_scripts' );



function wp_save_default_site_logo() {

    check_ajax_referer( 'save_site_logo' );

    $logo_id = isset($_POST['logo_id']) ? absint($_POST['logo_id']) : 0;

    set_theme_mod( 'custom_logo', $logo_id );

    wp_send_json_success();
}
add_action( 'wp_ajax_save_default_site_logo', 'wp_save_default_site_logo' );






// ----------------------------------------------
register_activation_hook(__FILE__, 'fantasy_manager_activate');
function fantasy_manager_activate() {
    global $wpdb;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();

    // TABLE 1: Pools
    $sql1 = "CREATE TABLE {$wpdb->prefix}fantasy_pools (
        pool_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        league_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY(pool_id)
    ) $charset_collate;";
    dbDelta($sql1);

    // TABLE 2: Boxes
    $sql2 = "CREATE TABLE {$wpdb->prefix}fantasy_boxes (
        box_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        pool_id BIGINT UNSIGNED NOT NULL,
        name VARCHAR(255) NOT NULL,
        max_entries INT NOT NULL DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY(box_id)
    ) $charset_collate;";
    dbDelta($sql2);

    // TABLE 3: Draft Spots
    $sql3 = "CREATE TABLE {$wpdb->prefix}fantasy_draft_spots (
        spot_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        box_id BIGINT UNSIGNED NOT NULL,
        spot_number INT NOT NULL,
        taken_by BIGINT UNSIGNED DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY(spot_id)
    ) $charset_collate;";
    dbDelta($sql3);

    // TABLE 4: ENTRIES (Your requested table)
    $sql4 = "CREATE TABLE {$wpdb->prefix}fantasy_entries (
        entry_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT UNSIGNED NOT NULL,
        league_id BIGINT UNSIGNED NOT NULL,
        pool_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY(entry_id)
    ) $charset_collate;";
    dbDelta($sql4);
// TABLE 5: Picks
$sql5 = "CREATE TABLE {$wpdb->prefix}fantasy_picks (
    pick_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    entry_id BIGINT UNSIGNED NOT NULL,
    box_id BIGINT UNSIGNED NOT NULL,
    spot_id BIGINT UNSIGNED DEFAULT NULL,
    player_id BIGINT UNSIGNED DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(pick_id)
) $charset_collate;";
dbDelta($sql5);
// TABLE 6: Players
$sql6 = "CREATE TABLE {$wpdb->prefix}fantasy_players (
    player_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    box_id BIGINT UNSIGNED NOT NULL,
    player_name VARCHAR(255) NOT NULL,
    team VARCHAR(255) DEFAULT NULL,
    position VARCHAR(50) DEFAULT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(player_id)
) $charset_collate;";
dbDelta($sql6);



}
add_action('init', 'fantasy_create_player_selections_table');
function fantasy_create_player_selections_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . "fantasy_pool_player_selections";

    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table_name} (
            player_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            player_api_id BIGINT DEFAULT NULL,
            first_name VARCHAR(100) DEFAULT NULL,
            last_name VARCHAR(100) DEFAULT NULL,
            box_id BIGINT UNSIGNED NOT NULL,
            player_name VARCHAR(255) NOT NULL,
            jersey_number VARCHAR(20) DEFAULT NULL,
            height VARCHAR(20) DEFAULT NULL,
            weight VARCHAR(10) DEFAULT NULL,
            birth_date DATE DEFAULT NULL,
            age INT DEFAULT NULL,
            rookie TINYINT(1) DEFAULT 0,
            handedness VARCHAR(10) DEFAULT NULL,
            team VARCHAR(255) DEFAULT NULL,
            position VARCHAR(50) DEFAULT NULL,
            image_url VARCHAR(500) DEFAULT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY (player_id)
        ) $charset_collate;";

        dbDelta($sql);
    }
}


register_activation_hook(__FILE__, 'fantasy_manager_create_or_update_stats_table');

function fantasy_manager_create_or_update_stats_table() {
    global $wpdb;

    $table = $wpdb->prefix . 'fantasy_player_stats';

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();

    // List of required columns
    $required_columns = [
        'id'                     => "BIGINT UNSIGNED NOT NULL AUTO_INCREMENT",
        'player_id'              => "BIGINT",
        'season'                 => "VARCHAR(20) NULL",
        'games_played'           => "INT NULL DEFAULT 0",
        'goals'                  => "INT NULL DEFAULT 0",
        'assists'                => "INT NULL DEFAULT 0",
        'points'                 => "INT NULL DEFAULT 0",
        'powerplay_goals'        => "INT NULL DEFAULT 0",
        'shorthanded_goals'      => "INT NULL DEFAULT 0",
        'game_winning_goals'     => "INT NULL DEFAULT 0",
        'plus_minus'             => "INT NULL DEFAULT 0",
        'shots'                  => "INT NULL DEFAULT 0",
        'shot_percentage'        => "FLOAT NULL DEFAULT 0",
        'hits'                   => "INT NULL DEFAULT 0",
        'takeaways'              => "INT NULL DEFAULT 0",
        'faceoff_percent'        => "FLOAT NULL DEFAULT 0",
        'penalty_minutes'        => "INT NULL DEFAULT 0",
        'fights'                 => "INT NULL DEFAULT 0",
        'time_on_ice_seconds'    => "INT NULL DEFAULT 0",
        'fantasy_score'          => "FLOAT NULL DEFAULT 0",
        'rank_global'            => "INT NULL",
        'updated_at'             => "DATETIME NULL"
    ];

    // Check if table exists
    $table_exists = $wpdb->get_var(
        $wpdb->prepare("SHOW TABLES LIKE %s", $table)
    );

    if (!$table_exists) {
        // Create the table if it does not exist
        $sql = "CREATE TABLE $table (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            player_id BIGINT,
            season VARCHAR(20) NULL,
            games_played INT NULL DEFAULT 0,
            goals INT NULL DEFAULT 0,
            assists INT NULL DEFAULT 0,
            points INT NULL DEFAULT 0,
            powerplay_goals INT NULL DEFAULT 0,
            shorthanded_goals INT NULL DEFAULT 0,
            game_winning_goals INT NULL DEFAULT 0,
            plus_minus INT NULL DEFAULT 0,
            shots INT NULL DEFAULT 0,
            shot_percentage FLOAT NULL DEFAULT 0,
            hits INT NULL DEFAULT 0,
            takeaways INT NULL DEFAULT 0,
            faceoff_percent FLOAT NULL DEFAULT 0,
            penalty_minutes INT NULL DEFAULT 0,
            fights INT NULL DEFAULT 0,
            time_on_ice_seconds INT NULL DEFAULT 0,
            fantasy_score FLOAT NULL DEFAULT 0,
            rank_global INT NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        dbDelta($sql);
        return;
    }

    // If table exists — check for missing columns and add them
    $existing_columns = $wpdb->get_results("SHOW COLUMNS FROM $table", ARRAY_A);

    $existing_column_names = array_column($existing_columns, 'Field');

    foreach ($required_columns as $column => $col_type) {
        if (!in_array($column, $existing_column_names)) {
            $wpdb->query("ALTER TABLE $table ADD $column $col_type");
        }
    }
}



// if (is_admin()) {
//     // Include all admin files
//     require_once plugin_dir_path(__FILE__) . 'admin/pools-ajax.php';
//     require_once plugin_dir_path(__FILE__) . 'admin/ajax-handlers.php';
//     require_once plugin_dir_path(__FILE__) . 'admin/players.php';
// }




// Theme setup
function fantasy_coliseum_setup() {
    add_theme_support('title-tag');
    register_nav_menus([
        'primary' => __('Primary Menu', 'fantasy-coliseum'),
    ]);
}
add_action('after_setup_theme', 'fantasy_coliseum_setup');

// Load style.css
function fantasy_coliseum_assets() {
    wp_enqueue_style('fantasy-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'fantasy_coliseum_assets');







add_action('admin_enqueue_scripts', function($hook) {
    if ($hook != 'toplevel_page_fantasy_manager') return;

    wp_enqueue_style('fm-admin-css', plugin_dir_url(__FILE__) . 'admin/css/admin-style.css');
    wp_enqueue_script('fm-admin-js', plugin_dir_url(__FILE__) . 'admin/js/admin-pools.js', ['jquery'], null, true);

    wp_localize_script('fm-admin-js', 'fm_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('fm_nonce')
    ]);
});



// ========================
// ✅ LEAGUE REWRITE RULES
// ========================

function custom_league_rewrite_rules() {
    add_rewrite_rule(
        'league/([^/]+)/home/?$',
        'index.php?pagename=league-home&league_id=$matches[1]',
        'top'
    );
	add_rewrite_rule(
        'contest/([^/]+)/players-picker/?$',
        'index.php?pagename=players-picker&league_id=$matches[1]',
        'top'
    );
	add_rewrite_rule(
        '^join-league/([^/]+)/?$',
        'index.php?pagename=join-league&league_id=$matches[1]',
        'top'
    );
	add_rewrite_rule(
        '^join-league/([^/]+)/success/?$',
        'index.php?pagename=join-contest-success&league_success_id=$matches[1]',
        'top'
    );
	
	add_rewrite_rule(
       '^league/([^/]+)/leaderboard/?$',
        'index.php?pagename=league-leaderboard&league_id=$matches[1]',
        'top'
    );
	
	
}
add_action('init', 'custom_league_rewrite_rules');

// Add league_id and sport to query vars
function custom_add_league_query_vars($vars) {
   
    $vars[] = 'league_id';
	$vars[] = 'league_success_id';
    return $vars;
}
add_filter('query_vars', 'custom_add_league_query_vars');



function custom_league_scores_rewrite() {
    add_rewrite_rule(
        '^news/([^/]+)/scores/?$',
        'index.php?pagename=league-scores&sport=$matches[1]',
        'top'
    );
	  add_rewrite_rule(
        '^league/([^/]+)/invite/?$',
        'index.php?pagename=league-invite&league_slug=$matches[1]',
        'top'
    );
}
add_action('init', 'custom_league_scores_rewrite');

function add_custom_league_scores_query_var($vars) {
    $vars[] = 'sport';
    return $vars;
}
add_filter('query_vars', 'add_custom_league_scores_query_var');



function custom_league_new_rewrite() {
    add_rewrite_rule(
        '^league/([^/]+)/new/?$', // matches /league/nba/new
        'index.php?pagename=create-league&sport=$matches[1]', // loads the page with slug 'league-new'
        'top'
    );
}
add_action('init', 'custom_league_new_rewrite');

function add_create_league_query_var($vars) {
    $vars[] = 'sport';
    return $vars;
}
add_filter('query_vars', 'add_create_league_query_var');





// ========================
// ✅ FIX "sport" ARRAY ISSUE
// ========================
function fix_sport_query_var() {
    $sport = get_query_var('sport');

    if (is_array($sport)) {
        $sport = reset($sport); // Take first array value
        set_query_var('sport', sanitize_text_field($sport));
    }
}
add_action('wp', 'fix_sport_query_var');







// 🔹 Auto-load all shortcode files
add_action('init', function() {
    $shortcodes_dir = plugin_dir_path(__FILE__) . 'shortcodes/';

    if (is_dir($shortcodes_dir)) {
        foreach (glob($shortcodes_dir . '*.php') as $file) {
            include_once $file;
        }
    }
});



// 🔹 Auto-load all shortcode files
add_action('init', function() {
    $schedules_dir = plugin_dir_path(__FILE__) . 'schedules/';

    if (is_dir($schedules_dir)) {
        foreach (glob($schedules_dir . '*.php') as $file) {
            include_once $file;
        }
    }
});


// 🔹 Auto-load all ajax  files
add_action('init', function() {
    $shortcodes_dir = plugin_dir_path(__FILE__) . 'ajax/';

    if (is_dir($shortcodes_dir)) {
        foreach (glob($shortcodes_dir . '*.php') as $file) {
            include_once $file;
        }
    }
});



// 🔹 Auto-load all ajax  files
add_action('init', function() {
    $shortcodes_dir = plugin_dir_path(__FILE__) . 'pages/';

    if (is_dir($shortcodes_dir)) {
        foreach (glob($shortcodes_dir . '*.php') as $file) {
            include_once $file;
        }
    }
});




function fc_get_spots_filled($contest_id) {
    global $wpdb;
    $entries_table = $wpdb->prefix . 'fantasy_entries';

    $count = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(*) FROM $entries_table WHERE league_id = %d", $contest_id)
    );
	// 	echo "<pre>";

	// 	print_r($count);
	// 	echo "</pre>";

    return intval($count);
}


function nba_scoreboard_shortcode() {

    // Detect league from URL or fallback to nba
    $league = isset($_GET['league']) ? strtolower(sanitize_text_field($_GET['league'])) : 'nba';

    ob_start(); ?>
    <style>
        
        /* ================================
   SCOREBOARD GRID
================================ */
.nba-scoreboard {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    width: 100%;
}

/* Tablet */
@media (max-width: 1024px) {
    .nba-scoreboard {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Mobile */
@media (max-width: 640px) {
    .nba-scoreboard {
        grid-template-columns: 1fr;
    }
}

/* ================================
   GAME CARD
================================ */
.nba-game-card {
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.08),
        rgba(0,0,0,0.35)
    );
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 16px;
    padding: 18px 20px;
    color: #fff;
    box-shadow: 0 10px 24px rgba(0,0,0,0.35);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    backdrop-filter: blur(8px);
    transition: transform .25s ease, box-shadow .25s ease;
}

.nba-game-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 36px rgba(0,0,0,0.45);
}

/* ================================
   GAME TIME
================================ */
.game-time {
    font-size: 13px;
    font-weight: 500;
    color: #d8d8d8;
    margin-bottom: 14px;
}

/* ================================
   TEAM ROW
================================ */
.team-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 12px 14px;
    border-radius: 10px;
    margin-bottom: 12px;
}

.team-card img {
    width: 40px;
    height: 40px;
    background: #fff;
    border-radius: 50%;
    padding: 3px;
    object-fit: contain;
}

/* ================================
   TEAM TEXT
================================ */
.team-name {
    font-size: 15px;
    font-weight: 700;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.team-type {
    font-size: 12px;
    opacity: .7;
    margin-top: 2px;
}

/* ================================
   BOX SCORE BUTTON
================================ */
.box-score-btn {
    margin-top: 14px;
    align-self: flex-end;
    display: none !important;
    align-items: center;
    gap: 6px;
    background: #ff9800;
    color: #000;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: background .25s ease, transform .2s ease;
    
}

.box-score-btn:hover {
    background: #ffb547;
    transform: scale(1.05);
}

/* ================================
   PAGINATION
================================ */
.fc-pagination-controls {
    justify-content: center;
    margin-top: 30px;
}

.fc-btn {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
    padding: 8px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: background .2s ease, transform .15s ease;
}

.fc-btn:hover:not(:disabled) {
    background: rgba(255,255,255,0.25);
    transform: translateY(-2px);
}

.fc-btn:disabled {
    opacity: .4;
    cursor: not-allowed;
}

        
    </style>
    <div class="fc-scoreboard-wrapper" data-league="<?php echo esc_attr($league); ?>">

        <!-- AJAX-loaded games go here -->
        <div id="nba-scoreboard">
            <p style="color:#fff">Loading games...</p>
        </div>

        <!-- Pagination -->
        <div class="fc-pagination-controls" style="display:flex;align-items:center;gap:10px;margin-top:20px;">
            <button id="nba-prev" class="fc-btn" disabled>Prev</button>
            <span style="color:#fff">
                Page <span id="nba-page">1</span>
            </span>
            <button id="nba-next" class="fc-btn">Next</button>
        </div>

    </div>

    <script>
    jQuery(document).ready(function($){

        let currentPage = 1;
        const ajaxUrl = '<?php echo site_url('/wp-admin/admin-ajax.php'); ?>';
        let currentGame = '<?php echo esc_js($league); ?>';

        function loadScores(date) {
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                data: {
                    action: 'fetch_game_scores',
                    page: currentPage,
                    date: date,
                    game: currentGame
                },
                beforeSend: function () {
                    $('#nba-scoreboard').html('<p style="color:#fff">Loading...</p>');
                },
                success: function (res) {
                    if (res.success) {
                        $('#nba-scoreboard').html(res.data.html);

                        const total = res.data.total;
                        const perPage = res.data.per_page;
                        const totalPages = Math.ceil(total / perPage);

                        $('#nba-page').text(currentPage);
                        $('#nba-prev').prop('disabled', currentPage <= 1);
                        $('#nba-next').prop('disabled', currentPage >= totalPages);
                    } else {
                        $('#nba-scoreboard').html('<p style="color:#fff">Error loading games</p>');
                    }
                }
            });
        }

        // Initial load (today)
        let today = new Date().toISOString().split("T")[0];
        loadScores(today);

        // Pagination buttons
        $('#nba-prev').on('click', function(){
            if (currentPage > 1) {
                currentPage--;
                loadScores(today);
            }
        });

        $('#nba-next').on('click', function(){
            currentPage++;
            loadScores(today);
        });

        // Date click support (from your calendar)
        $(document).on('click', '.date-item', function(){
            currentPage = 1;
            let date = $(this).data('date');
            loadScores(date);
        });

    });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('nba_scoreboard', 'nba_scoreboard_shortcode');




function nba_scoreboard_enqueue_scripts() {
    wp_enqueue_script('nba-scoreboard-ajax', plugin_dir_url(__FILE__) . 'nba-scoreboard.js', ['jquery'], null, true);
    wp_localize_script('nba-scoreboard-ajax', 'nbaScoreboard', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'nba_scoreboard_enqueue_scripts');













// You can place this inside header.php or output via a shortcode


// === Register Custom Post Type: Sport ===
function register_sport_post_type() {
    $labels = [
        'name'                  => _x('Sports', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Sport', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Sports', 'textdomain'),
        'name_admin_bar'        => __('Sport', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Sport', 'textdomain'),
        'edit_item'             => __('Edit Sport', 'textdomain'),
        'new_item'              => __('New Sport', 'textdomain'),
        'view_item'             => __('View Sport', 'textdomain'),
        'view_items'            => __('View Sports', 'textdomain'),
        'search_items'          => __('Search Sports', 'textdomain'),
        'not_found'             => __('No sports found', 'textdomain'),
        'not_found_in_trash'    => __('No sports found in Trash', 'textdomain'),
        'all_items'             => __('All Sports', 'textdomain'),
        'archives'              => __('Sport Archives', 'textdomain'),
        'attributes'            => __('Sport Attributes', 'textdomain'),
        'insert_into_item'      => __('Insert into sport', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this sport', 'textdomain'),
        'featured_image'        => __('Featured Image', 'textdomain'),
        'set_featured_image'    => __('Set featured image', 'textdomain'),
        'remove_featured_image' => __('Remove featured image', 'textdomain'),
        'use_featured_image'    => __('Use as featured image', 'textdomain'),
    ];

    $args = [
        'label'                 => __('Sport', 'textdomain'),
        'description'           => __('Sports custom post type', 'textdomain'),
        'labels'                => $labels,
        'supports'              => ['title', 'editor', 'thumbnail', 'excerpt'],
        'taxonomies'            => ['category', 'post_tag'],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-football', // ⚽️ Football icon
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // enables Gutenberg + REST API
        'rewrite'               => ['slug' => 'sports'],
    ];

    register_post_type('sport', $args);
}
add_action('init', 'register_sport_post_type');


function create_league_post_type() {
    $labels = array(
        'name' => 'Leagues',
        'singular_name' => 'League',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New League',
        'edit_item' => 'Edit League',
        'new_item' => 'New League',
        'view_item' => 'View League',
        'search_items' => 'Search Leagues',
        'not_found' => 'No leagues found',
        'not_found_in_trash' => 'No leagues found in Trash',
        'all_items' => 'All Leagues',
        'menu_name' => 'Leagues',
        'name_admin_bar' => 'League',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_in_menu' => true,
        'supports' => array('title', 'editor', 'author'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'leagues'),
		'menu_position'         => 6,
		'menu_icon'             => 'dashicons-image-filter', // ⚽️ Football icon
    );

    register_post_type('league', $args);
}
add_action('init', 'create_league_post_type');


// Add featured image column to 'sport' post type admin list (after checkbox)
add_filter('manage_sport_posts_columns', 'add_sport_thumbnail_column');
function add_sport_thumbnail_column($columns) {
    $new = [];
    foreach ($columns as $key => $value) {
        $new[$key] = $value;
        if ($key === 'cb') { // Add AFTER the checkbox
            $new['thumbnail'] = __('Image');
        }
    }
    return $new;
}

// Display the featured image in the column
add_action('manage_sport_posts_custom_column', 'show_sport_thumbnail_column', 10, 2);
function show_sport_thumbnail_column($column, $post_id) {
    if ($column === 'thumbnail') {
        $thumb = get_the_post_thumbnail($post_id, [60, 60]);
        echo $thumb ? $thumb : '<span style="color:#999;">—</span>';
    }
}

// Set column width styling
add_action('admin_head', 'sport_thumbnail_column_width');
function sport_thumbnail_column_width() {
    echo '<style>
        .column-thumbnail { width: 70px; text-align: center; }
        .column-thumbnail img { border-radius: 6px; }
    </style>';
}





// Shortcode: [fc_create_league]
function fc_create_league_form_shortcode() {
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fc_create_league_nonce']) && wp_verify_nonce($_POST['fc_create_league_nonce'], 'fc_create_league_action')) {
        $league_name     = sanitize_text_field($_POST['league_name']);
        $team_name       = sanitize_text_field($_POST['team_name']);
        $league_type     = sanitize_text_field($_POST['league_type']);
		 $max_participants = sanitize_text_field($_POST['max_participants']);
        $draft_type      = sanitize_text_field($_POST['draft_type']);
        $entry_fee       = sanitize_text_field($_POST['entry_fee']);
        $description     = sanitize_textarea_field($_POST['league_description']);
		$prize = sanitize_text_field($_POST['prize']);
        $user_id         = get_current_user_id();

        $post_id = wp_insert_post([
            'post_title'   => $league_name,
            'post_content' => $description,
            'post_type'    => 'league',
            'post_status'  => 'publish',
            'post_author'  => $user_id,
        ]);

        if ($post_id) {
            update_post_meta($post_id, '_max_participants', $max_participants);
			update_post_meta($post_id, '_team_name', $team_name);
            update_post_meta($post_id, '_league_type', $league_type);
            update_post_meta($post_id, '_draft_type', $draft_type);
            update_post_meta($post_id, '_entry_fee', $entry_fee);
			update_post_meta($post_id, '_prize', $prize);
            update_post_meta($post_id, '_created_by', $user_id);

            echo '<div class="fc-success">✅ League created successfully!</div>';
        } else {
            echo '<div class="fc-error">❌ Failed to create league. Please try again.</div>';
        }
    }

    ?>
    <form method="post" class="fc-league-form">
        <?php wp_nonce_field('fc_create_league_action', 'fc_create_league_nonce'); ?>

        <label>League Name*</label>
        <input type="text" name="league_name" required>
		
		<label>Max Participants*</label>
        <input type="text" name="max_participants" required>

        <label>Team Name*</label>
        <input type="text" name="team_name" required>

       <label>League Type*</label>
		<select name="league_type" required>
			<option value="">Select League Type</option>

			<?php
			$sports = get_posts([
				'post_type'      => 'sport',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC'
			]);

			if ($sports) {
				foreach ($sports as $sport) {
					echo '<option value="' . esc_attr($sport->post_name) . '">' . esc_html($sport->post_title) . '</option>';
				}
			}
			?>
		</select>

        <label>Draft Type*</label>
        <select name="draft_type" required>
            <option value="">Select Draft Type</option>
            <option value="Live Online Standard">Live Online Standard</option>
            <option value="Offline">Offline</option>
            <option value="Auto">Auto</option>
        </select>

        <label>Entry Fee*</label>
        <input type="text" name="entry_fee" required>

        <label>League Description</label>
        <textarea name="league_description" rows="4"></textarea>

        <button type="submit">Create League</button>
    </form>

    <style>
        .fc-league-form {
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
        }
        .fc-league-form label {
            display: block;
            font-weight: 600;
            margin-top: 12px;
        }
        .fc-league-form input, 
        .fc-league-form select, 
        .fc-league-form textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .fc-league-form button {
            margin-top: 18px;
            padding: 10px 20px;
            background: #0a4bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .fc-league-form button:hover {
            background: #0736b8;
        }
        .fc-success, .fc-error {
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .fc-success { color: green; }
        .fc-error { color: red; }
    </style>
    <?php

    return ob_get_clean();
}
add_shortcode('fc_create_league', 'fc_create_league_form_shortcode');


add_action('init', 'ensure_league_tables_exist');
function ensure_league_tables_exist() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
	$leagues_table = $wpdb->get_blog_prefix() . 'leagues';
	$league_meta_table = $wpdb->get_blog_prefix() . 'league_meta';


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // Always run dbDelta — it creates tables only if missing
    $sql1 = "CREATE TABLE $leagues_table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        league_id VARCHAR(32) NOT NULL UNIQUE,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        league_name VARCHAR(255) NOT NULL,
        team_name VARCHAR(255) NOT NULL,
        league_icon VARCHAR(255) DEFAULT NULL,
        league_image VARCHAR(255) DEFAULT NULL,
        league_type VARCHAR(100) NOT NULL,
        draft_type VARCHAR(100) NOT NULL,
        entry_fee VARCHAR(100) NOT NULL,
        promo_code VARCHAR(100) DEFAULT NULL,
        description TEXT DEFAULT NULL,
        status VARCHAR(50) DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY(id),
        KEY league_id (league_id)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE $league_meta_table (
        meta_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        league_id VARCHAR(32) NOT NULL,
        meta_key VARCHAR(255) NOT NULL,
        meta_value LONGTEXT NULL,
        PRIMARY KEY(meta_id),
        KEY league_id (league_id),
        KEY meta_key (meta_key)
    ) $charset_collate;";

    dbDelta($sql1);
    dbDelta($sql2);
}



add_shortcode('create_league_form', 'render_create_league_form');


add_action("wp_ajax_upload_league_wallpaper", "upload_league_wallpaper");

function upload_league_wallpaper() {

    check_ajax_referer('create_league_nonce', 'nonce');

    $league_id = intval($_POST['league_id']);

    if (!$league_id) {
        wp_send_json(['success' => false, 'message' => 'Invalid league ID']);
    }

    if (empty($_FILES['wallpaper']['name'])) {
        wp_send_json(['success' => false, 'message' => 'No file uploaded']);
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // Upload and attach to league post
    $attachment_id = media_handle_upload('wallpaper', $league_id);

    if (is_wp_error($attachment_id)) {
        wp_send_json(['success' => false, 'message' => 'Upload failed']);
    }

    // Get URL
    $url = wp_get_attachment_url($attachment_id);

    // ✅ Save wallpaper meta
    update_post_meta($league_id, 'league_image', $url);

    wp_send_json([
        'success' => true,
        'url'     => $url,
    ]);
}
add_action('wp_enqueue_scripts', function () {
	

 wp_enqueue_script(
            'league-wallpaper-js',
            plugin_dir_url(__FILE__) . 'league-wallpaper.js',
            ['jquery'],
            null,
            true
        );

        wp_localize_script('', 'league_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('create_league_nonce'),
        ]);

});


add_action('admin_menu', function(){

    // MAIN MENU
    add_menu_page(
        'Fantasy Manager',
        'Fantasy Manager',
        'manage_options',
        'fantasy_manager',
        'fantasy_manager_home_page',
        'dashicons-admin-generic',
        25
    );

    // SUBPAGE: POOLS
    add_submenu_page(
        'fantasy_manager',
        'Pools',
        'Pools',
        'manage_options',
        'fantasy_pools',
        'fantasy_pools_page'
    );

    // SUBPAGE: BOXES
    add_submenu_page(
        'fantasy_manager',
        'Boxes',
        'Pool Boxes',
        'manage_options',
        'fantasy_boxes',
        'fantasy_boxes_page'
    );

    // SUBPAGE: PLAYERS
    add_submenu_page(
        'fantasy_manager',
        'Players',
        'Pool Box Players',
        'manage_options',
        'fantasy_pool_players',
        'fantasy_pool_players_page'
    );
	
	 // SUBPAGE: PLAYERS
    add_submenu_page(
        'fantasy_manager',
        'Players',
        'Players',
        'manage_options',
        'fantasy_players',
        'fantasy_players_page'
    );
	
	// SUBPAGE: PLAYERS
    add_submenu_page(
        'fantasy_manager',
        'Player Stats Sync History',
        'Player Stats Sync History',
        'manage_options',
        'fantasy_manager_schedule_logs',
        'fantasy_manager_schedule_logs_page'
    );
	
	
});




add_action('init', function(){

    if( isset($_POST['join_league']) ){

        if( !is_user_logged_in() ) return;

        global $wpdb;

        $user_id = get_current_user_id();
        $league_id = get_the_ID();

        // get pool_id from league CPT
        $pool_id = get_post_meta($league_id, 'pool_id', true);

        // Check if already joined
        $entry = $wpdb->get_var($wpdb->prepare(
            "SELECT entry_id FROM {$wpdb->prefix}fantasy_entries
             WHERE user_id=%d AND league_id=%d",
             $user_id, $league_id
        ));

        if(!$entry){
            // Create entry
            $wpdb->insert(
                $wpdb->prefix.'fantasy_entries',
                [
                    'user_id'   => $user_id,
                    'league_id' => $league_id,
                    'pool_id'   => $pool_id,
                ]
            );

            $entry_id = $wpdb->insert_id;
        } else {
            $entry_id = $entry;
        }

        // Redirect to picks page
        wp_redirect(site_url('/make-picks/?entry_id='.$entry_id));
        exit;
    }

});
add_action('init', 'fm_save_pick');
function fm_save_pick() {
    if (!isset($_POST['make_pick'])) return;

    global $wpdb;

    $picks_table = $wpdb->prefix . 'fantasy_picks';
    $spots_table = $wpdb->prefix . 'fantasy_draft_spots';

    $entry_id = intval($_POST['entry_id']);
    $box_id = intval($_POST['box_id']);
    $player_id = intval($_POST['player_id']);
    $spot_id = intval($_POST['spot_id']);

    // Save pick
    $wpdb->insert($picks_table, [
        'entry_id' => $entry_id,
        'box_id' => $box_id,
        'spot_id' => $spot_id,
        'player_id' => $player_id,
        'created_at' => current_time('mysql')
    ]);

    // Mark spot as taken
    $wpdb->update(
        $spots_table,
        ['taken_by' => $entry_id],
        ['spot_id' => $spot_id]
    );

    wp_redirect($_SERVER['HTTP_REFERER']);
    exit;
}



function fm_get_user_score($entry_id){
    global $wpdb;
    $entries_table = $wpdb->prefix.'fantasy_entries';
    $picks_table   = $wpdb->prefix.'fantasy_picks';
    $stats_table   = $wpdb->prefix.'fantasy_player_stats';

    // Get all picks for this entry
    $picks = $wpdb->get_results("
        SELECT player_id
        FROM $picks_table
        WHERE entry_id = $entry_id
    ");

    $total = 0;
    foreach($picks as $p){
        $points = $wpdb->get_var("
            SELECT SUM(points) 
            FROM $stats_table
            WHERE player_id={$p->player_id}
        ");
        $total += intval($points);
    }
    return $total;
}

add_shortcode('fm_league_leaderboard', function($atts){
    global $wpdb;
   
    $league_id = intval(isset($_GET['league_id']) ? $_GET['league_id'] : 0 ) ;
    $entries_table = $wpdb->prefix.'fantasy_entries';

    $entries = $wpdb->get_results("SELECT * FROM $entries_table WHERE league_id=$league_id");

    // Calculate scores
    $results = [];
    foreach($entries as $e){
        $results[] = [
            'user_id'=>$e->user_id,
            'score'=>fm_get_user_score($e->entry_id)
        ];
    }

    // Sort descending
    usort($results, function($a,$b){ return $b['score'] - $a['score']; });

    ob_start();
    echo '<table class="widefat striped">';
    echo '<thead><tr><th>Rank</th><th>User</th><th>Points</th></tr></thead><tbody>';
    $rank=1;
    foreach($results as $r){
        $user = get_userdata($r['user_id']);
        echo '<tr>';
        echo '<td>'.$rank.'</td>';
        echo '<td>'.$user->display_name.'</td>';
        echo '<td>'.$r['score'].'</td>';
        echo '</tr>';
        $rank++;
    }
    echo '</tbody></table>';

    return ob_get_clean();
});




// /**
//  * Dispatch player sync based on league slug
//  *
//  * @param string $league
//  */
// function fantasy_sync_players_by_league( $league ) {
//     $league = strtolower(trim($league));
//     $sync_dir = get_template_directory() . '/sync/';

//     log_custom_error("Player sync initiated", 'schedule', "SYNC-{$league}-001", 'low', "Starting player sync for league: {$league}");

//     switch ( $league ) {

//         case 'nhl':
//             $file = $sync_dir . 'fetch_players_nhl.php';
//             if ( file_exists($file) ) {
//                 require_once $file;

//                 if ( function_exists('fantasy_fetch_players_nhl') ) {
//                     log_custom_error("Calling NHL player fetcher", 'schedule', "SYNC-NHL-002", 'low', "Executing fantasy_fetch_players_nhl()");
//                     fantasy_fetch_players_nhl();
//                 } else {
//                     log_custom_error("NHL fetch function missing", 'schedule', "SYNC-NHL-003", 'high', "Function fantasy_fetch_players_nhl() not found.");
//                 }
//             } else {
//                 log_custom_error("NHL sync file missing", 'schedule', "SYNC-NHL-004", 'high', "File not found: {$file}");
//             }
//             break;

//         case 'nfl':
//             $file = $sync_dir . 'fetch_players_nfl.php';
//             if ( file_exists($file) ) {
//                 require_once $file;

//                 if ( function_exists('fantasy_fetch_players_nfl') ) {
//                     log_custom_error("Calling NFL player fetcher", 'schedule', "SYNC-NFL-002", 'low', "Executing fantasy_fetch_players_nfl()");
//                     fantasy_fetch_players_nfl();
//                 } else {
//                     log_custom_error("NFL fetch function missing", 'schedule', "SYNC-NFL-003", 'high', "Function fantasy_fetch_players_nfl() not found.");
//                 }
//             } else {
//                 log_custom_error("NFL sync file missing", 'schedule', "SYNC-NFL-004", 'high', "File not found: {$file}");
//             }
//             break;

//         // case 'nba':
//         //     $file = $sync_dir . 'fetch_players_nba.php';
//         //     if ( file_exists($file) && function_exists('fantasy_fetch_players_nba') ) {
//         //         fantasy_fetch_players_nba();
//         //     }
//         //     break;

//         default:
//             log_custom_error("Unsupported league", 'schedule', "SYNC-UNKNOWN-001", 'medium', "League slug '{$league}' is not supported.");
//             break;
//     }

//     log_custom_error("Player sync completed for league", 'schedule', "SYNC-{$league}-005", 'low', "Finished player sync for league: {$league}");
// }


// /**
//  * Schedule daily sync if not already scheduled
//  */
// if ( ! wp_next_scheduled( 'fantasy_sync_all_leagues_daily' ) ) {
//     wp_schedule_event( time(), 'daily', 'fantasy_sync_all_leagues_daily' );
//     log_custom_error("Scheduled daily sync", 'schedule', "SYNC-SCHED-001", 'low', "Scheduled 'fantasy_sync_all_leagues_daily' event.");
// }

// add_action( 'fantasy_sync_all_leagues_daily', 'fantasy_sync_all_leagues' );


// /**
//  * Sync all published sports/leagues
//  */
// function fantasy_sync_all_leagues() {
//     log_custom_error("Starting full league sync", 'schedule', "SYNC-ALL-001", 'low', "Fetching all published sports/leagues");
// // 	fantasy_remove_duplicate_players_keep_oldest('fantasy_players');

//     $sports = get_posts([
//         'post_type'      => 'sport',
//         'post_status'    => 'publish',
//         'posts_per_page' => -1,
//     ]);

//     if ( empty($sports) ) {
//         log_custom_error("No sports found", 'schedule', "SYNC-ALL-002", 'medium', "No published sports found to sync");
//         return;
//     }

//     foreach ($sports as $sport) {
//         $league = $sport->post_name;
		
		
// // 	    fantasy_remove_duplicate_player_stats_keep_oldest('fantasy_player_stats_'.$league);

//         if (!$league) {
//             log_custom_error("Sport post missing slug", 'schedule', "SYNC-ALL-003", 'medium', "Sport post ID {$sport->ID} missing post_name");
//             continue;
//         }

//         log_custom_error("Dispatching sync for league", 'schedule', "SYNC-ALL-004", 'low', "Dispatching fantasy_sync_players_by_league for '{$league}'");
//         fantasy_sync_players_by_league($league);
//     }

//     log_custom_error("Full league sync completed", 'schedule', "SYNC-ALL-005", 'low', "Completed syncing all leagues");
// }








//     if ( ! wp_next_scheduled( 'fantasy_sync_players_daily' ) ) {
//         wp_schedule_event( time(), 'daily', 'fantasy_sync_players_daily' );
//     }
// add_action( 'fantasy_sync_players_daily', 'fantasy_sync_players_stats' );

// function fantasy_sync_players_stats() {
//     global $wpdb;

//     $players_table = $wpdb->prefix . 'fantasy_players';
//     $stats_table   = $wpdb->prefix . 'fantasy_player_stats';

//     $username = '8d3ac286-6e8d-4259-994d-c2e50e';
//     $password = 'MYSPORTSFEEDS';

//     $season  = "2025-2026";
//     $api_url = "https://api.mysportsfeeds.com/v2.1/pull/nhl/{$season}-regular/player_stats_totals.json";

//     // 1. API Request
//     $response = wp_remote_get($api_url, [
//         'headers' => [
//             'Authorization' => 'Basic ' . base64_encode("$username:$password")
//         ]
//     ]);

//     if (is_wp_error($response)) {
//         error_log("MSF API error: " . $response->get_error_message());
//         return;
//     }

//     $data = json_decode(wp_remote_retrieve_body($response), true);

//     if (empty($data['playerStatsTotals'])) {
//         error_log("No stats found in MSF response");
//         return;
//     }

//     // 2. Loop players
//     foreach ($data['playerStatsTotals'] as $row) {

//         $player = $row['player'];
//         $team   = $row['team']['abbreviation'] ?? null;
//         $stats  = $row['stats'];

//         $player_api_id = intval($player['id']);
//         $player_name   = trim(($player['firstName'] ?? '') . ' ' . ($player['lastName'] ?? ''));

//         /*
//          * SAVE / UPDATE PLAYERS TABLE
//          */
//         $wpdb->replace($players_table, [
//             'player_api_id' => $player_api_id,
//             'first_name'    => $player['firstName'] ?? null,
//             'last_name'     => $player['lastName'] ?? null,
//             'player_name'   => $player_name,
//             'jersey_number' => $player['jerseyNumber'] ?? null,
//             'team'          => $team,
//             'position'      => $player['primaryPosition'] ?? null,
//             'height'        => $player['height'] ?? null,
//             'weight'        => $player['weight'] ?? null,
//             'birth_date'    => $player['birthDate'] ?? null,
//             'age'           => $player['age'] ?? null,
//             'rookie'        => $player['rookie'] ?? 0,
//             'handedness'    => $player['handedness']['shoots'] ?? null,
//             'image_url'     => $player['officialImageSrc'] ?? null,
//             'updated_at'    => current_time('mysql')
//         ]);

//         /*
//          * Extract Stats
//          */
//         $games      = $stats['gamesPlayed'] ?? 0;
//         $goals      = $stats['scoring']['goals'] ?? 0;
//         $assists    = $stats['scoring']['assists'] ?? 0;
//         $points     = $stats['scoring']['points'] ?? 0;
//         $pp_goals   = $stats['scoring']['powerplayGoals'] ?? 0;
//         $sh_goals   = $stats['scoring']['shorthandedGoals'] ?? 0;
//         $gw_goals   = $stats['scoring']['gameWinningGoals'] ?? 0;

//         $shots      = $stats['skating']['shots'] ?? 0;
//         $hits       = $stats['skating']['hits'] ?? 0;
//         $takeaways  = $stats['skating']['takeaways'] ?? 0;
//         $plus_minus = $stats['skating']['plusMinus'] ?? 0;

//         $faceoff_pct = $stats['skating']['faceoffPercent'] ?? 0;
//         $shot_pct    = $stats['skating']['shotPercentage'] ?? 0;

//         $penalty_min = $stats['penalties']['penaltyMinutes'] ?? 0;
//         $fights      = $stats['penalties']['fights'] ?? 0;

//         $toi = $stats['shifts']['timeOnIceSeconds'] ?? 0;

//         /*
//          * FANTASY SCORE
//          */
//         $fantasy_score =
//             ($goals * 3) +
//             ($assists * 2) +
//             ($points * 1) +
//             ($shots * 0.4) +
//             ($hits * 0.4) +
//             ($takeaways * 0.6) +
//             ($pp_goals * 1.5) +
//             ($sh_goals * 2) +
//             ($gw_goals * 1.5) +
//             ($plus_minus * 0.5) -
//             ($penalty_min * 0.1) +
//             ($fights * 1.5);

//         /*
//          * SAVE / UPDATE STATS TABLE
//          */
//         $wpdb->replace($stats_table, [
//             'player_id'           => $player_api_id,
//             'season'              => $season,
//             'games_played'        => $games,
//             'goals'               => $goals,
//             'assists'             => $assists,
//             'points'              => $points,
//             'powerplay_goals'     => $pp_goals,
//             'shorthanded_goals'   => $sh_goals,
//             'game_winning_goals'  => $gw_goals,
//             'plus_minus'          => $plus_minus,
//             'shots'               => $shots,
//             'shot_percentage'     => $shot_pct,
//             'hits'                => $hits,
//             'takeaways'           => $takeaways,
//             'faceoff_percent'     => $faceoff_pct,
//             'penalty_minutes'     => $penalty_min,
//             'fights'              => $fights,
//             'time_on_ice_seconds' => $toi,
//             'fantasy_score'       => round($fantasy_score, 2),
//             'updated_at'          => current_time('mysql')
//         ]);
//     }

//     /*
//      * GLOBAL RANKING
//      */
//     $players = $wpdb->get_results(
//         $wpdb->prepare("SELECT id, fantasy_score FROM $stats_table WHERE season = %s ORDER BY fantasy_score DESC", $season),
//         ARRAY_A
//     );

//     $rank = 1;
//     foreach ($players as $p) {
//         $wpdb->update($stats_table, ['rank_global' => $rank], ['id' => $p['id']]);
//         $rank++;
//     }

//     error_log("Fantasy players + stats sync completed via single API.");
// }



add_shortcode('league_leaderboard', 'fantasy_leaderboard_shortcode_by_picks');
function fantasy_leaderboard_shortcode_by_picks($atts) {
    global $wpdb;

    $atts = shortcode_atts([
        'per_page' => 10
    ], $atts);

    // get league slug from URL (query var name you use)
    $league_slug = get_query_var('league_id');
// 	echo $league_slug;
    if (!$league_slug) return '<p>Invalid league URL.</p>';

    // fetch league post (CPT = 'league')
    $league = get_page_by_path($league_slug, OBJECT, 'league');
    if (!$league) return '<p>League not found.</p>';
// 	echo "<pre>";
// 	print_r(get_post_meta($league->ID));
	$sport = get_post_meta($league->ID, 'game_type', true);
// 	echo "</pre>";
	

    $league_id = intval($league->ID);
    $current_user = get_current_user_id();

    // table names with prefix
    $entries_table = $wpdb->prefix . 'fantasy_entries';
    $picks_table   = $wpdb->prefix . 'fantasy_picks';
    $stats_table   = $wpdb->prefix . 'fantasy_player_stats_'.$sport;

    // pagination
    $page = isset($_GET['lb_page']) ? max(1, intval($_GET['lb_page'])) : 1;
    $per_page = intval($atts['per_page']);
    $offset = ($page - 1) * $per_page;

    // 1. Get all entries for this league
    $entries = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $entries_table WHERE league_id = %s ORDER BY entry_id ASC",
            $league_slug
        )
    );

    if (empty($entries)) return '<p style="text-align:center;">No participants yet.</p>';

    $results = [];
// print_r($entries); 
    // 2. For each entry, get picks and sum player stats
    foreach ($entries as $entry) {
        $entry_id = intval($entry->entry_id);
        $user_id  = intval($entry->user_id);
 
        // get picks of this entry
        $player_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT player_id FROM $picks_table WHERE entry_id = %d",
                $entry_id
            )
        );
		

        // sum fantasy scores
        if (!empty($player_ids)) {
            $placeholders = implode(',', array_fill(0, count($player_ids), '%d'));
            $scores = $wpdb->get_col(
                $wpdb->prepare(
                    "SELECT fantasy_score FROM $stats_table WHERE player_id IN ($placeholders)",
                    ...$player_ids
                )
            );
            $total_score = array_sum($scores);
        } else {
            $total_score = 0;
        }

        $results[] = [
            'entry_id' => $entry_id,
            'user_id' => $user_id,
            'total_score' => $total_score,
        ];
    }

    // 3. Sort by total_score descending
    usort($results, function($a, $b) {
        return $b['total_score'] <=> $a['total_score'];
    });

    // 4. Paginate results
    $total_entries = count($results);
    $rows = array_slice($results, $offset, $per_page);

    // build rank map: entry_id => rank
    $rank_map = [];
    $rank = 1;
    foreach ($results as $r) {
        $rank_map[$r['entry_id']] = $rank++;
    }

    // output (same design)
    ob_start();
    ?>
    <style>
    .fc-lb-wrap { max-width:720px; margin:40px auto; font-family: Inter, sans-serif; color:#fff; }
    .fc-lb-title { text-align:center; font-size:48px; margin-bottom:30px; font-weight:700; }
    .fc-lb-item {
        display:flex; align-items:center; justify-content:space-between;
        background:#2f2f2f; padding:14px 18px; border-radius:12px; margin-bottom:14px;
        box-shadow:0 6px 16px rgba(0,0,0,0.45);
    }
    .fc-lb-item.you { border:2px solid #ff8a00; }
    .fc-lb-left { display:flex; align-items:center; gap:12px; }
    .fc-lb-avatar { width:48px; height:48px; border-radius:50%; object-fit:cover; }
    .fc-lb-name { font-weight:600; font-size:16px; }
    .fc-lb-badges { display:flex; gap:12px; align-items:center; }
    .fc-lb-rank, .fc-lb-score { background:#494949; padding:8px 18px; border-radius:24px; font-weight:600; }
    .fc-lb-load { text-align:center; margin-top:26px; }
    .fc-lb-load a { background:#ff8a00; color:#000; padding:12px 26px; border-radius:26px; text-decoration:none; font-weight:700; }
    </style>

    <div class="fc-lb-wrap">
        <?php foreach ($rows as $r) :
            $entry_id = intval($r['entry_id']);
            $user_id = intval($r['user_id']);
            $score = floatval($r['total_score']);
            $is_you = ($current_user && $current_user == $user_id);

            $user = get_userdata($user_id);
            $display_name = $user ? ($user->display_name ?: $user->user_login) : 'User';
            $avatar = $user ? get_avatar_url($user_id, ['size' => 96]) : '';
            $rank_num = $rank_map[$entry_id] ?? '-';

            // suffix for ordinal
            $suffix = 'th';
            if ($rank_num % 10 == 1 && $rank_num % 100 != 11) $suffix = 'st';
            elseif ($rank_num % 10 == 2 && $rank_num % 100 != 12) $suffix = 'nd';
            elseif ($rank_num % 10 == 3 && $rank_num % 100 != 13) $suffix = 'rd';
        ?>
            <div class="fc-lb-item <?php echo $is_you ? 'you' : ''; ?>">
                <div class="fc-lb-left">
                    <img class="fc-lb-avatar" src="<?php echo esc_url($avatar); ?>" alt="">
                    <div>
                        <div class="fc-lb-name"><?php echo $is_you ? 'You' : '@' . esc_html($display_name); ?></div>
                    </div>
                </div>

                <div class="fc-lb-badges">
                    <div class="fc-lb-rank"><?php echo esc_html($rank_num . $suffix); ?></div>
                    <div class="fc-lb-score"><?php echo number_format_i18n($score, 0); ?> PT</div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($total_entries > ($offset + $per_page)) : ?>
            <div class="fc-lb-load">
                <?php
                $next_page_link = add_query_arg('lb_page', $page + 1);
                ?>
                <a href="<?php echo esc_url($next_page_link); ?>">Load More</a>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

add_shortcode('fc_process_draft', 'fc_process_draft');
function fc_process_draft() {
    global $wpdb;

    if (!session_id()) session_start();

    // No session? No draft
    if (empty($_SESSION['fc_draft'])) {
        return "<p>No draft found.</p>";
    }

    $draft = $_SESSION['fc_draft'];
    $league_slug = sanitize_text_field($draft['league_id']);
    $players = $draft['players'];

    if (empty($league_slug) || empty($players)) {
        return "<p>Invalid draft.</p>";
    }

    // GET LEAGUE POST ID BY SLUG
    $league_post = get_page_by_path($league_slug, OBJECT, 'league');

    if (!$league_post) {
        return "<p>League not found.</p>";
    }

    $league_id = $league_post->ID;

    // ------------------------------------
    // CREATE ENTRY IN entries TABLE
    // ------------------------------------
    $user_id = get_current_user_id();
    if (!$user_id) {
        return "<p>User not logged in.</p>";
    }

    $entries_table = $wpdb->prefix . "fantasy_entries";
    $wpdb->insert($entries_table, [
        'league_id'  => $league_slug,
        'user_id'    => $user_id,
		'pool_id' => 0,
        'created_at' => current_time('mysql')
    ]);

    $entry_id = $wpdb->insert_id;

    // ------------------------------------
    // INSERT PICKS IN picks TABLE
    // ------------------------------------
    $picks_table = $wpdb->prefix . "fantasy_picks";

    foreach ($players as $p) {
        $wpdb->insert($picks_table, [
            'entry_id'   => $entry_id,
            'box_id'     => intval($p['box_id']),
            'spot_id'    => 0, // not provided — default NULL
            'player_id'  => intval($p['player_id']),
            'created_at' => current_time('mysql')
        ]);
    }

    // Clear draft session now
    unset($_SESSION['fc_draft']);

    // REDIRECT AFTER EVERYTHING IS SAVED
    $redirect = site_url('/league/' . $league_slug);

    echo "<script>window.location.href='$redirect';</script>";
    exit;
}




add_shortcode('view_league_leaderboard_button', 'fc_jview_league_leaderboard_button_shortcode');
function fc_jview_league_leaderboard_button_shortcode() {
    global $post, $wpdb;

    if (!$post) return "";

    $league = $post;
  
	
	 $league_slug = $league->post_name;
?>
 

<!-- Join button -->
<a href="<?php echo home_url('/league/' . $league_slug . '/leaderboard/'); ?>"
   style="
		  background: linear-gradient(90deg, #ff9f32, #ff7700);
		  color:#fff;
		  padding:18px 50px;
		  border-radius:40px;
		  font-size:30px;
		  font-weight:600;
		  text-decoration:none;
		  display:inline-block;
		  ">
	Leaderboard
</a>



    <?php
    
}
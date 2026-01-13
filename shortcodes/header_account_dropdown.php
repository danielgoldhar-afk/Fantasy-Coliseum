<?php

// You can place this inside header.php or output via a shortcode

function custom_user_dropdown() {
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $logout_url = wp_logout_url( home_url() );
        $profile_url = site_url('/dashboard/profile'); // or use get_edit_profile_url($user_id)
        ?>
        <div class="user-dropdown-container">
            <div class="user-avatar" id="userDropdownToggle">
                <img src="<?php echo esc_url( get_avatar_url( $current_user->ID, ['size' => 64] ) ); ?>" alt="User Avatar">
            </div>

            <div class="user-dropdown-menu" id="userDropdownMenu">
                <p class="user-name"><?php echo esc_html( $current_user->display_name ); ?></p>
				
				<a href="<?php echo esc_url(  site_url('/dashboard') ); ?>"> Dashboard</a>
                <a href="<?php echo esc_url( site_url('/my-leagues') ); ?>">My Leagues</a>
				<a href="<?php echo esc_url( site_url('/news/nba/scores') ); ?>">Press box</a>
				<a href="<?php echo esc_url( $profile_url ); ?>"> Profile</a>
                <a href="<?php echo esc_url( $logout_url ); ?>">Log Out</a>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function(){
            const toggle = document.getElementById('userDropdownToggle');
            const menu = document.getElementById('userDropdownMenu');
            toggle.addEventListener('click', () => {
                menu.classList.toggle('show');
            });
            document.addEventListener('click', (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });
        });
        </script>

        <style>
        .user-dropdown-container {
            position: relative;
            display: inline-block;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 10px 0;
            min-width: 160px;
            text-align: left;
            z-index: 9999;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .user-dropdown-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .user-dropdown-menu p.user-name {
            margin: 0;
            padding: 10px 15px;
            color: #fff;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .user-dropdown-menu a {
            display: block;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            transition: background 0.2s;
        }

        .user-dropdown-menu a:hover {
            background: rgba(255,255,255,0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        </style>
        <?php
    } 
}
add_shortcode('header_account_dropdown', 'custom_user_dropdown');
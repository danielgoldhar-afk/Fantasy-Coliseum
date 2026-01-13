<?php


add_shortcode('filter_pools_shortcode', function () {

    $selected_league = isset($_GET['league']) ? sanitize_text_field($_GET['league']) : '';

    ob_start();
    ?>
    
    <style>
        /* ===== Filter Button ===== */
        .pool-filter-trigger {
            font-family: "Aeonik Trial", Sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            padding: 22px 56px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            background: linear-gradient(180deg, #FFA13C 0%, #FF8C1A 100%);
            box-shadow:
                0px 10px 30px rgba(0,0,0,0.35),
                inset 0px 3px 6px rgba(255,255,255,0.25);
            transition: all 0.25s ease;
        }

        .pool-filter-trigger:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        /* ===== Modal Overlay ===== */
        .pool-filter-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.75);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* ===== Modal Box ===== */
        .pool-filter-modal {
            background: rgba(0,0,0,0.85);
            border-radius: 28px;
            padding: 48px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0px 30px 80px rgba(0,0,0,0.6);
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .pool-filter-modal h3 {
            font-family: "Heroking", Sans-serif;
            font-size: 28px;
            font-weight: 400;
            margin: 0;
            text-align: center;
        }

        /* ===== Dropdown ===== */
        .pool-filter-modal select {
            width: 100%;
            padding: 18px 22px;
            border-radius: 18px;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(0,0,0,0.7);
            color: #fff;
            font-size: 18px;
            appearance: none;
            outline: none;
        }

        .pool-filter-modal select option {
            background: #000;
            color: #fff;
        }

        /* ===== Buttons ===== */
        .filter-actions {
            display: flex;
            gap: 14px;
        }

        .filter-apply {
            flex: 1;
            font-size: 18px;
            padding: 18px 0;
            border-radius: 999px;
            border: none;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(180deg, #FFA13C 0%, #FF8C1A 100%);
            box-shadow:
                0px 8px 25px rgba(0,0,0,0.35),
                inset 0px 3px 6px rgba(255,255,255,0.25);
        }

        .filter-cancel {
            flex: 1;
            font-size: 18px;
            padding: 18px 0;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.25);
            background: transparent;
            color: #fff;
            cursor: pointer;
        }

        .filter-cancel:hover {
            background: rgba(255,255,255,0.08);
        }
    </style>
    
    <!-- Trigger -->
    <button class="pool-filter-trigger" id="openPoolFilter">
        Filter
    </button>

    <!-- Modal -->
    <div class="pool-filter-overlay" id="poolFilterOverlay">
        <form action="/pools" class="pool-filter-modal" method="get">
            <h3>Filter Pools</h3>

            <select name="type">
                <option value="">All </option>
                <?php
                $sports = get_posts([
                    'post_type'      => 'sport',
                    'posts_per_page' => -1,
                    'orderby'        => 'title',
                    'order'          => 'ASC'
                ]);

                foreach ($sports as $sport):
                    ?>
                    <option value="<?php echo esc_attr($sport->post_name); ?>"
                        <?php selected($selected_game_type, $sport->post_name); ?>>
                        <?php echo esc_html($sport->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="filter-actions">
                <button type="submit" class="filter-apply">Apply</button>
                <button type="button" class="filter-cancel" id="closePoolFilter">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <script>
        const openBtn = document.getElementById('openPoolFilter');
        const closeBtn = document.getElementById('closePoolFilter');
        const overlay = document.getElementById('poolFilterOverlay');

        openBtn.addEventListener('click', () => overlay.style.display = 'flex');
        closeBtn.addEventListener('click', () => overlay.style.display = 'none');
        overlay.addEventListener('click', e => {
            if (e.target === overlay) overlay.style.display = 'none';
        });
    </script>
    <?php

    return ob_get_clean();
});
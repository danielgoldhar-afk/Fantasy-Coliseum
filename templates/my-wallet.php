<?php
/**
 * Template Name: My Wallet
 * Template Post Type: page
 */

?>
<link rel="stylesheet"
      href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>My Wallet</title>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/dashboard.css">


<div class="fc-dashboard-page">
  <div class="fc-dashboard-page-overlay"></div>
  <div class="fc-dashboard-page-inner">
    <div class="fc-dashboard-page-inner-page">
      
    <!-- ✅ SIDEBAR START -->
    <div id="fc-dashboard-sidebar" class="fc-dashboard-sidenav">
      <?php include get_template_directory() . '/template-parts/league-sidebar.php'; ?>
    </div>
    <!-- ✅ SIDEBAR END -->
        <!-- ✅ CONTENT -->
		<div id="fc-dashboard-contentarea" class="fc-dashboard-content">
			<h1 class="fc-page-title">
    		    Wallet
    		</h1>
    	
    		<span class="fc-page-subtitle">Account</span>
			<br>
			
			<?php
			
			global $wpdb;
            $user_id = get_current_user_id();
        
            $table = $wpdb->prefix . 'fantasy_wallet_transactions';
        
            $balance = fantasy_get_wallet_balance($user_id);
        
            $transactions = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table 
                     WHERE user_id = %d 
                     ORDER BY id DESC 
                     LIMIT 50",
                    $user_id
                )
            );
        
            ob_start();
            ?>
            <style>
               .fw-wrap {
    width: 100%;
}

/* TOP BOXES */
.fw-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.fw-box {
   background: #ffa2431a;
    color: #fff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.fw-box span {
    font-size: 14px;
    opacity: 0.8;
}

.fw-box h2 {
    margin-top: 8px;
    font-size: 26px;
    font-weight: 600;
}

/* TABLE */
.fw-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius:10px;
}

.fw-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffa2431a;
    color: #e5e7eb;
     border-radius:10px;
}

.fw-table th,
.fw-table td {
    padding: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    text-align: left;
}

.fw-table th {
     background: #ffa2431a;
    font-weight: 600;
}

/* CREDIT / DEBIT COLORS */
.fw-credit {
    color: #fff;
}

.fw-debit {
    color: #fff;
}

.fw-empty {
    text-align: center;
    padding: 20px;
    opacity: 0.7;
}

.fw-wrap h1{
    font-weight: 400;
    font-family: 'aeoniktrial-light' !important;
    color: #fff;
    -webkit-text-stroke: 1px;
    letter-spacing: 1px;
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .fw-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .fw-stats {
        grid-template-columns: 1fr;
    }
}

            </style>
        
           <div class="fw-wrap">
               <h1>Recent Transactions</h1>

    <!-- TOP 4 BOXES -->
    <div class="fw-stats">
        
        <div class="fw-box">
            <span>Wallet Balance</span>
            <h2>$<?php echo number_format_i18n($balance, 2); ?></h2>
        </div>

        <div class="fw-box">
            <span>Total Credit</span>
            <h2>$<?php echo number_format_i18n($total_credit, 2); ?></h2>
        </div>

        <div class="fw-box">
            <span>Total Debit</span>
            <h2>$<?php echo number_format_i18n($total_debit, 2); ?></h2>
        </div>

        <div class="fw-box">
            <span>Transactions</span>
            <h2><?php echo count($transactions); ?></h2>
        </div>
    </div>

    <!-- TABLE -->
    <div class="fw-table-wrap">
        <table class="fw-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($transactions): ?>
                    <?php foreach ($transactions as $tx): ?>
                        <tr>
                            <td><?php echo esc_html(date('M d, Y', strtotime($tx->created_at))); ?></td>
                            <td><?php echo esc_html($tx->description ?: '-'); ?></td>
                            <td class="<?php echo $tx->type === 'credit' ? 'fw-credit' : 'fw-debit'; ?>">
                                <?php echo ucfirst($tx->type); ?>
                            </td>
                            <td class="<?php echo $tx->type === 'credit' ? 'fw-credit' : 'fw-debit'; ?>">
                                <?php echo ($tx->type === 'credit' ? '+' : '-') . '$' . number_format_i18n($tx->amount, 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="fw-empty">No wallet transactions yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

			
			
			
        </div>
    </div>
  </div>
</div>

<!-- ✅ JQUERY -->
<!-- ✅ JQUERY -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/dashboard.js"></script>
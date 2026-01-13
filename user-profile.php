<?php
/**
 * Template Name: Profile
 * Template Post Type: page
 */

?>  

 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

   

    h1 {
      font-size: 30px;
      font-weight: 700;
      margin-bottom: 5px;
      width: 100%;
      max-width: 800px;
    }

    h2 {
      font-size: 16px;
      color: #9ca3af;
      font-weight: 500;
      margin: 0 0 25px;
      width: 100%;
      max-width: 800px;
    }

     h6{
      margin: 3px 0px 10px 0px;
    }

    .container {
      width: 100%;
      max-width: 800px;
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    /* Section Base */
   .section {
    width: 760px;
    background: #0000004d;
    border: 1px solid #4e4e4e4d;
    border-radius: 17px;
    padding: 15px;
    position: relative;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    background: rgb(0 0 0 / 27%);
    border: 0.3px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0px 6px 19.3px 0px rgba(0,0,0,0.24);
}

    /* Floating title (like the image top-left text) */
    .section::before {
      content: attr(data-title);
      position: absolute;
      top: 7px;
      left: 32px;
      background: #191c1f;
      padding: 0 8px;
      font-size: 13px;
      color: #9ca3af;
    }

    /* Avatar section */
  .avatar-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    border: 1px solid #2b3240;
    border-radius: 8px;
    left: 10px;
}

    .avatar-icon {
      font-size: 60px;
      background: #202633;
      border-radius: 50%;
      width: 90px;
      height: 90px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 15px;

    }

    .upload-btn {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      box-shadow: 0px 11px 4px 0px rgba(0, 0, 0, 0.25);
      box-shadow: 0px 4px 6.5px 0px rgba(255, 255, 255, 0.25) inset;
      color: #fff;
      border-radius: 24px;
      border: none;
      padding: 8px 20px;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
      margin-right: 15px;
    }

    /* Form and Inputs */
    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 16px;
    }

    .form-group {
      flex: 1;
      min-width: 240px;
      display: flex;
      flex-direction: column;
      position: relative;
    }

    label {
      font-size: 14px;
      color: #fff;
      margin-bottom: 6px;
    }

    input, select {
		background: #00000014;
		border: 1px solid #2b3240;
		color: #fff;
		border-radius: 8px;
		padding: 12px 40px 12px 12px;
		font-size: 15px;
		outline: none;
/* 		transition: border-color 0.3s; */
	}

    input:focus, select:focus {
      border-color: #FF9500;
		
    }
	input[readonly], input[disabled] {
		background: #363636;
	}
	input[disabled]:hover {
border: 1px solid #2b3240;
	}
    input:hover,
select:hover,
input:focus,
select:focus {
  border-color: #FF9500;
  outline: none;
}

    input:disabled {
      opacity: 0.7;
    }

    /* Edit icon */
     .edit-icon {
    position: absolute;
    right: 12px;
    top: 12px;
    width: 18px;
    height: 18px;
    fill: #fff;
    cursor: pointer;
    transition: fill 0.3s;
  }

  .edit-icon:hover {
    fill: #fff;
  }


  .calendar-icon {
    position: absolute;
    right: 12px;
    top: 36px;
    width: 18px;
    height: 18px;
    fill: #9ca3af;
    pointer-events: none;
  }


  input:disabled {
    opacity: 0.7;
  }
    /* Buttons */
    .save-btn {
      background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      box-shadow: 0px 11px 4px 0px rgba(0, 0, 0, 0.25);
      box-shadow: 0px 4px 6.5px 0px rgba(255, 255, 255, 0.25) inset;
      color: #fff;
      border-radius: 24px;
      border: none;
      padding: 8px 16px;
  font-size: 14px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: 0.3s;
  white-space: nowrap;
    }

  
    .add-btn {
   background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      box-shadow: 0px 11px 4px 0px rgba(0, 0, 0, 0.25);
      box-shadow: 0px 4px 6.5px 0px rgba(255, 255, 255, 0.25) inset;
      color: #fff;
  border: none;
  padding: 8px 15px;
  border-radius: 24px;
  cursor: pointer;
  font-size: 14px;
  display: inline-flex; /* use inline-flex instead of flex */
  align-items: center;
  gap: 6px;
  transition: 0.3s;
  white-space: nowrap; /* prevents wrapping under icon */
}



    .address-box {
  border: 1px solid #2b3240;
  border-radius: 8px;
  background: #191c1f;
  padding: 15px 20px;
  margin-top: 15px;
}

    .email-options {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }


.toggle {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border: 1px solid #2b3240;
  border-radius: 8px;
}

.switch {
  position: relative;
  display: inline-block;
  width: 52px;
  height: 30px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #374151;
  transition: 0.4s;
  border-radius: 34px;
}

/* Toggle knob */
.slider:before {
  position: absolute;
  content: "";
  height: 22px;
  width: 22px;
  left: 3px;
  bottom: 3px;
  background-color: #0b111a;
  border: 2px solid #FF9500;
  border-radius: 50%;
  transition: 0.4s;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  color: #3b82f6;
}


/* Active (checked) state */
input:checked + .slider {
  background-color: #FF9500;
}

/* Move knob right and show tick */
input:checked + .slider:before {
  transform: translateX(24px);
  content: "✔";
  color: white;
  background-color: black;
  border-color:  white;
}

/* Optional subtle glow when active */
input:checked + .slider {
  box-shadow: 0 0 7px #FF9500;
}

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal-content {
      display: flex;
      flex-direction: column;
      gap: 12px;
      background: #191c1f;
      border: 1px solid #393c3f;
      border-radius: 17px;
      padding: 10px;
      width: 100%;
      max-width: 320px;
      box-shadow: 0 0 10px rgba(0,0,0,0.4);
      position: relative;
    }

    .modal h4 {
      margin: 0px 0px 2px 0px;
      font-size: 18px;
      font-weight: 700;
    }

   .close-btn {
  position: absolute;
  right: 5px;
  top: 11px;
  background: transparent;
  border: none;
  width: 40px;
  height: 23px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}



  .Sub-btn{
        display: flex;
    flex-direction: row-reverse;
  }
    .submit-btn {
         background: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
      box-shadow: 0px 11px 4px 0px rgba(0, 0, 0, 0.25);
      box-shadow: 0px 4px 6.5px 0px rgba(255, 255, 255, 0.25) inset;
      color: #fff;
      border: none;
      width: auto;
      padding: 10px 20px;
      border-radius: 28px;
      cursor: pointer;
      font-size: 15px;
  
    }
  


    @media (max-width: 600px) {
      body {
        padding: 20px;
      }
      .save-btn {
        width: 100%;
        float: none;
      }
    }


   

    /* Floating label inside input border for Username, Secret ID, Email */
.input-label-top {
  position: relative;
}

.input-label-top label {
  position: absolute;
  top: -8px;
  left: 8px;
  background: #191c1f;
  padding: 0 6px;
  font-size: 10px;
  color: #9ca3af;
  z-index: 2;
}



/* Time zone title above select box */
.timezone-label-top {
  position: relative;
}

.timezone-label-top label {
  position: absolute;
  top: -8px;
  left: 14px;
  background: #191c1f;
  padding: 0 6px;
  font-size: 13px;
  color: #9ca3af;
}

.modal-content .input-label-top label {
  background: #191c1f; /* same as modal background */
  color: #9ca3af;
}



    /* Remove native calendar icon */
input[type="date"]::-webkit-calendar-picker-indicator {
  opacity: 0;
  position: absolute;
  right: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}


/* Eye icon styling */
.password-field {
  position: relative;
}

.eye-icon {
  position: absolute;
  right: 12px;
  top: 12px;
  width: 20px;
  height: 20px;
  cursor: pointer;
  transition: fill 0.3s;
}

.eye-icon:hover {
  fill: #ffffff;
}



.info-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.info-header h1 {
  font-size: 22px;
  margin: 0;
	color: #fff
}




/* Add Address Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  justify-content: center;
  align-items: center;
}


.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h2 {
  font-size: 20px;
  margin: 0;
}

.close {
  cursor: pointer;
  font-size: 22px;
  color: #999;
  transition: 0.2s;
}
.close:hover {
  color: #fff;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 12px;
}

.input-group input,
.input-group select {
   background: #191c1f;
      border: 1px solid #2b3240;
      color: #fff;
      border-radius: 8px;
      padding: 12px 40px 12px 12px;
      font-size: 15px;
      outline: none;
      transition: border-color 0.3s;
}

.input-group svg {
  color: #49a3fd;
}

.input-row {
  display: flex;
  flex-direction: column;
}

.input-row .input-group {
  flex: 1;
}

.input-group input::placeholder {
  color: #bbb;
}

.input-group label {
  font-size: 13px;
  color: #bbb;
}

.manual-btn {
    background: #2b2e31;
    color: #e4e4e4;
    border: none;
    border-radius: 20px;
    padding: 8px 4px;
    width: 61%;
    cursor: pointer;
    transition: 0.3s;
}




.input-group {
  position: relative;
}

#searchView .input-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

#searchView input {
   background: #191c1f;
      border: 1px solid #2b3240;
      color: #fff;
      border-radius: 8px;
      padding: 12px 40px 12px 12px;
      font-size: 15px;
      outline: none;
      transition: border-color 0.3s;
      width: 83%;

}

  </style>  


<link rel="stylesheet"
      href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

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
		<style>
			.my-leagues-wrapper {
				background-color: #FFFFFF14;
				border: 0.3px solid rgba(255,255,255,0.15);
				padding: 10px;
				border-radius: 20px;
				text-decoration: none;
				display: grid;
				grid-template-columns: 1fr 1fr;
				margin-top: 30px;
			}
			.league-item {
				background: #00000047;
				padding: 20px;
				border-radius: 20px;
			}
			.league-item h3{
				color: #fff	;
				margin-top: 0
			}
		</style>
        <!-- ✅ CONTENT -->
    	<div id="fc-dashboard-contentarea" class="fc-dashboard-content">
    		<h1 class="fc-page-title">
    		    Profile
    		</h1>

   

			<div class="container">
				<!-- Avatar -->
				<div class="section" data-title="Avatar">
					<div class="avatar-content">
						<div class="avatar-icon">👤</div>
						<button class="upload-btn">Upload</button>
					</div>
				</div>
<?php 
				
				$user_id = get_current_user_id();
    $user = get_userdata($user_id);

    // === PROCESS FORM SUBMISSION ===
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fc_profile_nonce'])) {

        if (!wp_verify_nonce($_POST['fc_profile_nonce'], 'fc_update_profile')) {
            return "<p>Security check failed.</p>";
        }

        $updated_data = [];

        // Update phone (stored in user meta)
        if (isset($_POST['phone'])) {
            update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
        }

        // Update first & last name
        if (isset($_POST['first_name'])) {
            update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
        }
        if (isset($_POST['last_name'])) {
            update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
        }

        // Update email (only if changed)
        if (!empty($_POST['email']) && is_email($_POST['email'])) {
            wp_update_user([
                'ID'       => $user_id,
                'user_email' => sanitize_email($_POST['email'])
            ]);
        }

        echo "<div class='fc-success'>Profile updated successfully.</div>";
    }

    // === GET UPDATED VALUES ===
    $phone = get_user_meta($user_id, 'phone', true);
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);

    ob_start();
    ?>

<form method="POST">

    <?php wp_nonce_field('fc_update_profile', 'fc_profile_nonce'); ?>

    <div class="section">

        <div class="info-header">
            <h1>Information</h1>
            <button class="save-btn" type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                     width="16" height="16">
                    <path d="M3 4.99509C3 3.89323 3.89262 3 4.99509 3H19.0049C20.1068 3 
                    21 3.89262 21 4.99509V19.0049C21 20.1068 20.1074 21 19.0049 
                    21H4.99509C3.89323 21 3 20.1074 3 19.0049V4.99509ZM5 5V19H19V5H5Z"/>
                </svg>
                Save changes
            </button>
        </div>

        <div class="form-row">
            <div class="form-group input-label-top">
                <label>Username</label>
                <input type="text" value="<?php echo esc_attr($user->user_login); ?>" disabled>
            </div>

            <div class="form-group input-label-top">
                <label>Phone number</label>
                <input type="text" name="phone" value="<?php echo esc_attr($phone); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group input-label-top" style="flex:1; position:relative;">
                <label>Email</label>
                <input type="text" 
                       id="emailField"
                       name="email" 
                       value="<?php echo esc_attr($user->user_email); ?>" 
                       disabled>

                <span class="edit-icon" id="editEmail" 
                      style="cursor:pointer; position:absolute; right:10px; bottom:12px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="#fff" width="18" height="18">
                        <path d="M15.7279 9.57627L14.3137 8.16206L5 17.4758V18.89H6.41421L15.7279 
                        9.57627ZM17.1421 8.16206L18.5563 6.74785L17.1421 
                        5.33363L15.7279 6.74785L17.1421 8.16206ZM7.24264 
                        20.89H3V16.6473L16.435 3.21231C16.8256 2.82179 
                        17.4587 2.82179 17.8492 3.21231L20.6777 6.04074C21.0682 
                        6.43126 21.0682 7.06443 20.6777 7.45495L7.24264 20.89Z">
                        </path>
                    </svg>
                </span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>First name</label>
                <input type="text" name="first_name" value="<?php echo esc_attr($first_name); ?>">
            </div>

            <div class="form-group">
                <label>Last name</label>
                <input type="text" name="last_name" value="<?php echo esc_attr($last_name); ?>">
            </div>
        </div>

    </div>

</form>

				<!-- Address -->
				<!-- Address -->
<!-- 				<div class="section">
					<div class="form-row"
						 style="justify-content: space-between; align-items:center;">
						<h3 style="margin:0;">Address</h3>
						<button id="addAddressBtn" class="add-btn ">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.0839 15.812C19.6827 13.0691 19.6379 8.73845 16.9497 6.05025C14.2161 3.31658 9.78392 3.31658 7.05025 6.05025C4.36205 8.73845 4.31734 13.0691 6.91612 15.812C7.97763 14.1228 9.8577 13 12 13C14.1423 13 16.0224 14.1228 17.0839 15.812ZM8.38535 17.2848L12 20.8995L15.6147 17.2848C14.9725 15.9339 13.5953 15 12 15C10.4047 15 9.0275 15.9339 8.38535 17.2848ZM12 23.7279L5.63604 17.364C2.12132 13.8492 2.12132 8.15076 5.63604 4.63604C9.15076 1.12132 14.8492 1.12132 18.364 4.63604C21.8787 8.15076 21.8787 13.8492 18.364 17.364L12 23.7279ZM12 10C12.5523 10 13 9.55228 13 9C13 8.44772 12.5523 8 12 8C11.4477 8 11 8.44772 11 9C11 9.55228 11.4477 10 12 10ZM12 12C10.3431 12 9 10.6569 9 9C9 7.34315 10.3431 6 12 6C13.6569 6 15 7.34315 15 9C15 10.6569 13.6569 12 12 12Z"></path></svg>
							Add Address</button>
					</div>

					
					<div class="address-box">
						<p class="no-address">No Address</p>
						<p>Punjab, Pakistan</p>
					</div>
				</div> -->

				<!-- Timezone -->
<!-- 				<div class="section">
					<div class="form-group timezone-label-top">
						<label>Time zone</label>
						<select>
							<option>Asia - Karachi (GMT+5:00) PKT</option>
							<option>Asia - Lahore (GMT+5:00)</option>
							<option>Asia - Dubai (GMT+4:00)</option>
						</select>
					</div>
				</div> -->

				<!-- Email -->
<!-- 				<div class="section">
					<div class="email-options">
						<div class="toggle">
							<span>Receive important emails</span>
							<label class="switch">
								<input type="checkbox" checked>
								<span class="slider"></span>
							</label>
						</div>
						<div class="toggle">
							<span>Receive promotion emails</span>
							<label class="switch">
								<input type="checkbox" checked>
								<span class="slider"></span>
							</label>
						</div>
					</div>
				</div> -->
			</div>
		
		
		</div>
	
		
		
    <!-- Popup Modal -->
    <div class="modal" id="emailModal">
      <div class="modal-content">
        <button class="close-btn" id="closeModal">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
            fill="white"><path
              d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 10.5858L9.17157 7.75736L7.75736 9.17157L10.5858 12L7.75736 14.8284L9.17157 16.2426L12 13.4142L14.8284 16.2426L16.2426 14.8284L13.4142 12L16.2426 9.17157L14.8284 7.75736L12 10.5858Z"></path></svg>
        </button>
        <h4>Change Fantasy Email</h4>
        <div class="form-group input-label-top">
          <label>Current Email</label>
          <input type="email" placeholder="Enter current email">
        </div>
        <div class="form-group">
          <input type="email" placeholder="Enter new email">
        </div>
        <div class="form-group">
          <input type="email" placeholder="Confirm new email">
        </div>
       <div class="form-group password-field">
		  <input type="password" id="passwordInput" placeholder="Enter password">
		  <svg id="togglePassword" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
			   fill="#9ca3af" class="eye-icon">
			<path
			  d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7zm0 12
			  c-2.76 0-5-2.24-5-5s2.24-5 5-5
			  5 2.24 5 5-2.24 5-5 5zm0-8
			  c-1.66 0-3 1.34-3 3
			  s1.34 3 3 3 3-1.34 3-3
			  -1.34-3-3-3z"/>
		  </svg>
		</div>

        <div class="Sub-btn">
          <button class="submit-btn" id="submitModal">Submit</button>
        </div>
      </div>
    </div>

		<!-- Add Address Modal -->
	<div id="addAddressModal" class="modal">
	  <div class="modal-content">
		<div class="modal-header">
		  <h2>Add Address</h2>
		  <span class="close" id="closeAddressModal">&times;</span>
		</div>

		<!-- Search View -->
		<div id="searchView">
		  <div class="input-group input-label-top">
			<label for="">address</label>
			<input type="text" placeholder="Search for your address..." />
		  </div>

		  <button class="manual-btn" id="openManualBtn">
			Or enter address manually
		  </button>
		</div>

		<!-- Manual Address View -->
		<div id="manualView" style="display: none;">
		  <div class="input-group  input-label-top">
			<label for="">Country</label>
			<select>
			  <option>Select Country</option>
			  <option>Pakistan</option>
			  <option>United States</option>
			  <option>United Kingdom</option>
			  <option>Canada</option>
			</select>
		  </div>

		  <div class="input-group">
			<input type="text" placeholder="Enter your address" />
		  </div>

		  <div class="input-group">
			<input type="text" placeholder="Apartment / Suite / Unit" />
		  </div>

		  <div class="input-row">
			<div class="input-group">
			  <input type="text" placeholder="City" />
			</div>
			<div class="input-group input-label-top">
			  <label for="">Province / State</label>
			  <input type="text" placeholder="Province / State" />
			</div>
		  </div>

		  <div class="input-group">
			<input type="text" placeholder="Zip / Postal code" />
		  </div>
			<div class="Sub-btn">
			   <button class="submit-btn">Save Address</button>
			</div>

		</div>
	  </div>
	</div>


    <script>
    const editEmail = document.getElementById('editEmail');
    const modal = document.getElementById('emailModal');
    const closeModal = document.getElementById('closeModal');
    const submitModal = document.getElementById('submitModal');

    editEmail.addEventListener('click', () => {
      modal.style.display = 'flex';
    });

    closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    submitModal.addEventListener('click', () => {
      alert("Email updated successfully!");
      modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      if (e.target === modal) modal.style.display = 'none';
    });

    const passwordInput = document.getElementById('passwordInput');
const togglePassword = document.getElementById('togglePassword');

togglePassword.addEventListener('click', () => {
  const isPassword = passwordInput.type === 'password';
  passwordInput.type = isPassword ? 'text' : 'password';
  
  // Toggle icon between eye open and closed
  togglePassword.innerHTML = isPassword
    ? `<path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12c-2.76 
    0-5-2.24-5-5s2.24-5 5-5 5 
    2.24 5 5-2.24 5-5 5z"/>`
    : `<path d="M12 5c-7 0-11 7-11 7s4 7 11 7c1.66 0 3.24-.41 
    4.67-1.17l2.1 2.1 1.41-1.41-17-17-1.41 
    1.41 2.65 2.65C5.91 7.05 8.83 6 12 6c7 0 11 
    7 11 7s-1.64 2.79-4.33 4.65L17.83 
    16.5A8.97 8.97 0 0 0 12 17c-4.97 
    0-9.27-3.11-11-7 1.06-2.39 3.22-4.51 
    6.16-5.86l1.48 1.48C7.64 6.88 5.93 8.13 
    4.64 10c1.73 3.89 6 7 11 7 
    1.5 0 2.93-.27 4.22-.75l1.35 1.35c-1.67.79-3.52 
    1.4-5.57 1.4z"/>`;
});


 const addAddressBtn = document.getElementById('addAddressBtn'); // The "Add Address" button
  const addAddressModal = document.getElementById('addAddressModal');
  const closeAddressModal = document.getElementById('closeAddressModal');
  const openManualBtn = document.getElementById('openManualBtn');
  const searchView = document.getElementById('searchView');
  const manualView = document.getElementById('manualView');

  // Open modal
  addAddressBtn.addEventListener('click', () => {
    addAddressModal.style.display = 'flex';
  });

  // Close modal
  closeAddressModal.addEventListener('click', () => {
    addAddressModal.style.display = 'none';
    searchView.style.display = 'block';
    manualView.style.display = 'none';
  });

  // Switch to manual view
  openManualBtn.addEventListener('click', () => {
    searchView.style.display = 'none';
    manualView.style.display = 'block';
  });

  // Close modal on background click
  window.addEventListener('click', (e) => {
    if (e.target === addAddressModal) {
      addAddressModal.style.display = 'none';
      searchView.style.display = 'block';
      manualView.style.display = 'none';
    }
  });
</script>

  
<?php 
// Create a shortcode [custom_signup_form]
function custom_signup_form_shortcode() {
    ob_start(); // Start output buffering
    ?>
   <div class="custom-signup-container">
        <h2 class="signup-title">Create Your Free Account</h2>
        <form class="signup-form" id="customSignupForm">
            <input type="text" placeholder="Username" name="username" required>
            <input type="email" placeholder="Email Address" name="email" required>
            <input type="password" placeholder="Password" name="password" required>
            <input type="password" placeholder="Confirm Password" name="confirm_password" required>

            <label class="terms">
                <input type="checkbox" name="terms" required>
                <span>
                    By creating an account, you agree to our 
                    <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                </span>
            </label>

            <button type="submit" class="create-btn">Create Account</button>
            <p id="signup-message" style="margin-top: 15px;"></p>
        </form>
    </div>
<script>
    jQuery(document).ready(function($){
        $('#customSignupForm').on('submit', function(e){
            e.preventDefault();

            var form = $(this);
            var messageBox = $('#signup-message');
            messageBox.text('Creating your account...').css('color', '#fff');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: form.serialize() + '&action=custom_signup_form',
                success: function(response){
                    if(response.success){
                        messageBox.css('color', '#00ff99').text(response.data.message);
                        setTimeout(function(){
							
							
                            let redirectUrl = "<?php echo isset($_GET['redirect']) ? esc_url($_GET['redirect']) : ''; ?>";

                            if (redirectUrl !== "") {
                                window.location.href = redirectUrl;
                            } else {
                                window.location.href = "<?php echo site_url('/dashboard'); ?>";
                            }
                        }, 1500);
                    } else {
                        messageBox.css('color', '#ff4d4d').text(response.data.message);
                    }
                },
                error: function(){
                    messageBox.css('color', '#ff4d4d').text('Something went wrong. Please try again.');
                }
            });
        });
    });
    </script>

    <style>
      @import url('https://fonts.googleapis.com/css2?family=Freckle+Face&display=swap');

      .custom-signup-container {
        border-radius: 16px;
        text-align: center;
        width: 90%;
        max-width: 739px;
        backdrop-filter: blur(10px);
        margin: 60px auto;
        font-family: "Heroking", Sans-serif;
		 
      }

      body {
        font-family: "Aeonik Trial", Sans-serif;
      }

      .signup-title {
        color: #fff;
        font-size: 48px;
        margin-bottom: 30px;
        letter-spacing: -3px;
		line-height: 100%;
		  font-weight: 400;
      }

      .signup-form {
        display: flex;
        flex-direction: column;
         gap: 25px;
      }

      .signup-form input[type="text"],
      .signup-form input[type="email"],
      .signup-form input[type="password"] {
        box-shadow: 0px 13px 16.3px 0px rgba(0, 0, 0, 0.12);
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        padding: 20px;
        color: #fff;
        border-radius: 12px;
        font-size: 16px;
        outline: none;
        transition: all 0.3s ease;
      }

      .signup-form input::placeholder {
        color: rgba(255, 255, 255, 0.6);
      }

      .signup-form input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #ff9900;
      }

      .terms {
        display: flex;
        align-items: center;
		  font-family: "Aeonik Trial", Sans-serif;
        gap: 10px;
        background: rgba(255, 255, 255, 0.1);
        padding: 10px 15px;
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        text-align: left;
        backdrop-filter: blur(8px);
      }

      .terms input[type="checkbox"] {
        accent-color: #ff9900;
        width: 16px;
        height: 16px;
      }

      .terms a {
        color: #ff9900;
        text-decoration: none;
      }

      .terms a:hover {
        text-decoration: underline;
      }

      .create-btn {
			background-color: transparent;
			font-family: "Aeonik Trial", Sans-serif;
			font-size: 20px;
			font-weight: 700;
			line-height: 100%;
			letter-spacing: -3%;
			fill: var( --e-global-color-text );
			color: var( --e-global-color-text );
			background-image: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
			border-radius: 24px 24px 24px 24px;
			padding: 024px 048px 024px 048px;
		    cursor: pointer;
      }


      @media (max-width: 600px) {
        .custom-signup-container {
          padding: 25px;
        }
        .signup-title {
          font-size: 28px;
        }
      }
    </style>
    <?php
    return ob_get_clean(); // Return the buffered output
}
add_shortcode('custom_signup_form', 'custom_signup_form_shortcode');
<?php




// === Shortcode: [custom_login_form] ===
function custom_login_form_shortcode() {
    ob_start(); ?>
    <div class="custom-login-container">
        <h2 class="login-title">Welcome Back</h2>
        <form class="login-form" id="customLoginForm">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <div class="remember-section">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="<?php echo wp_lostpassword_url(); ?>" class="forgot-link">Forgot Password?</a>
				
            </div>
			<?php 
			
			$redirect = isset($_GET['redirect']) ? '?redirect='.esc_url($_GET['redirect']) : '';
// 			print_r($redirect);
			
			?>
			<a href="/onboarding<?php echo $redirect ?>" class="forgot-link">Sign Up</a>
            <button type="submit" class="login-btn">Log In</button>
            <p id="login-message" style="margin-top: 15px;"></p>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($){
        $('#customLoginForm').on('submit', function(e){
            e.preventDefault();

            var form = $(this);
            var messageBox = $('#login-message');
            messageBox.text('Logging in...').css('color', '#fff');

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: form.serialize() + '&action=custom_login_form',
                success: function(response){
                    if(response.success){
                        messageBox.css('color', '#00ff99').text(response.data.message);
                        setTimeout(function(){
							
							
                            window.location.href = '<?php echo isset($_GET['redirect']) ? $redirect : site_url('/profile'); ?>';
							
                        }, 1000);
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

      .custom-login-container {
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

      .login-title {
        color: #fff;
        font-size: 48px;
        margin-bottom: 30px;
        letter-spacing: -3px;
        line-height: 100%;
        font-weight: 400;
      }

      .login-form {
        display: flex;
        flex-direction: column;
        gap: 25px;
      }

      .login-form input[type="text"],
      .login-form input[type="password"] {
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

      .login-form input::placeholder {
        color: rgba(255, 255, 255, 0.6);
      }

      .login-form input:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #ff9900;
      }

      .remember-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: rgba(255,255,255,0.8);
        font-size: 14px;
      }

      .remember-section label {
        display: flex;
        align-items: center;
        gap: 8px;
		font-family: "Aeonik Trial", Sans-serif;
      }

      .remember-section input[type="checkbox"] {
        accent-color: #ff9900;
      }

      .forgot-link {
        color: #ff9900;
        text-decoration: none;
		 font-family: "Aeonik Trial", Sans-serif;
      }

      .forgot-link:hover {
        text-decoration: underline;
      }

      .login-btn {
        background-color: transparent;
        font-family: "Aeonik Trial", Sans-serif;
        font-size: 20px;
        font-weight: 700;
        line-height: 100%;
        letter-spacing: -3%;
        color: var(--e-global-color-text);
        background-image: linear-gradient(180deg, #FF9500 0%, #FF8000 100%);
        border-radius: 24px;
        padding: 24px 48px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .login-btn:hover {
        opacity: 0.9;
        transform: scale(1.02);
      }

      @media (max-width: 600px) {
        .custom-login-container {
          padding: 25px;
        }
        .login-title {
          font-size: 28px;
        }
      }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('myaccount_login_form', 'custom_login_form_shortcode');
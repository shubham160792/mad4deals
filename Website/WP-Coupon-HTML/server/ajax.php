<?php

$action =  isset( $_REQUEST['act'] ) ? $_REQUEST['act'] : '';

$doing = isset( $_REQUEST['st_doing'] ) ? $_REQUEST['st_doing'] : '';
switch ( $doing ) {
    case 'ajax_search':
            $json = array(
                'success' => true,
                'action'=> array(
                    "url"=> '#',
                    "text"=> 'View all 245 results',
                ),
                'results' => array(
                    array(
                        'title' => 'Athleteform',
                        'url' => 'single_store.html',
                        'description' => '12 Coupons',
                        'image' => '<img src="thumb/stores/athleteform.png" alt="">',
                    ),
                    array(
                        'title' => 'Religion',
                        'url' => 'single_store.html',
                        'description' => '12 Coupons',
                        'image' => '<img src="thumb/stores/religion.png" alt="">',
                    ),
                    array(
                        'title' => 'Nature',
                        'url' => 'single_store.html',
                        'description' => '12 Coupons',
                        'image' => '<img src="thumb/stores/nature.png" alt="">',
                    ),
                    array(
                        'title' => 'Starlight',
                        'url' => 'single_store.html',
                        'description' => '12 Coupons',
                        'image' => '<img src="thumb/stores/starlight.png" alt="">',
                    ),
                    array(
                        'title' => 'Scribble',
                        'url' => 'single_store.html',
                        'description' => '12 Coupons',
                        'image' => '<img src="thumb/stores/scribble.png" alt="">',
                    ),
                )
            );

        echo json_encode( $json );
        break;
}


switch ( $action ) {
    case 's':
        break;

    case 'modal-template':

?>

    <div class="st-user-modal stuser-wrapper"> <!-- this is the entire modal form, including the background-->
        <div class="stuser-modal-container"> <!-- this is the container wrapper -->

            <form id="stuser-login" class="stuser-form stuser-login-form form ui" action="http://localhost/st/st-coupon/" method="post">

                <div class="stuser-form-body">
                    <div class="st_social_login">
                        <a href="#" class="st_form_icon st_form_twitter"><i class="twitter icon"></i></i><span>Log in with Twitter </span></a>
                        <a href="#" class="st_form_icon st_form_facebook"><i class="facebook icon"></i></i><span>Log in with Facebook </span></a>
                        <a href="#" class="st_form_icon st_form_gplus"><i class="google plus icon"></i><span>Log in with Google </span></a>        
                    </div>
                    <div class="st_user_sep">
                        <span class="st_form_sep">or</span><hr>
                    </div>

                    <p class="fieldset stuser_input st_username_email">
                        <label class="st-username" for="signin-usernamef55f8057078bd1">Username or email</label>
                        <input name="st_username" class="full-width has-padding has-border" id="signin-usernamef55f8057078bd1" type="text" placeholder="Username or email">
                        <span class="st-error-message"></span>
                    </p>

                    <p class="fieldset stuser_input st_pwd">
                        <label class="image-replace st-password" for="signin-passwordf55f8057078bd1">Password</label>
                        <input name="st_pwd" class="full-width has-padding has-border" id="signin-passwordf55f8057078bd1" type="password" placeholder="Password">
                        <a href="#0" class="hide-password">Show</a>
                        <span class="st-error-message"></span>
                    </p>

                    <p class="forgetmenot fieldset">
                        <label> <input type="checkbox" value="forever" name="st-rememberme" checked> Remember me</label>
                        <a class="st-lost-pwd-link" href="#">Forgot password ?</a>
                    </p>

                    <p class="fieldset">
                        <input class="login-submit button btn" type="submit" value="Login">
                        <input type="hidden" value="" name="st_redirect_to">
                    </p>

                </div>

                <div class="stuser-form-footer">
                    <p>
                        Don't have an account ? <a class="st-register-link" href="http://localhost/st/st-coupon/user/?st_action=register">Sign Up</a>
                    </p>
                </div>
            </form>

            <form id="st-signup" class="stuser-form st-register-form in-st-modal form ui" action="http://localhost/st/st-coupon/" method="post">

                <div class="stuser-form-body">

                    <div class="st_social_login">
                        <a href="#" class="st_form_icon st_form_twitter"><i class="twitter icon"></i></i><span>Sign up with Twitter </span></a>
                        <a href="#" class="st_form_icon st_form_facebook"><i class="facebook icon"></i></i><span>Sign up with Facebook </span></a>
                        <a href="#" class="st_form_icon st_form_gplus"><i class="google plus icon"></i><span>Sign up with Google </span></a>        
                    </div>
                    <div class="st_user_sep">
                        <span class="st_form_sep">or</span><hr>
                    </div>

                    <div class="stuser-form-fields">
                        <p class="fieldset stuser_input st_username">
                            <label class="image-replace st-username" for="signup-usernamer-55f8057078f48">Username</label>
                            <input name="st_signup_username" class="full-width has-padding has-border" id="signup-usernamer-55f8057078f48" type="text" placeholder="Username">
                            <span class="st-error-message"></span>
                        </p>

                        <p class="fieldset stuser_input st_email">
                            <label class="image-replace st-email" for="signup-emailr-55f8057078f48">E-mail</label>
                            <input name="st_signup_email" class="full-width has-padding has-border" id="signup-emailr-55f8057078f48" type="email" placeholder="E-mail">
                            <span class="st-error-message"></span>
                        </p>

                        <p class="fieldset stuser_input st_password">
                            <label class="image-replace st-password" for="signup-passwordr-55f8057078f48">Password</label>
                            <input name="st_signup_password" class="full-width has-padding has-border" id="signup-passwordr-55f8057078f48" type="password" placeholder="Password">
                            <a href="#" class="hide-password">Show</a>
                            <span class="st-error-message"></span>
                        </p>

                        <p class="fieldset">
                            <input class="signup-submit button btn" type="submit" data-loading-text="Loading..." value="Sign Up">
                        </p>
                    </div>

                </div>

                <div class="stuser-form-footer">
                    <p>
                        Already have an account ? <a class="st-back-to-login" href="http://localhost/st/st-coupon/wp-login.php">Login</a>
                    </p>
                </div>
            </form>
            <form id="st-reset-password" class="stuser-form stuser-form-reset-password form ui" action="" method="post">
                <div class="stuser-form-header">
                    <h3>Reset your password</h3>
                </div>

                <div class="stuser-form-body">
                    <p class="stuser-form-message">
                        Please enter your email address. You will receive a link to create a new password.
                    </p>

                    <p class="st-user-msg">Check your e-mail for the confirmation link.</p>

                    <div class="stuser-form-fields">
                        <p class="fieldset stuser_input st_input_combo">
                            <label class="st-email" for="reset-emailf55f80570791c9">User name or E-mail</label>
                            <input name="st_user_login" class="full-width has-padding has-border" id="reset-emailf55f80570791c9" type="text" placeholder="User name or E-mail">
                            <span class="st-error-message"></span>
                        </p>

                        <p class="fieldset">
                            <input class="reset-submit button btn" data-loading-text="Loading..." type="submit" value="Submit">
                        </p>
                    </div>
                </div>
                <div class="stuser-form-footer">
                    <p>Remember your password ? <a class="st-back-to-login" href="http://localhost/st/st-coupon/wp-login.php">Login</a></p>
                </div>
            </form>
            <a href="#0" class="st-close-form">Close</a>
        </div>
        <!-- stuser-modal-container -->
    </div> <!-- st-user-modal -->

<?php
    break;
}
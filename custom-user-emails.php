<?php
/**
 * Plugin Name: JLS Custom User Emails (Nominee)
 * Description: This plugin overwrites the pluggable 'wp_new_user_notification()' plugin to allow the sending of a custom user emails.
 * Version: 1.0
 * Author: Jonathan Loescher
 * Author URI: http://www.jonathanloescher.com 
 */

if ( !function_exists( 'wp_new_user_notification' ) ) {
    function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {
        
        // setting globals for password reset link
        global $wpdb, $wp_hasher;
        
        // set content type to html
        add_filter( 'wp_mail_content_type', 'wpmail_content_type' );
 
        // set variables
        $user = new WP_User( $user_id );
        $userLogin = stripslashes( $user->user_login );
        $userEmail = stripslashes( $user->user_email );
        $userFirstName = stripslashes( $user->first_name );
        $userLastName = stripslashes( $user->last_name );
        $siteUrl = get_site_url();
        $loginURL = wp_login_url();
        $logoUrl = plugin_dir_url( __FILE__ ).'/logo.png';
        $blogName = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
 
        $subject = 'Welcome to ' . $blogName;
        $headers = 'From: Barrett Family Foundation <noreply@barrettfamilyfoundation.org>';
 
        // admin email
        $message  = "A new user has been created"."<br /><br />";
        $message .= 'Email: '.$userEmail."<br />";
        $message .= 'Username: '.$userLogin."<br />";
        $message .= 'Username: '.$userFirstName."<br />";
        $message .= 'Username: '.$userLastName."<br />";
        @wp_mail( get_option( 'admin_email' ), 'New User Created', $message, $headers );
        
        /* Copied from /wp-includes/pluggable.php */
        // Generate something random for a password reset key.
        $key = wp_generate_password( 20, false );

        /** This action is documented in wp-login.php */
        do_action( 'retrieve_password_key', $user->user_login, $key );

        // Now insert the key, hashed, into the DB.
        if ( empty( $wp_hasher ) ) {
            require_once ABSPATH . WPINC . '/class-phpass.php';
            $wp_hasher = new PasswordHash( 8, true );
        }
        $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
        $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );

       
        /* end copied */
        
        $resetURL = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');

        if ( empty($plaintext_pass) ) {
            // create $plaintext_pass variable with instruction to create password if no password exists
            $plaintext_pass = 'To set your password, ' . '<a href="' . $resetURL . '">click here</a>.';
        }
 
        ob_start();
        include plugin_dir_path( __FILE__ ).'/email-welcome.php';
        $message = ob_get_contents();
        ob_end_clean();
 
        @wp_mail( $userEmail, $subject, $message, $headers );
 
        // remove html content type
        remove_filter ( 'wp_mail_content_type', 'wpmail_content_type' );
 
    }
}
/**
 * wpmail_content_type
 * allow html emails
 *
 * @author Joe Sexton <joe@webtipblog.com>
 * @return string
 */
function wpmail_content_type() {
 
    return 'text/html';
}
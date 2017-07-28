<?php
/*
 Plugin Name: WC Password Strength Settings
 Plugin URI: https://danielsantoro.com/project/woocommerce-password-strength-settings-plugin
 Description: Allows administrators to set the required password strength or disable it entirely from the WooCommerce Accounts menu.
 Author: Daniel Santoro
 Author URI: https://danielsantoro.com
 Version: 1.2.0-beta
 License: GPLv2 or later
 */


/**
 * Add custom action links on the plugin screen.
 */
function wcpss_add_plugin_links( $links ) {
    $new_links = '<a href="https://danielsantoro.com/project/woocommerce-password-strength-settings-plugin/?utm_source=pw-strength-plugin&utm_medium=plugin-overview-link" target="_blank">' . __( 'Documentation' ) . '</a>' . ' | ' . '<a href="https://danielsantoro.com/support/?utm_source=pw-strength-plugin&utm_medium=plugin-overview-link" target="_blank">' . __( 'Support' ) . '</a>';
    array_push( $links, $new_links );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'wcpss_add_plugin_links' );


/**
 * Administration Options
 */
add_filter( 'woocommerce_get_settings_account','wcpss_woo_account_setting' );
function wcpss_woo_account_setting($settings) {
    $settings[]=array( 'title' => __( 'User Password Strength Settings', 'woocommerce' ), 'type' => 'title', 'id' => 'account_password_options' );
    $settings[]=array(
                'title'    => __( 'Strength Requirement', 'woocommerce' ),
                'desc'     => __( 'Select the minimum strength of your user passwords. Level 4 = Default.', 'woocommerce' ),
                'id'       => 'woocommerce_myaccount_password_strength',
                'type'     => 'select',
                'default'  => '',
                'class'    => 'wc-enhanced-select',
                'css'      => 'min-width:350px;',
                'desc_tip' => true,
                'options'  =>array(
                    '0'=>__( 'Level 1 - Anything', 'woocommerce' ),
                    '1'=>__( 'Level 2 - Weakest', 'woocommerce' ),
                    '2'=>__( 'Level 3 - Weak', 'woocommerce' ),
                    '3'=>__( 'Level 4 - Medium', 'woocommerce' ),
                    '4'=>__( 'Level 5 - Strong', 'woocommerce' ),
                ),
            );

    $settings[]=array(
				'title'    => __( 'Level 1 Message', 'woocommerce' ),
				'desc'     => __( 'Usually is not displayed - can be left as-is.', 'woocommerce' ),
				'id'       => 'woocommerce_password_strength_label_1',
				'type'     => 'text',
				'css'      => 'min-width:350px;',
				'default'  => 'empty',
				'placeholder' => 'empty',
				'desc_tip' => true,
			);

    $settings[]=array(
				'title'    => __( 'Level 2 Message', 'woocommerce' ),
				'desc'     => __( 'Will display until level 2 strength bypassed.', 'woocommerce' ),
				'id'       => 'woocommerce_password_strength_label_2',
				'type'     => 'text',
				'css'      => 'min-width:350px;',
				'default'  => 'Short: Your password is too short.',
				'placeholder'  => 'Your password is too short.',
				'desc_tip' => true,
			);

    $settings[]=array(
				'title'    => __( 'Level 3 Message', 'woocommerce' ),
				'desc'     => __( 'Will display until level 3 strength bypassed.', 'woocommerce' ),
				'id'       => 'woocommerce_password_strength_label_3',
				'type'     => 'text',
				'css'      => 'min-width:350px;',
				'default'  => 'Password Strength: Weak',
				'placeholder'  => 'Password Strength: Weak',
				'desc_tip' => true,
			);

    $settings[]=array(
				'title'    => __( 'Level 4 Message', 'woocommerce' ),
				'desc'     => __( 'Will display until level 4 strength bypassed.', 'woocommerce' ),
				'id'       => 'woocommerce_password_strength_label_4',
				'type'     => 'text',
				'css'      => 'min-width:350px;',
				'default'  => 'Password Strength: OK',
				'placeholder'  => 'Password Strength: OK',
				'desc_tip' => true,
			);

    $settings[]=array(
				'title'    => __( 'Level 5 Message', 'woocommerce' ),
				'desc'     => __( 'Will display until level 5 strength bypassed.', 'woocommerce' ),
				'id'       => 'woocommerce_password_strength_label_5',
				'type'     => 'text',
				'css'      => 'min-width:350px;',
				'default'  => 'Password Strength: Strong',
				'placeholder'  => 'Password Strength: Strong',
				'desc_tip' => true,
			);

        $settings[]=array(
				'title'    => __( 'Error Message', 'woocommerce' ),
				'desc'     => __( 'Will display if there is an error in the user-selected password.', 'woocommerce' ),
				'id'       => 'woocommerce_password_error',
				'type'     => 'text',
				'css'      => 'min-width:350px;',
				'default'  => 'Please try again.',
				'placeholder'  => 'Please try again.',
				'desc_tip'  => true,
			);

    $settings[]=array( 'type' => 'sectionend', 'id' => 'account_password_options' );
    return $settings;
}

/**
 * Frontend Display
 */
add_filter('woocommerce_min_password_strength','wcpss_change_password_strength',30);
function wcpss_change_password_strength() {
    $strength=get_option( 'woocommerce_myaccount_password_strength', null );
    return intval($strength);
    
}

/**
 * Localization
 */
add_action( 'wp_enqueue_scripts',  'wcpss_load_scripts',99 );
function wcpss_load_scripts() {
    wp_localize_script( 'wc-password-strength-meter', 'pwsL10n', array(
        'empty' => __( get_option( 'woocommerce_password_strength_label_1', null ) ),
        'short' => __( get_option( 'woocommerce_password_strength_label_2', null ) ),
        'bad' => __( get_option( 'woocommerce_password_strength_label_3', null ) ),
        'good' => __( get_option( 'woocommerce_password_strength_label_4', null ) ),
        'strong' => __( get_option( 'woocommerce_password_strength_label_5', null ) ),
        'mismatch' => __( 'Your passwords do not match, please re-enter them.' )
    ) );
}

add_filter( 'wc_password_strength_meter_params', 'ds_strength_meter_custom_strings' );
function ds_strength_meter_custom_strings( $data ) {
    $data_new = array(
        'i18n_password_error'   => esc_attr__( get_option( 'woocommerce_password_error', null ), 'woocommerce' ),
        'i18n_password_hint'    => esc_attr__( get_option( 'woocommerce_password_hint', null ), 'woocommerce' ),
    );
    return array_merge( $data, $data_new );
}

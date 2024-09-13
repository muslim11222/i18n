<?php 

/**
 * Plugin name: i18n plugin
 * Description: This is a i18n plugin
 * Version: 0.0.1
 * Author: Muslim Khan
 * Author url: http://www.i18n.com
 * Textdomain: qr-code
 * Domain Path: /languages
 */
// i18n = Internationalization
// l10n = Localisation


 class i18n_plugin {
     public function __construct() {
         add_action('init', array($this, 'init'));
     }
 
     public function init() {
         add_filter('the_content', array($this, 'the_content_hook'));
         add_action('plugins_loaded', array($this, 'plugins_loaded'));
         add_action('admin_menu', [$this, 'qr_code_add_settings_page']);
     }


     public function qr_code_add_settings_page() {
          $heading = __("QR Code Settings", "qr-code");
          add_options_page($heading, $heading, 'manage_options', 'qr-code-settings', [$this, 'qr_code_settings_page']);
     }


     public function qr_code_settings_page() {
          ?>
               <div class="wrap">
                    <h1>
                         <?php _e("QR Code Settings", "qr-code"); ?>
                    </h1>
                    <p>
                         <?php _e("This is a QR Code Plugin Settings Page", "qr-code"); ?>
                    </p>

                    <p>
                         <?php
                              $generatedQRCode = 1327;
                              $translatableString = __("Generated QR Code: %d", "qr-code");
                              //bangla translation
                              $translatableString = __("টোটাল কিউআরকোড জেনারেট হয়েছে: %d", "qr-code");
                              printf($translatableString, $generatedQRCode);
                         ?>
                    </p>


                    <!-- calender k ki vabe banglai kora jai seta dekhabo holo -->
                    <p>
                         <?php 
                              global $wp_locale;
                              $months =  $wp_locale->month;
                              echo "<ul>";
                              foreach($months as $month) {
                                   echo "<li> {$month}</li>";
                              }
                         ?>
                    </p>

                    <!-- atar kaj holo number jemon e dei na kno ...browser e ta forment hoye show korbe  -->
                    <p>
                         <?php
                         //number format using i18n
                         $number = "5635252545234567.89";
                         echo __("you have ", "qr-code") . number_format_i18n($number,2) . __(" dollar", "qr-code");
                         ?>
                    </p>

                    <!-- atar kaj holo date time k formet korbe  -->
                    <p>
                         <?php 
                              //display date using i18n
                              $date = "2022-12-10";
                              $timestamp = strtotime( $date );
                              $current_date_setting = get_option( 'date_format' );
                              echo date_i18n( $current_date_setting, $timestamp );
                         ?>
                   </p>


                    <!-- atar kaj holo post beshi hole s jukto hbe . mane 2 hole s hbe -->
                   <p>
                         <?php 
                              //singular plural translation 
                              $count = 1;
                              $singular = "You have %d post";
                              $plural = "You have %d posts";

                              printf( _n( $singular, $plural, $count, "qr-code" ), $count );
                         ?>
                    </p>

               </div>   
          <?php
     }


     //plugins_loaded
     public function plugins_loaded() {
          load_plugin_textdomain('qr-code', false, dirname(plugin_basename(__FILE__)) ."/languages");
     }
 
     public function the_content_hook($content) {
          $current_post_url = get_permalink();
          $size = 150;
          $color = '000';
          $final_size = apply_filters('qr_code_size', $size); //200
          $final_color = apply_filters('qr_code_color', $color); //000
          // $heading = apply_filters('qr_code_heading', 'Amazing QR Code');
          $heading = __("Amazing QR Code", "qr-code");
  
          $footer = "Powered by QR Code Plugin";
  
          _e("Powered by QR Code Plugin", "qr-code");
  
          $qr_code_image = "https://api.qrserver.com/v1/create-qr-code/?size={$final_size}x{$final_size}&color={$final_color}&data=" . $current_post_url;
          $newcontent = $content . "<h1>{$heading}</h1><p><img src='{$qr_code_image}'></p><p>{$footer}</p>";
          do_action('qr_code_displayed', $current_post_url);
          return $newcontent;
          // return $content."<p>URL: {$current_post_url}</p>";
     }
 }
 
 new i18n_plugin();
 
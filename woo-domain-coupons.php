<?php
/*
Plugin Name: Woo Domain Coupons
Description: Allows Woo Coupons to be restricted by domain
Version: 0.01.00
Author: Two Row Studio
Text Domain: woo_domain_coupons
*/


/*************************************************************************/
/* Woo Domain Coupons Plugin                                             */
/*                                                                       */
/* Plugin to extend the restrictions for WooCommerce Coupons to a        */
/* specific domains for a customer's registered email address            */
/*************************************************************************/

defined ('ABSPATH') or die('No Direct access!');

define('WDC_BASE_DIR',plugin_dir_path(__FILE__));
define('WDC_BASE_URL',plugin_dir_url(__FILE__));

add_action('admin_enqueue_scripts','enqueue_WDC_styles');

function enqueue_WDC_styles(){
  wp_enqueue_style('WDC_styles',WDC_BASE_URL.'/assets/css/woo_domain_coupon.css');
}

add_filter( 'woocommerce_coupon_data_tabs','add_domain_restriction_section',20,1);

if (! function_exists ('add_domain_restriction_section') ){
  function add_domain_restriction_section($sections){
    $sections['domain_restriction'] = array(
        'label' => __('Domain Specific Coupons','woo_domain_coupons'),
        'target' => 'domain_restriction_data',
        'class' => 'domain_restriction_data',
    );
    return $sections;
  }
}

add_action('woocommerce_coupon_data_panels','add_domain_restriction_settings',10,2);

if (! function_exists ('add_domain_restriction_settings') ){
  function add_domain_restriction_settings($coupon_id,$coupon){
    ?>
    <div id="domain_restriction_data" class="panel woocommerce_options_panel"><?php
    $label = get_post_meta($coupon_id,'_wdc_cust_label',true);
    $domain = get_post_meta($coupon_id,'_wdc_cust_domain',true);

    echo '<div class="options_group">';
      // Customer label
    woocommerce_wp_text_input( array(
        'label'     => __( 'Customer', 'woo_domain_coupons' ),
  			'description' => __( 'What comapany name should be displayed as the customer for this coupon?', 'woo_domain_coupons' ),
  			'id'       => 'dom_restrict_cust_label',
  			'type'     => 'text',
  			'desc_tip'     => true,
        'value' => $label
      )
    );
    // Customer Domains
    woocommerce_wp_text_input(  array(
      'label'     => __( 'Customer Domain', 'woo_domain_coupons' ),
      'description' => __( 'What domain should be required for the coupon to be applied?', 'woo_domain_coupons' ),
      'id'       => 'dom_restrict_domain',
      'type'     => 'text',
      'desc_tip'     => true,
      'value' => $domain
      )
    );
    echo'<p>If a domain is set here for this coupon, only email addresses with that domain will be able to use the coupon.</p>';
    echo '</div></div>';
  }
}

//save domain restriction domain restriction data

add_action('woocommerce_coupon_options_save','save_domain_restriction_data',20,2);

function save_domain_restriction_data($post_id, $coupon){
  $data['_wdc_cust_label'] = $_POST['dom_restrict_cust_label'];
  $data['_wdc_cust_domain'] = $_POST['dom_restrict_domain'];

  foreach ($data as $key=>$value){
    if (get_post_meta($post_id,$key,true)) {
      if ($value){
        update_post_meta($post_id,$key,$value);
      }else{
        delete_post_meta($post_id, $key);
      }
    }else {
      add_post_meta($post_id, $key, $value);
    }
  }

}

  add_action('woocommerce_after_checkout_validation','check_domain_coupon',2);

  function check_domain_coupon($posted){
    $cart = new WC_Cart();
    $cart->get_cart_from_session();
    if (! empty($cart->applied_coupons)){
      $coupons = $cart->applied_coupons;
      $domains = array();
      foreach($coupons as $code){
        $coupon_id = wc_get_coupon_id_by_code($code);
        $coupon = new WC_Coupon($code);
        $domain = get_post_meta($coupon_id,'_wdc_cust_domain',true);
        $label = get_post_meta($coupon_id,'_wdc_cust_label',true);
        array_push($domains,$domain);
        if ($domains){
          $cust_domain = array();
          if (is_user_logged_in()){
            $current_user = wp_get_current_user();
            $cust_email = $current_user->user_email;
            $cust_domain[] = find_domain($cust_email);
          }
          $form_email = $posted['billing_email'];
          array_push($cust_domain,find_domain($form_email));
          if (0==sizeof(array_intersect($cust_domain,$domains))){
            wc_add_notice ("This coupon cannot be applied since this code is reserved for ".$label.". Please use your ".$label." email address to use this code.",'error');
            wc_add_notice ("Email Domain used: ".json_encode($cust_domain)."<br>Coupon Domains: ".json_encode($domains),'notice');
            $cart->remove_coupon ($code);
            WC()->session->set('refresh_totals',true);
            $cart->calculate_totals();
            $cart->total = max( 0, apply_filters( 'woocommerce_calculated_total', round( $cart->cart_contents_total + $cart->tax_total + $cart->shipping_tax_total + $cart->shipping_total + $cart->fee_total, $cart->dp ), $cart ) );
          }
        }
      }
    }
  }

  function find_domain ($email){
    $dom_delimit = strpos($email,"@");
    $domain = substr($email,$dom_delimit+1);
    return $domain;
  }

  ?>

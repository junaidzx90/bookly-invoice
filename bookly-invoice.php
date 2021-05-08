<?php
 /**
 *
 * @link              https://github.com/
 * @since             1.0.0
 * @package           bookly_invoice
 *
 * @wordpress-plugin
 * Plugin Name:       Bookly Invoice
 * Plugin URI:        https://github.com/junaidzx90/bookly-invoice
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Junayedzx90
 * Author URI:        https://www.fiverr.com/junaidzx90
 * Text Domain:       bookly-invoice
 * Domain Path:       /languages
 */

// If this file is called directly, abort.

define( 'BKLY_NAME', 'bookly-invoice' );
define( 'BKLY_PATH', plugin_dir_path( __FILE__ ) );
define( 'BKLY_URL', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'WPINC' ) && ! defined('BKLY_NAME') && ! defined('BKLY_PATH')) {
	die;
}

add_action( 'plugins_loaded', 'bookly_invoice_init' );
function bookly_invoice_init() {
    if(!function_exists('bookly_loader')){
        add_action( 'admin_notices', 'bookly_invoice_admin_noticess' );
    }

    load_plugin_textdomain( 'bookly_invoice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    add_action('init', 'bookly_invoice_run');
}

// Main Function iitialize
function bookly_invoice_run(){

    function bookly_invoice_admin_noticess(){
        $message = sprintf(
            /* translators: 1: Plugin Name 2: Elementor */
            print_r( '%1$s requires <a href="https://wordpress.org/plugins/bookly-responsive-appointment-booking-tool/"> %2$s </a> to be installed and activated.', 'bookly_invoice' ),
            '<strong>' . esc_html__( 'Bookly Invoice', 'bookly_invoice' ) . '</strong>',
            '<strong>' . esc_html__( 'Bookly', 'bookly_invoice' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    register_activation_hook( __FILE__, 'activate_bookly_invoice_cplgn' );
    register_deactivation_hook( __FILE__, 'deactivate_bookly_invoice_cplgn' );

    // Activision function
    function activate_bookly_invoice_cplgn(){
        // global $wpdb;
        // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // $bookly_invoice_v1 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}bookly_invoice__v1` (
        //     `ID` INT NOT NULL AUTO_INCREMENT,
        //     `user_id` INT NOT NULL,
        //     `username` VARCHAR(255) NOT NULL,
        //     `account1` INT NOT NULL,
        //     `account2` INT NOT NULL,
        //     PRIMARY KEY (`ID`)) ENGINE = InnoDB";
        //     dbDelta($bookly_invoice_v1);
    }

    // Dectivision function
    function deactivate_bookly_invoice_cplgn(){
        // Nothing For Now
    }

    // Admin Enqueue Scripts
    add_action('admin_enqueue_scripts',function(){
        wp_register_style( BKLY_NAME, BKLY_URL.'admin/css/bookly-invoice.css', array(), microtime(), 'all' );
        wp_register_script( BKLY_NAME, BKLY_URL.'admin/js/bookly-invoice.js', array(), 
        microtime(), true );
        wp_localize_script( BKLY_NAME, 'admin_ajax_action', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ) );
    });

    // Register Menu
    add_action('admin_menu', function(){
        add_menu_page( 'bookly_invoice', 'bookly_invoice', 'manage_options', 'bookly_invoice', 'bookly_invoice_menupage_display', 'dashicons-admin-network', 45 );
    
        // // For colors
        // add_settings_section( 'bookly_invoice_colors_section', 'Activation Colors', '', 'bookly_invoice_colors' );
    
        // //Activate Button
        // add_settings_field( 'bookly_invoice_activate_button', 'Activate Button', 'bookly_invoice_activate_button_func', 'bookly_invoice_colors', 'bookly_invoice_colors_section');
        // register_setting( 'bookly_invoice_colors_section', 'bookly_invoice_activate_button');
        
    });

    // activate Button colors
    // function bookly_invoice_activate_button_func(){
        
    // }

    // Get payments information
    add_action("wp_ajax_get_invoice_data", "get_invoice_data");
    add_action("wp_ajax_nopriv_get_invoice_data", "get_invoice_data");
    function get_invoice_data(){
        if(isset($_GET['user_id'])){
            global $wpdb;
            $user_id = $_GET['user_id'];
            $user_id = intval($user_id);

            // How many appoints
            $payments = $wpdb->get_results("SELECT bca.*,bp.*,bc.* FROM {$wpdb->prefix}bookly_customer_appointments bca, {$wpdb->prefix}bookly_payments bp, {$wpdb->prefix}bookly_customers bc WHERE bca.customer_id = $user_id AND bca.customer_id = bc.id AND bca.payment_id = bp.id");
            
            
            //type,status,total,paid,full_name,phone,country,postcode,state,email,city,street,payment_date
            //     ["details"]=>
            //     string(392) "{"items":[{"ca_id":98,"appointment_date":"2021-04-05 10:00:00","service_name":"Rijles 1 uur","service_price":"50","service_tax":0,"wait_listed":false,"number_of_persons":1,"units":1,"duration":"3600","staff_name":"Abdullah Cakmak","extras":[]}],"coupon":null,"subtotal":{"price":"50","deposit":0},"customer":"Samed S\u00f6kmen","tax_in_price":"excluded","tax_paid":"0.00","from_backend":true}"
            if(!empty($payments) && !is_wp_error( $wpdb )){
                
                $payment_details = json_decode($payments[0]->details,true);
                
                //$payment_details['items'][0];
                
                // adjustments
                
                $output = '';
                $output .= '<div id="wrapper">';
                $output .= '<div class="clearfix" id="header_section">';
                $output .= '<h1>'.__(get_bloginfo('name'), BKLY_NAME).'- INVOICE</h1>';

                $output .= '<div id="customer_info" class="clearfix">';
                $output .= '<div>Company Name</div>';
                $output .= '<div>CEO</div>';
                $output .= '<div>Phone</div>';
                $output .= '<div><a href="mailto:email@gmail.com">email@gmail.com</a></div>';
                $output .= '</div>';

                $output .= '<div id="informations">';
                $output .= '<div><span>CUSTOMER</span> '.__($payments[0]->full_name, BKLY_NAME).'</div>';
                $output .= '<div><span>ADDRESS</span> '.__($payments[0]->state.', '.$payments[0]->city.', '.$payments[0]->street.', '.$payments[0]->postcode.', '.$payments[0]->country, BKLY_NAME).'</div>';
                $output .= '<div><span>EMAIL</span> <a href="mailto:'.__($payments[0]->email, BKLY_NAME).'">'.__($payments[0]->email, BKLY_NAME).'</a></div>';
                // $output .= '<div><span>DATE</span> '.__(date('Y/m/d', strtotime($payments[0]->created_at)), BKLY_NAME).'</div>';
                $output .= '<div><span>TYPE</span> '.__($payments[0]->type, BKLY_NAME).'</div>';
                $output .= '</div>';

                $output .= '</div>';
                $output .= '<main>';
                $output .= '<table>';
                $output .= '<thead>';
                $output .= '<tr>';
                $output .= '<th class="service">Maintenance</th>';
                $output .= '<th class="desc">Date</th>';
                $output .= '<th>Hour</th>';
                $output .= '<th>Service Price</th>';
                $output .= '<th class="desc">Adjustments</th>';
                $output .= '<th class="amount">Adjust-Amount</th>';
                $output .= '<th class="paid">Paid</th>';
                $output .= '<th>Cost</th>';
                $output .= '</tr>';
                $output .= '</thead>';
                $output .= '<tbody>';

                foreach($payments as $pay){
                    $_details = json_decode($pay->details,true);
                    // var_dump($_details['subtotal']['price']);die;

                    $output .= '<tr>';
                    $output .= '<td class="service">'.__($_details['items'][0]['service_name'], BKLY_NAME).'</td>';$output .= '<td class="date">'.__(date('Y/m/d', strtotime($pay->created_at)), BKLY_NAME).'</td>';
                    $output .= '<td class="hour">'.floor($_details['items'][0]['duration'] / (60 * 60)).' Hour </td>';
                    $output .= '<td class="price">'.__($_details['items'][0]['service_price'], BKLY_NAME).'</td>';
                    $output .= '<td class="desc">';
                    if($_details['adjustments'][0]['reason'] !== "null"){
                        $output .= __($_details['adjustments'][0]['reason'], BKLY_NAME);
                    }
                    $output .= '</td>';
                    $output .= '<td class="amount">'.__($_details['adjustments'][0]['amount'], BKLY_NAME).'</td>';
                    $output .= '<td class="paid">'.__($pay->paid, BKLY_NAME).'</td>';
                    $output .= '<td class="cost">'.__($pay->total, BKLY_NAME).'</td>';
                    $output .= '</tr>';
                }
                $output .= '<tr>';
                $output .= '<td colspan="7">SUBTOTAL</td>';
                $output .= '<td class="total">'.__($_details['subtotal']['price'], BKLY_NAME).'</td>';
                $output .= '</tr>';
                $output .= '<tr>';
                $output .= '<td class="total">$1,300.00</td>';
                $output .= '</tr>';
                
                $output .= '</tbody>';
                $output .= '</table>';
                $output .= '</main>';
                $output .= '</div>';

                echo json_encode($output);
                die;
            }

            die;
        }
        die;
    }

    // bookly_invoice_reset
    add_action("wp_ajax_get_user_appoint_information", "get_user_appoint_information");
    add_action("wp_ajax_nopriv_get_user_appoint_information", "get_user_appoint_information");
    function get_user_appoint_information(){
        if(isset($_POST['user_id'])){
            global $wpdb;
            $customer_id = $_POST['user_id'];
            $customer_id = intval($customer_id);

            // How many appoints
            $user_appointments = $wpdb->get_results("SELECT bca.payment_id AS payment_id,bp.status AS status, bp.details AS payment_details FROM {$wpdb->prefix}bookly_customer_appointments bca, {$wpdb->prefix}bookly_payments bp WHERE bca.customer_id = $customer_id AND bp.id = payment_id");
            
            if(!empty($user_appointments) && !is_wp_error( $wpdb )){
                $output = '';
                $i = 1;
                foreach($user_appointments as $payments){
                    $payment_details = json_decode($payments->payment_details,true);
                    
                    $employee = $payment_details['items'][0]['staff_name'];
                    $date = $payment_details['items'][0]['appointment_date'];

                    $output .= '<tr>';
                    $output .= '<td>'.$i.'</td>';
                    $output .= '<td class="date">'.$date.'</td>';
                    $output .= '<td class="employee">'.__($employee,'bookly-invoice').'</td>';
                    $output .= '<td class="status '.$payments->status.'">'.__($payments->status,'bookly-invoice').'</td>';
                    $output .= '<td class="viewbtns"><button payment_id="'.intval($payments->payment_id).'" customer_id="'.intval($customer_id).'" id="view_invoice">VIEW</button></td>';
                    $output .= '</tr>';
                    $i++;

                }
                echo json_encode($output);
                die;
            }
            die;
        }
        die;
    }

    // How many appoints
    // $user_appointments = $wpdb->get_results("SELECT bca.payment_id AS payment_id,bp.total AS total,bp.status AS status, bp.paid AS paid,bp.created_at AS payment_date, bp.details AS payment_details FROM {$wpdb->prefix}bookly_customer_appointments bca, {$wpdb->prefix}bookly_payments bp WHERE bca.customer_id = $customer_id AND bp.id = payment_id");

    // Menu callback funnction
    function bookly_invoice_menupage_display(){
        if(function_exists('bookly_loader')){
            wp_enqueue_style(BKLY_NAME);
            wp_enqueue_script(BKLY_NAME);
            ?>
            <?php
            global $wpdb;
            $bookly_cappointments = $wpdb->get_results("SELECT bca.*,bp.*,bc.id AS ID,bc.* FROM {$wpdb->prefix}bookly_customer_appointments bca, {$wpdb->prefix}bookly_payments bp, {$wpdb->prefix}bookly_customers bc WHERE bca.customer_id = bc.id AND bca.payment_id = bp.id GROUP BY bc.full_name");

            // object(stdClass)#831 (54) {
            //     ["id"]=>
            //     string(2) "95"
            //     ["customer_id"]=>
            //     string(2) "95"
            //     ["appointment_id"]=>
            //     string(3) "101"
            //     ["payment_id"]=>
            //     string(2) "57"
            //     ["number_of_persons"]=>
            //     string(1) "1"
            //     ["status"]=>
            //     string(9) "completed"
            //     ["status_changed_at"]=>
            //     string(19) "2021-05-06 14:30:19"
            //     ["type"]=>
            //     string(5) "local"
            //     ["total"]=>
            //     string(5) "50.00"
            //     ["tax"]=>
            //     string(4) "0.00"
            //     ["paid"]=>
            //     string(5) "50.00"
            //     ["paid_type"]=>
            //     string(7) "in_full"
            // type,status,total,paid,full_name,phone,country,postcode,state,email,city,street,payment_date
            // //     ["details"]=>
            // //     string(392) "{"items":[{"ca_id":98,"appointment_date":"2021-04-05 10:00:00","service_name":"Rijles 1 uur","service_price":"50","service_tax":0,"wait_listed":false,"number_of_persons":1,"units":1,"duration":"3600","staff_name":"Abdullah Cakmak","extras":[]}],"coupon":null,"subtotal":{"price":"50","deposit":0},"customer":"Samed S\u00f6kmen","tax_in_price":"excluded","tax_paid":"0.00","from_backend":true}"
            //     ["wp_user_id"]=>
            //     string(2) "77"
            //     ["full_name"]=>
            //     string(13) "Samed Sökmen"
            //     ["first_name"]=>
            //     string(5) "Samed"
            //     ["last_name"]=>
            //     string(7) "Sökmen"
            //     ["payment_date"]=>
            //     string(19) "2021-04-04 09:36:25"
            //     ["phone"]=>
            //     string(12) "+31612324681"
            //     ["email"]=>
            //     string(16) "samsok@gmail.com"
            //     ["country"]=>
            //     string(0) ""
            //     ["state"]=>
            //     string(0) ""
            //     ["postcode"]=>
            //     string(6) "2011AC"
            //     ["city"]=>
            //     string(7) "Haarlem"
            //     ["street"]=>
            //     string(15) "Koralensteeg 44"
            //     ["street_number"]=>
            //     string(0) ""
            //     ["additional_address"]=>
            //     string(0) ""
            //   }

            // customer ids
            $customer_ids = [];
            // payment ids
            $payment_ids = [];
            // All payment details
            $payment_details = [];

            if(!empty($bookly_cappointments)){
                foreach($bookly_cappointments as $customerinfo){
                    // // customer_id
                    // $customer_ids[] = $customerinfo->customer_id;
                    // // payment_id
                    // $payment_ids[] = $customerinfo->payment_id;
                    // // $payment_details
                    // $payment_infos = json_decode($customerinfos->payment_details, true);
                    // $payment_details[] = $payment_info['items'][0];

                    require_once 'invoice-component.php';
                }
            }

            // echo '<pre>';
            // var_dump($customer_ids);
            
        }
    }
}
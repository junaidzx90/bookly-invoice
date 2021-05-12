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
    }else{
        add_action('init', 'bookly_invoice_run');
    }

    load_plugin_textdomain( 'bookly_invoice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function bookly_invoice_admin_noticess(){
    $message = sprintf(
        /* translators: 1: Plugin Name 2: Elementor */
        print_r( '%1$s requires <a href="https://wordpress.org/plugins/bookly-responsive-appointment-booking-tool/"> %2$s </a> to be installed and activated.', 'bookly_invoice' ),
        '<strong>' . esc_html__( 'Bookly Invoice', 'bookly_invoice' ) . '</strong>',
        '<strong>' . esc_html__( 'Bookly', 'bookly_invoice' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

// Main Function iitialize
function bookly_invoice_run(){
    register_activation_hook( __FILE__, 'activate_bookly_invoice_cplgn' );
    register_deactivation_hook( __FILE__, 'deactivate_bookly_invoice_cplgn' );

    // Activision function
    function activate_bookly_invoice_cplgn(){
        // 
    }

    // Dectivision function
    function deactivate_bookly_invoice_cplgn(){
        // Nothing For Now
    }

    // Admin Enqueue Scripts
    add_action('admin_enqueue_scripts',function(){
        wp_register_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '', 'all' );
        wp_register_style( BKLY_NAME, BKLY_URL.'admin/css/bookly-invoice.css', array(), microtime(), 'all' );

        wp_register_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array(), 
        '', true );
        wp_register_script( 'jspdf', BKLY_URL.'admin/js/jspdf.js', array(), 
        '', true );
        wp_register_script( 'html2canvas', BKLY_URL.'admin/js/html2canvas.js', array(), 
        '', true );
        wp_register_script( BKLY_NAME, BKLY_URL.'admin/js/bookly-invoice.js', array(), 
        microtime(), true );
        wp_localize_script( BKLY_NAME, 'admin_ajax_action', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ) );
    });

    // Register Menu
    add_action('admin_menu', function(){
        add_menu_page( 'bookly_invoice', 'bookly_invoice', 'manage_options', 'bookly_invoice', 'bookly_invoice_menupage_display', 'dashicons-welcome-widgets-menus', 45 );
    });


    // Get payments information
    add_action("wp_ajax_save_admin_invoce_info", "save_admin_invoce_info");
    add_action("wp_ajax_nopriv_save_admin_invoce_info", "save_admin_invoce_info");
    function save_admin_invoce_info(){
        global $current_user;
        if(isset($_POST['ceoinp']) || isset($_POST['phoneinp']) || isset($_POST['emlinp'])){
            $name = sanitize_text_field( $_POST['ceoinp'] );
            $phoneinp = sanitize_text_field( $_POST['phoneinp'] );
            $emlinp = sanitize_email( $_POST['emlinp'] );

            $company = sanitize_text_field( $_POST['company'] );
            $vatin = sanitize_text_field( $_POST['vatin'] );
            $address = sanitize_text_field( $_POST['address'] );
            $sign = sanitize_text_field( $_POST['sign'] );

            if(!empty($name)){
                if(get_user_meta( $current_user->ID, 'admin_invoce_name_info',true )){
                    update_user_meta($current_user->ID, 'admin_invoce_name_info', $name);
                }else{
                    add_user_meta($current_user->ID, 'admin_invoce_name_info', $name);
                }
            }
            if(!empty($phoneinp)){
                if(get_user_meta( $current_user->ID, 'admin_invoce_phoneinp_info',true )){
                    update_user_meta($current_user->ID, 'admin_invoce_phoneinp_info', $phoneinp);
                }else{
                    add_user_meta($current_user->ID, 'admin_invoce_phoneinp_info', $phoneinp);
                }
            }
            if(!empty($emlinp)){
                if(get_user_meta( $current_user->ID, 'admin_invoce_emlinp_info',true )){
                    update_user_meta($current_user->ID, 'admin_invoce_emlinp_info', $emlinp);
                }else{
                    add_user_meta($current_user->ID, 'admin_invoce_emlinp_info', $emlinp);
                }
            }


            if(!empty($company)){
                if(get_user_meta( $current_user->ID, 'admin_invoce_company_info',true )){
                    update_user_meta($current_user->ID, 'admin_invoce_company_info', $company);
                }else{
                    add_user_meta($current_user->ID, 'admin_invoce_company_info', $company);
                }
            }
            if(!empty($vatin)){
                if(get_user_meta( $current_user->ID, 'admin_invoce_vatin_info',true )){
                    update_user_meta($current_user->ID, 'admin_invoce_vatin_info', $vatin);
                }else{
                    add_user_meta($current_user->ID, 'admin_invoce_vatin_info', $vatin);
                }
            }
            if(!empty($address)){
                if(get_user_meta( $current_user->ID, 'admin_invoce_address_info',true )){
                    update_user_meta($current_user->ID, 'admin_invoce_address_info', $address);
                }else{
                    add_user_meta($current_user->ID, 'admin_invoce_address_info', $address);
                }
            }
            if(!empty($sign)){
                if(get_user_meta( $current_user->ID, 'admin_invoce_sign_info',true )){
                    update_user_meta($current_user->ID, 'admin_invoce_sign_info', $sign);
                }else{
                    add_user_meta($current_user->ID, 'admin_invoce_sign_info', $sign);
                }
            }
            die;
        }
        die;
    }


    // Get payments information
    add_action("wp_ajax_get_invoice_data", "get_invoice_data");
    add_action("wp_ajax_nopriv_get_invoice_data", "get_invoice_data");
    function get_invoice_data(){
        if(isset($_GET['user_id'])){
            global $wpdb,$current_user;
            $user_id = $_GET['user_id'];
            $user_id = intval($user_id);

            // How many appoints
            $payments = $wpdb->get_results("SELECT bca.*,bp.*,bc.* FROM {$wpdb->prefix}bookly_customer_appointments bca, {$wpdb->prefix}bookly_payments bp, {$wpdb->prefix}bookly_customers bc WHERE bca.customer_id = $user_id AND bca.customer_id = bc.id AND bca.payment_id = bp.id");
            
            
            if(!empty($payments) && !is_wp_error( $wpdb )){
                $payment_details = json_decode($payments[0]->details,true);

                $output = '';
                $output .= '<div id="wrapper">';
                $output .= '<div class="clearfix" id="header_section">';
                $output .= '<h1>Auto Rijschool Cakmak Faktuur</h1>';

                $output .= '<div id="customer_info" class="clearfix">';
                // name address VAT nr, Email tel
                $output .= '<div class="edit_inp">';
                $output .= '<input type="text" placeholder="Company" id="company" value="'.get_user_meta( $current_user->ID, 'admin_invoce_company_info',true ).'">';
                $output .= '<input type="text" placeholder="Name" id="ceoinp" value="'.get_user_meta( $current_user->ID, 'admin_invoce_name_info',true ).'">';
                $output .= '<input type="text" placeholder="Tel" id="phoneinp" value="'.get_user_meta( $current_user->ID, 'admin_invoce_phoneinp_info',true ).'">';
                $output .= '<input type="email" placeholder="Email" id="emlinp" value="'.get_user_meta( $current_user->ID, 'admin_invoce_emlinp_info',true ).'">';
                $output .= '<input type="text" placeholder="VAT Number" id="vatin" value="'.get_user_meta( $current_user->ID, 'admin_invoce_vatin_info',true ).'">';
                $output .= '<input type="text" placeholder="Adress" id="address" value="'.get_user_meta( $current_user->ID, 'admin_invoce_address_info',true ).'">';
                $output .= '<input type="text" placeholder="Signeture" id="sign" value="'.get_user_meta( $current_user->ID, 'admin_invoce_sign_info',true ).'">';
                $output .= '</div>';

                $output .= '<div class="companytext">'.(get_user_meta( $current_user->ID, 'admin_invoce_company_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_company_info',true ):'Company').'</div>';

                $output .= '<div class="ceotext">'.(get_user_meta( $current_user->ID, 'admin_invoce_name_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_name_info',true ):'My Name').'</div>';

                $output .= '<div class="phonetext">'.(get_user_meta( $current_user->ID, 'admin_invoce_phoneinp_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_phoneinp_info',true ):'+0000000000').'</div>';

                $output .= '<div><a href="mailto:'.(get_user_meta( $current_user->ID, 'admin_invoce_emlinp_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_emlinp_info',true ):'').'" class="mailtext">'.(get_user_meta( $current_user->ID, 'admin_invoce_emlinp_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_emlinp_info',true ):'admin@example.com').'</a></div>';

                $output .= '<div class="vatintext">'.(get_user_meta( $current_user->ID, 'admin_invoce_vatin_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_vatin_info',true ):'VATIN').'</div>';

                $output .= '<div class="addresstext">'.(get_user_meta( $current_user->ID, 'admin_invoce_address_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_address_info',true ):'Address').'</div>';

                $output .= '<div class="bklyeditbtn"><button class="editmood">Edit</button></div>';
                $output .= '</div>';

                $output .= '<div id="informations">';
                $output .= '<div><span>CUSTOMER</span> '.__($payments[0]->full_name, BKLY_NAME).'</div>';
                $output .= '<div><span>ADDRESS</span>';

                $address = '';
                if(!empty($payments[0]->state)){
                    $address .= __($payments[0]->state.', ', BKLY_NAME);
                }
                if(!empty($payments[0]->city)){
                    $address .= __($payments[0]->city.', ', BKLY_NAME);
                }
                if(!empty($payments[0]->street)){
                    $address .= __($payments[0]->street.', ', BKLY_NAME);
                }
                if(!empty($payments[0]->postcode)){
                    $address .= __($payments[0]->postcode.', ', BKLY_NAME);
                }
                if(!empty($payments[0]->country)){
                    $address .= __($payments[0]->country, BKLY_NAME);
                }

                $address = rtrim($address,', ');
                $output .= $address;

                $output .= '</div>';
                $output .= '<div><span>EMAIL</span> <a href="mailto:'.__($payments[0]->email, BKLY_NAME).'">'.__($payments[0]->email, BKLY_NAME).'</a></div>';
                $output .= '<div><span>PHONE</span>'.__($payments[0]->phone, BKLY_NAME).'</div>';
                $output .= '</div>';

                $output .= '</div>';
                $output .= '<main>';
                $output .= '<table>';
                $output .= '<thead>';
                $output .= '<tr>';
                $output .= '<th class="service">Maintenance</th>';
                $output .= '<th class="desc">Pay Date</th>';
                $output .= '<th>Hours</th>';
                $output .= '<th>Service Price</th>';
                $output .= '<th class="desc">Adjustments</th>';
                $output .= '<th class="amount">Adjust-Amount</th>';
                $output .= '<th class="paid">Paid</th>';
                $output .= '<th>Costs</th>';
                $output .= '</tr>';
                $output .= '</thead>';
                $output .= '<tbody>';

                $total_amount = 0;
                $paid_amount = 0;

                $invoice_details = [];

                foreach($payments as $pay){
                    $_details = json_decode($pay->details,true);
                    $total_amount += $pay->total;
                    $paid_amount += $pay->paid;

                    // Using for details
                    $shortname = explode(' ', $_details['items'][0]['service_name'])[0];
                    $invoice_details['items'][] = $shortname;
                    $invoice_details['price'][$shortname.'_price'] += $_details['items'][0]['service_price'];
                    $invoice_details['durations'][$shortname.'_durations'] += $_details['items'][0]['duration'];
                    $invoice_details['paid'][$shortname.'_paids'] += $_details['adjustments'][0]['amount'];
                    

                    $output .= '<tr>';
                    $output .= '<td class="service">'.__($_details['items'][0]['service_name'], BKLY_NAME).'</td>';$output .= '<td class="date">'.__(date('Y/m/d', strtotime($pay->created_at)), BKLY_NAME).'</td>';
                    $output .= '<td class="hour">'.floor($_details['items'][0]['duration'] / (60 * 60)).(floor($_details['items'][0]['duration'] / (60 * 60)) > 1? ' Hours':' Hour').' </td>';
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

                $output .= '</tbody>';
                $output .= '</table>';

                // Details table
                $output .= '<div id="bottom_tables">';
                $output .= '<table id="detailstable">';
                $output .= '<tbody>';
                if(!empty($invoice_details)){
                    foreach(($invoice_details['durations'])  as $key => $duration){
                        $output .= '<tr colspan="3" class="bcolumn">';
                        $output .= '<td class="">';

                        $serviceItem = explode('_',$key)[0];
                        $durations = $duration / (60 * 60);
                        $originalprice = $invoice_details['price'][$serviceItem.'_price'];
                        $paidamounts = $invoice_details['paid'][$serviceItem.'_paids'];

                        $output .= 'Total '.strtolower($serviceItem).' '.$durations.' '.($durations > 1? 'hours ': 'hour ' ).$originalprice. ' eur.';

                        $output .= '</td>';
                        $output .= '</tr>';

                        if($paidamounts){
                            $output .= '<tr>';
                            $output .= '<td>';
                            $output .= 'Total '.strtolower($serviceItem).' paid '.$paidamounts.' eur.';
                            $output .= '</td>';
                            $output .= '</tr>';
                        }

                    }
                }
                $output .= '</tbody>';
                $output .= '</table>';

                // Result table
                $output .= '<table id="resultable">';
                $output .= '<tbody>';
                $output .= '<tr class="bcolumn">';
                $output .= '<td colspan="7">TOTAL</td>';
                $output .= '<td class="total">'.__($total_amount, BKLY_NAME).'</td>';
                $output .= '</tr>';
                $output .= '<tr class="bcolumn">';
                $output .= '<td colspan="7">PAID</td>';
                $output .= '<td class="due">'.__($paid_amount, BKLY_NAME).'</td>';
                $output .= '</tr>';
                $output .= '<tr class="bcolumn">';
                $output .= '<td colspan="7">Credit</td>';
                $output .= '<td class="due">'.__($total_amount-$paid_amount, BKLY_NAME).'</td>';
                $output .= '</tr>';
                $output .= '</tbody>';
                $output .= '</table>';
                // Result table
                $output .= '</div>';

                $output .= '</main>';
                $output .= '<div class="invoice__footer_content">';
                $output .= '<div class="signeture_text">';
                $output .= '<strong><i>'.(get_user_meta( $current_user->ID, 'admin_invoce_sign_info',true )?get_user_meta( $current_user->ID, 'admin_invoce_sign_info',true ):'Signeture').'</i></strong>';
                $output .= '</div>';
                $output .= '<div class="create_date">00/00/00</div>';
                $output .= '</div>';

                $output .= '</div>';
                $output .= '<button id="pdfdownload" onclick=CreatePDFfromHTML()>Download as a PDF</button>';
                echo json_encode($output);
                die;
            }

            die;
        }
        die;
    }

    // Menu callback funnction
    function bookly_invoice_menupage_display(){
        if(function_exists('bookly_loader')){
            wp_enqueue_style(BKLY_NAME);
            wp_enqueue_style('select2');
            wp_enqueue_script('select2');
            wp_enqueue_script('jspdf');
            wp_enqueue_script('html2canvas');
            wp_enqueue_script(BKLY_NAME);
            ?>
            <?php
            global $wpdb;
            $bookly_cappointments = $wpdb->get_results("SELECT bca.*,bp.*,bc.id AS ID,bc.* FROM {$wpdb->prefix}bookly_customer_appointments bca, {$wpdb->prefix}bookly_payments bp, {$wpdb->prefix}bookly_customers bc WHERE bca.customer_id = bc.id AND bca.payment_id = bp.id GROUP BY bc.full_name");

            require_once 'invoice-component.php';
        }
    }
}
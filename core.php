<?php

/*
Plugin Name: Saba
Plugin URI: http://wordpress.org/plugins/r6f
Description: saba plugin
Author: amir arbabi
Version: 1.0.1
Author URI: http://r6f.ir
*/




defined("ABSPATH" || exit());



class Core{

    private  static  $_instance = null;
    const MINI_PHP_VER = '7.2';

    public static function instance()
    {
        if (is_null(self::$_instance)){
            self::$_instance = new self();
        }
    }


    public function __construct()
    {

        if (version_compare(PHP_VERSION, self::MINI_PHP_VER, '<')){
            do_action('admin_notices', [$this, 'admin_php_notice']);
            return;
        }

        $this->constans();
        $this->init();
    }


    public function constans()
    {

        if (!function_exists('get_plugin_data')){
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        define("SBA_BASE_FILE", __FILE__);
        define("SBA_PATH", trailingslashit(plugin_dir_path(SBA_BASE_FILE)));
        define("SBA_URL", trailingslashit(plugin_dir_url(SBA_BASE_FILE)));
        define("SBA_ADMIN_ASSETS", trailingslashit(SBA_URL . 'assets/admin'));
        define("SBA_FRONT_ASSETS", trailingslashit(SBA_URL . 'assets/front'));
        define("SBA_INC_PATH", trailingslashit(SBA_PATH . 'inc'));
        define("SBA_VIEW_PATH", trailingslashit(SBA_PATH . 'views'));

        $sba_plugin_data = get_plugin_data(SBA_BASE_FILE);
        define('SBA_VER', $sba_plugin_data['Version']);
    }



    public function init()
    {
       require __DIR__ . '/vendor/autoload.php';
       require __DIR__ . '/inc/functions.php';

        register_activation_hook(SBA_BASE_FILE, [$this, 'active']);

        add_action('woocommerce_order_status_completed', [$this, 'jende'], 20, 2);

        add_action('woocommerce_thankyou', [$this, 'update_sba'], 10, 1);



        if (is_admin()){
            new SBA_Menu();
        }

       new SBA_API();
    }


    public function update_sba($order_id)
    {
        session_start();

        $request_id = md5('sbaR6f'. $order_id);



        if (isset($_SESSION['processed_requests'][$request_id])){
            return;
        }

        $_SESSION['processed_requests'][$request_id] = true;
        $api = new SBA_API();

        $order = wc_get_order($order_id);



        $user = [
            'id' => $order->data['customer_id'],
            'date' => date("Y-m-d"),
            'name' => $order->data['billing']['first_name'] .' '. $order->data['billing']['first_name'],
            'phone' => $order->data['billing']['phone'],
            'address' => $order->data['billing']['address_1']
        ];


        $res1 = $api->set_user($user);

        //if(isset($res1->data['Message']) || (int)$res1->data < 1){

        //}



        foreach ($order->get_items() as $item_id => $item ) {

            // Get an instance of corresponding the WC_Product object
            $product = $item->get_product();

            $offer = isset($product->get_sale_price) ? round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100 , 2) : 0 ;

            $p = [
                'id' => $product->get_sku(),
                'date' => date("Y-m-d"), // $order->data
                'count' => $item->get_quantity(),
                'customer_id' => $order->data['customer_id'],
                'yad' => '', //note
                'price' => (string)$product->get_price(),
                'box' => 1, // tedad to har jabe
                'idtot' => $order_id, // shomare factor
                'ptk' => $offer // darsad takhfif = 0 - 100
            ];

            $res2 = $api->set_product($p);
            if(isset($res2->data['Message']) || (int)$res2->data < 1){
                SBA_DB::insert_product($p);
            }
        }





    }




    public function active()
    {
        SBA_DB::create_table();
    }

    public function deactive()
    {

    }

    public function admin_php_notice()
    {
        ?>
        <div class="notice notice-warning">
            <p>نیازمند Php نسخه بالاتر است افزونه تیکت پشتیبان</p>
        </div>
        <?php
    }
}

Core::instance();

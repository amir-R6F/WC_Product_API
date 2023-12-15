<?php
defined("ABSPATH" || exit());
class SBA_DB{
    public static function create_table(){
        global $wpdb;


        $users = $wpdb->prefix . 'sba_users';
        $products = $wpdb->prefix . 'sba_products';

        $charset = $wpdb->get_charset_collate();

        $users_sql = "CREATE TABLE IF NOT EXISTS `". $users . "` (
                `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) ,
                `phone` varchar(255) ,
                `address` TEXT ,
                `customer_id` int ,
                `create_date` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`ID`))
                ENGINE=InnoDB " . $charset . ";";

        $products_sql = "CREATE TABLE IF NOT EXISTS `". $products . "` (
                `ID` bigint(20) NOT NULL AUTO_INCREMENT,
                `sku` INT ,
                `count` INT ,
                `customer` INT ,
                `price` varchar(255) ,
                `note` TEXT ,
                `box` int ,
                `factor_number` INT ,
                `offer` INT ,
                `create_date` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`ID`))
                ENGINE=InnoDB " . $charset . ";";


        if (!function_exists('dbDelta')){
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        }

        dbDelta($users_sql);
        dbDelta($products_sql);


    }

    public static function insert_product($data)
    {
        global $wpdb;

        $products = $wpdb->prefix . 'sba_products';

        $wpdb->insert($products,
            [
                'sku' => $data['id'],
                'count' => $data['count'],
                'customer' => $data['customer_id'],
                'price' => $data['price'],
                'note' => $data['yad'],
                'box' => $data['box'],
                'factor_number' => $data['idtot'] ,
                'offer' => $data['ptk'],
            ],
            ['%d', '%d', '%d', '%s', '%s', '%d', '%d', '%d']
        );
        $insert_id = $wpdb->insert_id;
        return ['product_id' => $insert_id];
    }

    public static function list()
    {
        global $wpdb;
        $products = $wpdb->prefix . 'sba_products';
        return $wpdb->get_results("SELECT * FROM $products ORDER BY `create_date` DESC");
    }
}
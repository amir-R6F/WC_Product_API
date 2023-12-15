<?php

defined("ABSPATH" || exit());

class SBA_Menu extends Base_Menu {

    public function __construct()
    {
        $this->page_title = 'پنل تنظیمات صبا';
        $this->menu_title = 'تنظیمات صبا';
        $this->menu_slug = 'saba-setting';

        $this->has_sub_menu = true;
        $this->sub_items= [
            "settings" => [
                'page_title'=> 'تنظیمات',
                'menu_title'=> 'تنظیمات',
                'menu_slug'=> 'saba-settings',
                'callback' => 'setting'
            ],
            "fail" => [
                'page_title'=> 'سفارشات ثبت نشده',
                'menu_title'=> 'ثبت نشده ها',
                'menu_slug'=> 'saba-orders',
                'callback' => 'orders'
            ],
        ];

        parent::__construct();
    }

    public function page()
    {

    }

    public function setting()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['submit'])){
                $settings = [
                    'sba-user' => $_POST['saba-user'],
                    'sba-pass' => $_POST['saba-password'],
                    'sba-server' => $_POST['saba-server']
                ];

                update_option('saba_settings', $settings);

            }elseif(isset($_POST['manual'])){

                (new SBA_API())->all_products();

                include SBA_VIEW_PATH . 'admin/panel.php';
            }
        }

        include SBA_VIEW_PATH . 'admin/panel.php';
    }

    public function orders()
    {
        $orders = SBA_DB::list();
        include SBA_VIEW_PATH . 'admin/orders.php';
    }




}

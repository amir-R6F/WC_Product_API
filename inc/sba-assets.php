<?php

defined("ABSPATH" || exit());

class SBA_Assets{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'front_assets']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
    }

    public function admin_assets()
    {
        wp_enqueue_script('tkt-main', TKT_ADMIN_ASSETS . 'js/main.js' , ['jquery'], TKT_VER, true);

        wp_localize_script('tkt-main', 'TKT_DATA', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);

    }

    public function front_assets()
    {

    }
}
<?php

use Automattic\WooCommerce\Client;

class SBA_API
{
    private $sba_user;
    private $sba_token;




    public function __construct()
    {

        $this->sba_user = sba_settings('sba-user');
        $this->sba_token = sba_settings('sba-pass');
        $this->server_address = sba_settings('sba-server');

        // Register API routes
        add_action('rest_api_init', [$this, 'saba_register_routes']);

    }

    // Register Saba Routes
    public function saba_register_routes()
    {
        register_rest_route('saba/v1', '/products', [
            'methods' => 'GET',
            'callback' => [$this, 'all_products'],
        ]);

    }


    // Saba API
    public function all_products()
    {

        $url = $this->server_address . '/getprc';
        $user = $this->sba_user;
        $token = $this->sba_token;

        $queryString = http_build_query([
            'user' => $user,
            'token' => $token
        ]);

        $url .= '?' . $queryString;

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            var_dump('ارتباط برقرار نشد');
        } else {
            $decodedResponse = json_decode($response, true);
            $results = json_decode($decodedResponse);

            if (is_null($results)){
                return rest_ensure_response(['message' => 'done for now.']);
            }

            foreach ($results as $res) {
                try {


                    if ($res->rowv == 2) {
                        $this->createOrUpdate($res);
                    } elseif ($res->rowv == 1) {
                        $this->delete($res);
                    }


                } catch (Exception $e) {
                    $errors = array(
                        'status' => 401,
                        'message' => $e->getMessage(),
                        'id' => $res->id,
                        'last' => end($results)->id
                    );

                    update_option('last_product', $res->id - 1);
                    $this->set_flag();

                    $this->all_products();
                }
            }


            update_option('last_product', end($results)->id);

            $this->set_flag();

            $this->all_products();
        }

        curl_close($curl);

    }

    public function set_flag()
    {

        $url = $this->server_address . '/ProductEditing';
        $data = [
            'id' => get_option('last_product'),
            'user' => $this->sba_user,
            'token' => $this->sba_token
        ];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($curl);

        if ($response == false) {
            $eror = curl_error($curl);
            return rest_ensure_response(['error' => "curl error: $eror"]);
        } else {
            $decoderes = json_decode($response, true);
            return rest_ensure_response($decoderes);
        }

        curl_close($curl);

    }

    public function set_user($user)
    {
        $url = $this->server_address . '/PostWebCastStore';
        $data = [
            'user' => $this->sba_user,
            'token' => $this->sba_token,
            "idcast" => $user['id'],
            "dat" => $user['date'],
            "nam" => $user['name'],
            "tel" => $user['phone'],
            "adr" => $user['address']
        ];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($curl);

        if ($response == false) {
            $eror = curl_error($curl);
            return rest_ensure_response(['error' => "curl error: $eror"]);
        } else {
            $decoderes = json_decode($response, true);
            return rest_ensure_response($decoderes);
        }

        curl_close($curl);
    }

    public function set_product($product)
    {
        $url = $this->server_address . '/PostPrctmpWebcast';
        $data = [
            'user' => $this->sba_user,
            'token' => $this->sba_token,
            "cod" => $product['id'],
            "numb" => $product['count'],
            "idcast" => $product['customer_id'],
            "yad" => $product['yad'],
            "price" => $product['price'],
            "box" => $product['box'],
            "dat" => $product['date'],
            "idtot" => $product['idtot'],
            "ptk" => $product['ptk']
        ];

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($curl);

        if ($response == false) {
            $eror = curl_error($curl);
            return rest_ensure_response(['error' => "curl error: $eror"]);
        } else {
            $decoderes = json_decode($response, true);
            return rest_ensure_response($decoderes);
        }

        curl_close($curl);
    }


    // My API
    public function createOrUpdate($data)
    {

        $p = wc_get_product_id_by_sku($data->id);

        if ($p == 0){
            $product = new WC_Product_Simple();
            $product->set_name($data->nam);
            $product->set_regular_price($data->price);
            $product->set_sku($data->id);

            if (isset($data->numb)){
                $product->set_manage_stock(true);
                $product->set_stock_quantity(intval($data->numb));
            }

            $product->save();
        }else{
            $product = new WC_Product_Simple($p);
            $product->set_name($data->nam);
            $product->set_regular_price($data->price);
            $product->set_sku($data->id);

            if (isset($data->numb)){
                $product->set_manage_stock(true);
                $product->set_stock_quantity(intval($data->numb));
            }

            $product->save();
        }



    }

    public function delete($data)
    {

        $p = wc_get_product_id_by_sku($data->id);

        $product = new WC_Product_Simple($p);
        $product->delete(true);
    }




}

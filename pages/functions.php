<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(__DIR__ . '/../../../'));
}

class SkynetWidget
{

    /**
     * Sends user and site data to Skynet API.
     */
    public static function getWidgetInfo($req = null)
    {

        $now = new DateTime('now', new DateTimeZone('UTC'));
        $dateTime = $now->format('Y-m-d\TH:i:sO');

        $post_fields = [
            'name'             => $req['nickname'],
            'company_name'     => '',
            'website'          => base64_encode($_SERVER['HTTP_HOST']),
            'package_type'     => 'free-widget',
            'start_date'       => $dateTime,
            'end_date'         => '',
            'price'            => '',
            'discount_price'   => '0',
            'platform'         => 'Nameless MC',
            'api_key'          => '',
            'is_trial_period'  => '',
            'is_free_widget'   => '1',
            'bill_address'     => '',
            'country'          => '',
            'state'            => '',
            'city'             => '',
            'post_code'        => '',
            'transaction_id'   => '',
            'subscr_id'        => '',
            'payment_source'   => ''
        ];
        

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ada.skynettechnologies.us/api/add-user-domain',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_fields,
            CURLOPT_SSL_VERIFYPEER => false // ⚠️ Not for production
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    /**
     * Fetch widget settings from Skynet.
     */
    public static function fetchWidgetSettings($req)
    {

        $raw_keys = array_keys($req);
        $raw_json = $raw_keys[0]; // Get the first (and only) key

        $dataPre = json_decode($raw_json, true); // Decode it to PHP array
        $data = $dataPre['user_data']; // Decode it to PHP array


        // self::initNameless(); // Validate user
        $host = $_SERVER['HTTP_HOST'];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://ada.skynettechnologies.us/api/widget-settings',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['website_url' => $host],
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $res = json_decode($response, true);

        if (empty($res['Data'])) {
            self::getWidgetInfo($data); // Register first if not found
            sleep(1); // Wait for sync
            return self::fetchWidgetSettings($req); // Retry
        }

        return json_encode($res['Data']);
    }

    /**
     * Store a success message in session.
     */
    public static function settingsUpdated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['success'] = "Settings updated successfully!";
        return true;
    }
}

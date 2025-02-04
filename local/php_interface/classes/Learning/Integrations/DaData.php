<?php
namespace Learning\Integrations;
class DaData
{
    private $clean_url = "https://cleaner.dadata.ru/api/v1/clean";
    private $suggest_url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs";
    private $token = "116f3d8184beb82e2dde985a46cc589fdf77ec05";
    private $secret = "25748ede2718207b5cf9b9225b4ad61d7542065b";
    private $handle;

    public function __construct()
    {
        
    }

    /**
     * Initialize connection.
     */
    public function init()
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Token " . $this->token,
            "X-Secret: " . $this->secret,
        ));
        curl_setopt($this->handle, CURLOPT_POST, 1);
    }

    /**
     * Clean service.
     * See for details:
     *   - https://dadata.ru/api/clean/address
     *   - https://dadata.ru/api/clean/phone
     *   - https://dadata.ru/api/clean/passport
     *   - https://dadata.ru/api/clean/name
     * 
     * (!) This is a PAID service. Not included in free or other plans.
     */
    public function clean($type, $value)
    {
        $url = $this->clean_url . "/$type";
        $fields = array($value);
        return $this->executeRequest($url, $fields);
    }

    /**
     * Find by ID service.
     * See for details:
     *   - https://dadata.ru/api/find-party/
     *   - https://dadata.ru/api/find-bank/
     *   - https://dadata.ru/api/find-address/
     */
    public function findById($type, $fields)
    {
        $url = $this->suggest_url . "/findById/$type";
        return $this->executeRequest($url, $fields);
    }

    /**
     * Reverse geolocation service.
     * See https://dadata.ru/api/geolocate/ for details.
     */
    public function geolocate($lat, $lon, $count = 10, $radius_meters = 100)
    {
        $url = $this->suggest_url . "/geolocate/address";
        $fields = array(
            "lat" => $lat,
            "lon" => $lon,
            "count" => $count,
            "radius_meters" => $radius_meters
        );
        return $this->executeRequest($url, $fields);
    }

    /**
     * Detect city by IP service.
     * See https://dadata.ru/api/iplocate/ for details.
     */
    public function iplocate($ip)
    {
        $url = $this->suggest_url . "/iplocate/address";
        $fields = array(
            "ip" => $ip
        );
        return $this->executeRequest($url, $fields);
    }

    /**
     * Suggest service.
     * See for details:
     *   - https://dadata.ru/api/suggest/address
     *   - https://dadata.ru/api/suggest/party
     *   - https://dadata.ru/api/suggest/bank
     *   - https://dadata.ru/api/suggest/name
     *   - ...
     */
    public function suggest($type, $fields)
    {
        $url = $this->suggest_url . "/suggest/$type";
        return $this->executeRequest($url, $fields);
    }

    /**
     * Close connection.
     */
    public function close()
    {
        curl_close($this->handle);
    }

    private function executeRequest($url, $fields)
    {
        curl_setopt($this->handle, CURLOPT_URL, $url);
        if ($fields != null) {
            curl_setopt($this->handle, CURLOPT_POST, 1);
            curl_setopt($this->handle, CURLOPT_POSTFIELDS, json_encode($fields));
        } else {
            curl_setopt($this->handle, CURLOPT_POST, 0);
        }
        $result = $this->exec();
        $result = json_decode($result, true);
        return $result;
    }

    private function exec()
    {
        $result = curl_exec($this->handle);
        $info = curl_getinfo($this->handle);
        if ($info['http_code'] == 429) {
            throw new TooManyRequests();
        } elseif ($info['http_code'] != 200) {
            throw new Exception('Request failed with http code ' . $info['http_code'] . ': ' . $result);
        }
        return $result;
    }
}


// Метод init() следует вызвать один раз в начале,
// затем можно сколько угодно раз вызывать отдельные методы clean(), suggest() и т.п.
// и в конце следует один раз вызвать метод close().
//
// За счёт этого не создаются новые сетевые соединения на каждый запрос,
// а переиспользуется существующее.

// $token = "ВАШ_API_КЛЮЧ";
// $secret = "ВАШ_СЕКРЕТНЫЙ_КЛЮЧ";

// $dadata = new Dadata($token, $secret);
// $dadata->init();

// Найти компанию по ИНН
// $fields = array("query" => "7707083893", "count" => 5);
// $result = $dadata->suggest("party", $fields);
// print_r($result);

// $dadata->close();
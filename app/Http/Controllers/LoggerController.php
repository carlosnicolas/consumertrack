<?php

namespace App\Http\Controllers;

class LoggerController extends Controller
{
    private $client;
    private $geolocation;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->client = $this->getClient();
        $this->geolocation = $this->geolocate();
    }

    public function index() {
        $this->log("Client access.");
    }

    public function geolocate() {
        $data["country"] = null;
        $data["city"] = null;
        $data["ip"] = null;
        
        // Get IP

        // connect to API

        return $data;
    }

    public function getClient() {
        $data["device"] = null;
        $data["browser"] = null;

        // get client data

        return $data;
    }

    public function store($action = "") {
        try {
      
        } catch(Exception $e) {
          echo "Cannot access to log file. ".$e->textError();
        }
    }

    public function log($action) {
        ["device" => $device, "browser" => $browser] = $this->getClient();
        ["ip" => $ip, "country" => $country, "city" => $city] = $this->geolocate();
        $dateTime = date('m/d/Y h:i:s a', time());
        $log = "${dateTime} ${ip} ${country} ${city} - ${device} ${browser} ${action}";
        echo $log;
        //$this->store($log);
    }
}

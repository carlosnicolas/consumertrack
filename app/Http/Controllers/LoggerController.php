<?php

namespace App\Http\Controllers;

use \GeoIp2\WebService\Client;
use Detection\MobileDetect;

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
        $this->log("Client access");
    }

    public function getClientIP()
    {
        $m = null;
        try {
            $externalContent = @file_get_contents('http://checkip.dyndns.com/');
            preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
        return $m[1] ?? null;
    }

    public function getBrowserData()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $name = 'Unknown';
        $platform = 'Unknown';
        $ub = "Unknown";
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
            $name = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent)) {
            $name = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent)) {
            $name = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent)) {
            $name = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent)) {
            $name = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent)) {
            $name = 'Netscape';
            $ub = "Netscape";
        }
        return (object) [ "name" => $name, "ub" => $ub ];
    }

    public function getDeviceType()
    {
        $detect = new MobileDetect;
        if ( $detect->isMobile() )
            return "Mobile";
        if( $detect->isTablet() )
            return "Tablet";
        return "Desktop";
    }

    public function getClient() {
        return (object) [
            "ip" => $this->getClientIP(),
            "device" => $this->getDeviceType(),
            "browser" => $this->getBrowserData()
        ];
    }

    public function geolocate() {
        $data = (object) [
            "country" => "Unknown",
            "state" => "Unknown"
        ];
        
        // Geolocation
        if($this->client->ip){
            $client = new Client(env('GEO_CLIENT', ""), env('GEO_KEY', ""),['en'],['host' => 'geolite.info']);
            $record = $client->city($this->client->ip);
    
            $data->country = $record->country->name;
            $data->state = $record->subdivisions[0]->name;
        }
        return $data;
    }

    public function store($action = "") {
        try {
            $handle = fopen(public_path("log.csv"), "a");
            echo "Action: ".$action; 
            fputcsv($handle, [$action]);
            fclose($handle);
            exit;
        } catch(Exception $e) {
          echo "Cannot access to log file. ".$e->getMessage(); exit;
        }
    }

    public function log($action) {
        $dateTime = date('m/d/Y H:i:s', time());
        $log = "{$dateTime} - {$this->client->ip} - {$action} [{$this->geolocation->country} {$this->geolocation->state} - {$this->client->device} / {$this->client->browser->ub} / {$this->client->browser->name}]";
        $this->store($log);
        echo $log;
    }
}

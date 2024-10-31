<?php
namespace AlAdhanApi;

use AlAdhanApi\Endpoints;
use AlAdhanApi\Methods;

/**
 * Class Client
 * @package AlAdhanApi
 */

class Client {

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var
     */
    protected $latitude;

    /**
     * @var
     */
    protected $longitude;

    /**
     * @var
     */
    protected $location;

    /**
     * @var
     */
    protected $timezone;

    /**
     * @var
     */
    protected $method;

    /**
     * @var
     */
    protected $timestamp;

    /**
     * @var
     */
    protected $month;

    /**
     * @var
     */
    protected $year;
    
    /**
     * @var
     */
    protected $city;
    
    /**
     * @var
     */
    protected $country;
    
    /**
     * @var
     */
    protected $address;
    
    /**
     * @var
     */
    protected $state;
    
    /**
     * @var
     */
    protected $latitudeAdjustmentMethod;
    
    /**
     * @var
     */
    protected $school;
    
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'defaults' => [
                'headers' =>
                    [
                        'User-Agent' => 'AlAdhanWordPressPlugin_1.0'
                    ],
            ]
            ]);
    }
    
    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }
    
    public function setLongitude($longitude) {
        $this->longitude = $longitude;
        
    }
    
    public function setLocation($location) {
        $this->location = $location;
        
    }
    
    public function setTimezone($timezone) {
        $this->timezone = $timezone;
        
    }
    
    public function setMethod($method) {
        $this->method = $method;
    }
    
    public function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }
    
    public function setMonth($month) {
        $this->month = $month;
    }
    
    public function setYear($year) {
        $this->year = $year;
    }
    
    public function setCity($city) {
        $this->city = $city;
    }
    
    public function setCountry($country) {
        $this->country = $country;
    }
    
    public function setState($state) {
        $this->state = $state;
    }
    
    public function setAddress($address) {
        $this->address = $address;
    }
    
    public function setLatitudeAdjustmentMethod($method) {
        $this->latitudeAdjustmentMethod = (int) $method;
    }
    
    public function setSchool($school) {
        $this->school = (int) $school;
    }
    
    protected function connect($endpoint, array $data)
    {
        try {
            return $this->client->get(
                $endpoint,
                [
                    'headers' => ['Content-Type' => 'application/text'],
                    'query' => $data,
                    'verify' => false
                ]
            );
        } catch(\Exception $e) {
            // Write to PHP log.
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
                              
                              
    
    
}

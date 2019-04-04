<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 03.04.19
 * Time: 14:47
 */

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Psr\Log\LoggerInterface;


class Openweathermap
{
    private $params;

    private $logger;

    private $debug = true;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        $this->params = $params;
        $this->logger = $logger;
    }

    public function getKey()
    {
        return $this->params->get('openweathermap_key');
    }

    public function apiCall($latitude, $longitude)
    {
        $cache = new FilesystemCache();

        if ($this->debug === true AND $cache->has('openweathermap_response')) {
            return $cache->get('openweathermap_response');
        }

        $url = 'https://api.openweathermap.org/data/2.5/weather?appid='.$this->getKey();
        $url .= '&units=metric';
        $url .= '&lat='.$latitude.'&lon='.$longitude;

        $this->logger->info('URL: ' . $url);

        $curl = curl_init();
        $headers = array();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $json = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($json, true);

        if ($this->debug === true) {
            $cache->set('openweathermap_response', $data);
        }

        return $data;
    }
}
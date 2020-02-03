<?php
/**
 * User: tomek
 * Date: 03.04.19
 * Time: 14:47
 */

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
//use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Psr\Log\LoggerInterface;

class Openweathermap
{
    private $params;

    private $logger;

    private $debug = false;
    
    private $useCache = true;

    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        $this->params = $params;
        $this->logger = $logger;
    }

    private function getKey()
    {
        return $this->params->get('openweathermap_key');
    }
    
    private function performRequest($url)
    {
        if ($this->useCache === true) {
            $cacheSlug = substr($url, strrpos($url, '/')+1);
            $cache = new FilesystemAdapter();
            $cacheitem = $cache->getItem($cacheSlug);
            if ($cacheitem->isHit()) {
                return $cacheitem->get();
            }
        }
        
        $json = $this->curlRequest($url);
        $data = json_decode($json, true);
        if (!is_array($data) or  empty($data)) {
            throw new \Exception('Could not retrieve weather api data');
        }
        if ($this->useCache === true) {
            $cacheitem->set($data);
            $cacheitem->expiresAfter(60*30);
            $cache->save($cacheitem);
        }
        
        return $data;
    }
    
    /**
     * Curl request
     *
     * @param string $url
     * @throws \Exception
     * @return array
     */
    private function curlRequest($url)
    {
        try {
            $curl = curl_init();
            $headers = array();
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            $data = curl_exec($curl);
            curl_close($curl);
            return $data;
        } catch (Exception $e) {
            throw new \Exception('Could not retrieve url');
        }
        return [];
    }
    
    /**
     * Get by city
     * @param string $city
     * @param string $code
     * @return array
     */
    public function getByCity($city, $code = '')
    {
        $query = $city;
        if (!empty($code)) {
            $query .= ',' . $code;
        }
            
        $url = $this->getUrl(['q'=>$query], 'forecast');
        $this->logger->info('URL '.__METHOD__.': ' . $url);
        $data = $this->performRequest($url);
        return $data;
    }
    
    /**
     * Get by coordinates
     *
     * @param string $latitude
     * @param string $longitude
     * @return array
     */
    public function getCurrentByCoordinate($latitude, $longitude)
    {
        $param = ['lat'=>$latitude, 'lon'=>$longitude];
        $url = $this->getUrl($param);
        $this->logger->info('URL: ' . $url);
        $data = $this->performRequest($url);
        return $data;
    }
    
    
    /**
     * Get api url
     *
     * @param array $param
     * @return string
     */
    public function getUrl(array $param, $scope = 'weather')
    {
        if (empty($param)) {
            throw new \Exception('Parms must not be empty');
        }
        
        $url = 'https://api.openweathermap.org/data/2.5/'.$scope;
        $url .= '?appid='.$this->getKey();
        $url .= '&units=metric';
        $url .= '&' . http_build_query($param);
        return $url;
    }
}

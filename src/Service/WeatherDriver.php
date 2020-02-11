<?php

//var_dump();
namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class WeatherDriver
{
    protected $params;
    
    protected $logger;
    
    protected $debug = false;
    
    protected $useCache = true;
    
    public function __construct(ParameterBagInterface $params, LoggerInterface $logger)
    {
        $this->params = $params;
        $this->logger = $logger;
    }
    
    protected function getKey()
    {
        return $this->params->get('openweathermap_key');
    }
    
    protected function performRequest($url)
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
        } catch (\Exception $e) {
            throw new \Exception('Could not retrieve url');
        }
        return [];
    }
    
    
    /**
     * Browser language
     * @param string $default
     * @return string
     */
    protected function geBrowserLanguage($default='en')
    {
        if ( isset( $_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ] ) ) {
            
            $langs = explode( ',', $_SERVER['HTTP_ACCEPT_LANGUAGE'] );
            if (isset($langs[0][0])) {
                return substr( $langs[0], 0, 2 );   
            }
        }
        return $default;        
    }
    
}

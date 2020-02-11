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

class Openweathermap extends WeatherDriver
{
    
    /**
     * Get by city
     * @param string $city
     * @param string $code
     * @return array
     */
    public function getForecastByCity($city, $code = '', $scope = 'forecast')
    {
        $query = $city;
        if (!empty($code)) {
            $query .= ',' . $code;
        }
            
        $url = $this->getUrl(['q'=>$query], $scope);
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
        $url .= '&lang='.$this->geBrowserLanguage();
        $url .= '&' . http_build_query($param);
        return $url;
    }
}

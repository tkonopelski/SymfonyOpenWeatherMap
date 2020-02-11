<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 03.04.19
 * Time: 14:07.
 */

namespace App\Controller;

use App\Entity\WeatherHistory;
use App\Service\Openweathermap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapApiController extends AbstractController
{
    /**
     * @Route("/mapapi/getwheather", name="mapapi_getwheather")
     */
    public function getWheather(Request $request, Openweathermap $openweathermap)
    {
        $latitude = $request->request->get('latitude', false);
        $longitude = $request->request->get('longitude', false);

        $data = $openweathermap->getCurrentByCoordinate($latitude, $longitude);
        $res = $this->save($data, $request);

        $return = [];
        if (is_numeric($res) and $res > 0) {
            $return['status'] = 'success';

            $view = $this->render('modal.html.twig', [
                'data' => $data,
            ]);

            $return['view'] = $view->getContent();
            return new JsonResponse($return);
        } else {
            $return['status'] = 'error';
            return new JsonResponse($return);
        }
    }
    /**
     * @Route("/mapapi/forecastcity", name="mapapi_forecastcity")
     */
    public function getForecastByCity(Request $request, Openweathermap $openweathermap)
    {
        $city = $request->query->get('city');
        //$city = $request->request->get('city', false);
        $city = substr($city, 0, 25);
        //echo $city;
        
        
        //return new JsonResponse('');
        //return new Response('');

        $data = $openweathermap->getForecastByCity($city);
        return new JsonResponse($data);
        
        
    }

    /**
     * Save weather.
     *
     * @param $data
     *
     * @return int
     */
    private function save($data, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $weatherHistory = new WeatherHistory();
        $weatherHistory->setLatitude($data['coord']['lat']);
        $weatherHistory->setLongitude($data['coord']['lon']);
        $weatherHistory->setAdded(new \DateTime());
        $weatherHistory->setTemp($data['main']['temp']);
        $weatherHistory->setPressure($data['main']['pressure']);
        $weatherHistory->setHumidity($data['main']['humidity']);
        $weatherHistory->setWindSpeed($data['wind']['speed']);
        $weatherHistory->setClouds($data['clouds']['all']);
        $weatherHistory->setCountry($data['sys']['country']);
        $weatherHistory->setCity($data['name']);
        $weatherHistory->setIp($request->getClientIp());
        $weatherHistory->setUa($request->headers->get('User-Agent'));
        $weatherHistory->setDescription($data['weather'][0]['description']);
        if (isset($data['visibility'])) {
            $weatherHistory->setVisibility($data['visibility']);
        }

        $entityManager->persist($weatherHistory);
        $entityManager->flush();

        return $weatherHistory->getId();
    }

}

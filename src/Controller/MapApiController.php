<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 03.04.19
 * Time: 14:07
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Openweathermap;
use App\Entity\WeatherHistory;
use Symfony\Component\HttpFoundation\JsonResponse;

class MapApiController extends AbstractController
{
    /**
     * @Route("/mapapi/getwheather", name="mapapi_getwheather")
     */
    function getWheather(Request $request, Openweathermap $openweathermap)
    {
        $latitude = $request->request->get('latitude', false);
        $longitude = $request->request->get('longitude', false);

        $data = $openweathermap->apiCall($latitude, $longitude);
        $res = $this->save($data, $request);

        $return = array();

        if (is_numeric($res) AND $res > 0) {

            $return['status'] = 'success';

            $view = $this->render('modal.html.twig', array(
                'data' => $data,
            ));

            $return['view'] = $view->getContent();

            return new JsonResponse($return);

        } else {
            $return['status'] = 'error';
            return new JsonResponse($return);
        }

    }

    /**
     * Save weather
     *
     * @param $data
     * @param Request $request
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

    /**
     * @Route("/test", name="mapapi_test")
     */
    public function test(Openweathermap $openweathermap)
    {
        $key = $openweathermap->getKey();
        //dump($key);
        return new Response('');

    }
}
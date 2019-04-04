<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\WeatherHistory;


class StatsController extends AbstractController
{
    /**
     * Statistics
     *
     * @Route("/stats", name="stats")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(WeatherHistory::class);

        $aggregated = $repository->getAggregated();
        dump($aggregated);

        $count = $repository->getCount();
        dump($count);

        $groupCity = $repository->findByCityGroup();
        dump($groupCity);

        return $this->render('stats.html.twig', [
            'title' => 'Stats',
            'aggregated' => $aggregated,
            'count' => $count['c'],
            'groupCity' => $groupCity
        ]);
    }
}
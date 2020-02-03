<?php

namespace App\Controller;

use App\Entity\WeatherHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    /**
     * Statistics.
     *
     * @Route("/stats", name="stats")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(WeatherHistory::class);

        $aggregated = $repository->getAggregated();
        $count = $repository->getCount();
        $groupCity = $repository->findByCityGroup();

        return $this->render('stats.html.twig', [
            'title' => 'Stats',
            'aggregated' => $aggregated,
            'count' => $count['c'],
            'groupCity' => $groupCity,
        ]);
    }
}

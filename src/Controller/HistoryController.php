<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\WeatherHistory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HistoryController extends AbstractController
{
    private $queryLimit = 10;

    /**
     * @Route("/history", name="history")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(WeatherHistory::class);

        return $this->render('history.html.twig', [
            'pagination' => $paginator->paginate(
                $repository->findBy(array(), array('id'=>'desc')),$request->query->getInt('page', 1),$this->queryLimit)
        ]);
    }
}
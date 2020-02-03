<?php

namespace App\Controller;

use App\Entity\WeatherHistory;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
                $repository->findBy([], ['id' => 'desc']),
                $request->query->getInt('page', 1),
                $this->queryLimit
            ),
        ]);
    }

    /**
     * @Route("/history/item/{id}", name="history_item")
     */
    public function item($id = null)
    {
        $repository = $this->getDoctrine()->getRepository(WeatherHistory::class);
        $item = $repository->findOneBy(['id' => $id]);

        return $this->render('history_item.html.twig', [
            'item' => $item,
            'title' => 'Item #'.$item->getId(),
        ]);
    }
}

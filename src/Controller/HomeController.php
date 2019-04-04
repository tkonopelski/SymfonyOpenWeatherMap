<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * HOME
     */
    public function index()
    {
        return $this->render('map.html.twig', [
            'title' => 'Home',
        ]);
    }
}
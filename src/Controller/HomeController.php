<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Home page.
     */
    public function index()
    {
        return $this->render('map.html.twig', [
            'title' => 'Home',
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Home page.
     */
    public function index()
    {
        return $this->render('vue.html.twig', [
            'title' => 'map',
        ]);
    }
    
    /**
     * @Route("/chart", name="home_chart")
     */
    public function chart()
    {
        return $this->render('chart.html.twig', [
            'title' => 'chart',
        ]);
    }
    
    
    /**
     * @Route("/map", name="home_map")
     */
    public function map()
    {
        return $this->render('map.html.twig', [
            'title' => 'vue',
        ]);
    }
    
    /**
     * @Route("/vue-dev", name="home_vue")
     */
    public function vue()
    {
        return $this->render('vue.html.twig', [
            'title' => 'vue',
        ]);
    }
    
    
    
}

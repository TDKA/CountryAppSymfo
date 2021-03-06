<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $req): Response
    {
        $message = $req->get('message');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'message' => $message
        ]);
    }
}

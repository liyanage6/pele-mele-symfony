<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $articles = $this->get('doctrine.orm.entity_manager')->getRepository("AppBundle:Article")->findAll();

        return $this->render('homepage.html.twig', [
            "articles" => $articles
        ]);
    }
}

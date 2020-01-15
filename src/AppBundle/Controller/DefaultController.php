<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
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
        $em = $this->get('doctrine.orm.entity_manager');
        $articles = $em->getRepository("AppBundle:Article")->findAll();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $article = $form->getData();
            $article->setCreatedAt(new \DateTime());
            $article->setUser($this->getUser());

            $em->persist($article);
            $em->flush();

            $articles = $em->getRepository('AppBundle:Article')->findAll();

            return $this->render('homepage.html.twig', [
                "articles" => $articles,
                "form" => $form->createView(),
                "categories" => $categories
            ]);
        }


        return $this->render('homepage.html.twig', [
            "articles" => $articles,
            "form" => $form->createView(),
            "categories" => $categories
        ]);
    }
}

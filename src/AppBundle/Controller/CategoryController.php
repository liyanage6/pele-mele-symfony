<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use AppBundle\Form\ArticleType;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 *
 * @Route(name="/category")
 */
class CategoryController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/add")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addAction (Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $category = new Category();
        $formCat = $this->createForm(CategoryType::class, $category);

        $formCat->handleRequest($request);

        if ($formCat->isSubmitted() && $formCat->isValid()){
            $category = $formCat->getData();

            $em->persist($category);
            $em->flush();

            $article = new Article();
            $form = $this->createForm(ArticleType::class, $article);
            $articles = $em->getRepository('AppBundle:Article')->findAll();
            $categories = $em->getRepository('AppBundle:Category')->findAll();

            return $this->redirectToRoute('homepage', [
                'articles' => $articles,
                'form' => $form->createView(),
                'categories' => $categories
            ]);
        }

        return $this->render(':category:list.html.twig', [
            'form' => $formCat->createView()
        ]);
    }
}
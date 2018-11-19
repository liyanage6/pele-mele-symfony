<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ArticleController
 * @package AppBundle\Controller
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route("/add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();
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

            return $this->redirectToRoute('homepage', ["articles" => $articles]);
        }

        return $this->render("article/add.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param Article $article
     *
     * @Route("/delete/{article}")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|AccessDeniedException
     */
    public function deleteAction (Article $article)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $article = $em->getRepository('AppBundle:Article')->find($article);

        if($this->getUser() == $article->getUser() || $article->getUser()->getRoles() == "ROLE_SUPER_ADMIN"){
            $em->remove($article);
            $em->flush();
        }
        else{
            throw new AccessDeniedException("You don't have the right.");
        }

        $articles = $em->getRepository('AppBundle:Article')->findAll();

        return $this->redirectToRoute('homepage', ['articles' => $articles]);
    }
}
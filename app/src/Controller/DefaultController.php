<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/", methods={"GET"})
 */
class DefaultController extends AbstractController{

    /**
     * @Route("/", methods={"GET"})
     */
    public function index()
    {
        return new Response('<h1>Hello Wold!</h1>',200);
    }

    /**
     * @Route("/{alias}", name="redirect_to_short_url", methods={"GET"}, requirements={"alias"="[a-zA-Z0-9]+"})
     */
    public function redirectToShorty($alias)
    {
       //Find the url and redirect
       $em = $this->getDoctrine()->getManager();
       $shorty = $em->getRepository(ShortUrl::class)->findOneByAlias($alias);

       if($shorty){
           $shorty->setVisits($shorty->getVisits()+1);
           $em->persist($shorty);
           $em->flush();
           return $this->redirect($shorty->getUrl(),301);
       }

       return new Response('<h1>Not Found</h1>',Response::HTTP_NOT_FOUND);
    }
}
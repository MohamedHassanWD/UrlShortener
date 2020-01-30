<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use App\Services\Shortener;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/api/urls")
 */
class UrlController extends AbstractController
{

    /**
     * @Route(methods={"GET"})
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $urls = $em->getRepository(ShortUrl::class)->findAll();

        return $this->json([
            'status' => 'SUCCESS',
            'message' => 'Resources available',
            'data' => $urls
        ],200);
    }

    /**
     * @Route("/{alias}", name="url_by_alias", methods={"GET"}, requirements={"alias"="[a-zA-Z0-9]+"})
     */
    public function read($alias)
    {
        $url = $this->getDoctrine()->getRepository(ShortUrl::class)->findOneByAlias($alias);

        if(!$url){
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Resource not found'
            ],404);
        }

        return $this->json([
            'status' => 'SUCCESS',
            'message' => 'Resource available',
            'data' => [
                'id' => $url->getId(),
                'alias' => $url->getAlias(),
                'url' => $url->getUrl(),
                'created_at' => $url->getCreatedAt(),
                'updated_at' => $url->getUpdatedAt(),
            ]
        ],200);
    }

    /**
     * @Route(name="create_short_url", methods={"POST"})
     */
    public function create(Request $request, UserInterface $user)
    {
        //Initialize the shortner service
        $shortener = new Shortener();

        //Initialize the serializer
        $serializer = $this->get('serializer');

        //Create a short URL obkect based on the request content
        $shortUrl = $serializer->deserialize($request->getContent(),ShortUrl::class,'json');

        //verify url is not null
        if(!$shortUrl->getUrl()) 
            return $this->json([
                'status'=>'ERROR',
                'message'=>'You did not provide a URL!'
            ],200);

        //Validate the url
        if(!$shortener->validateUrlFormat($shortUrl->getUrl()))
            return $this->json([
                'status'=>'ERROR',
                'message'=>'The URL provided is not valid!'
            ],200);

        //Verify the url is real and exists
        // if(!$shortener->verifyUrlExists($shortUrl->getUrl()))
        //     return $this->json([
        //         'status'=>'ERROR',
        //         'message'=>'The URL provided is not valid or does not exists (Fake)!'
        //     ],200);

        
        //Create the entity manager
        $em = $this->getDoctrine()->getManager();

        //check if the url exists
        $existingUrl = $em->getRepository(ShortUrl::class)->findOneByUrl($shortUrl->getUrl());

        if($existingUrl){
            return $this->json([
                'id' => $existingUrl->getId(),
                'alias' => $existingUrl->getAlias(),
                'url' => $existingUrl->getUrl(),
                'created_at' => $existingUrl->getCreatedAt(),
                'updated_at' => $existingUrl->getUpdatedAt(),
            ],201);
        }

        //Insert the url

        $shortUrl->setCreatedAt(new DateTime('now'));
        $shortUrl->setUser($user->getId());
        $em->persist($shortUrl);
        $em->flush();
        
        //Create the short code from the ID
        $createdShortUrl = $em->getRepository(ShortUrl::class)->findOneById($shortUrl->getId());
        $createdShortUrl->setAlias($shortener->generateShortCode($shortUrl->getId()));
        $em->persist($shortUrl);
        $em->flush();

        //return the full url object
        return $this->json([
            'id' => $createdShortUrl->getId(),
            'alias' => $createdShortUrl->getAlias(),
            'url' => $createdShortUrl->getUrl(),
            'created_at' => $createdShortUrl->getCreatedAt(),
            'updated_at' => $createdShortUrl->getUpdatedAt(),
        ],201);
    }

    /**
     * @Route("/{alias}", name="update_short_url", methods={"PUT"}, requirements={"alias"="[a-zA-Z0-9]+"})
     */
    public function update(Request $request,$alias,UserInterface $user)
    {
        //Initialize the shortner service
        $shortener = new Shortener();

        //Initialize the serializer
        $serializer = $this->get('serializer');

        //Create a short URL obkect based on the request content
        $shortUrl = $serializer->deserialize($request->getContent(),ShortUrl::class,'json');

        //verify url is not null
        if(!$shortUrl->getUrl()) 
            return $this->json([
                'status'=>'ERROR',
                'message'=>'You did not provide a URL!'
            ],200);

        //Validate the url
        if(!$shortener->validateUrlFormat($shortUrl->getUrl()))
            return $this->json([
                'status'=>'ERROR',
                'message'=>'The URL provided is not valid!'
            ],200);
        
        //Create the entity manager
        $em = $this->getDoctrine()->getManager();

        //check if the url exists
        $existingAlias = $em->getRepository(ShortUrl::class)->findOneByAlias($alias);
        
        if(!$existingAlias)
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Resource Not Found!'
            ],404); 
        
        //Check if user has permission to edit the resource
        if($existingAlias->getUser() != $user->getId())
            return $this->json([
                'status' => 'ERROR',
                'message' => 'You do not have permission to edit this resource'
            ],403);
        
        $existingAlias->setUpdatedAt(new DateTime('now'));
        $existingAlias->setUrl($shortUrl->getUrl());
        $em->persist($existingAlias);
        $em->flush();
        
        return $this->json([
            'status' => 'SUCCESS',
            'message' => 'Resource Updated Successfully!',
            'data' => $existingAlias
        ],200); 
    }

    /**
     * @Route("/{alias}", name="delete_short_url", methods={"DELETE"}, requirements={"alias"="[a-zA-Z0-9]+"})
     */
    public function delete($alias, UserInterface $user)
    {
        $em = $this->getDoctrine()->getManager();
        $shortUrl = $em->getRepository(ShortUrl::class)->findOneByAlias($alias);

        if(!$shortUrl)
            return $this->json([
                'status' => 'ERROR',
                'message' => 'Resource not found'
            ],404);

        //Check if user has permission to edit the resource
        if($shortUrl->getUser() !== $user->getId())
            return $this->json([
                'status' => 'ERROR',
                'message' => 'You do not have permission to edit this resource'
            ],403);

        $em->remove($shortUrl);
        $em->flush();

        return $this->json([
            'status' => 'SUCCESS',
            'message' => 'Resource Deleted Successfully!'
        ],200);
    }
}

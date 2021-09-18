<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/newsletter")
 */
class NewsletterController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function list(): Response
    {
        return $this->render('newsletter/index.html.twig', []);
    }
}

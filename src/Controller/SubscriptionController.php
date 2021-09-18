<?php

namespace App\Controller;

use App\Form\SubscriptionType;
use App\Subscription\SubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subscription")
 */
class SubscriptionController extends AbstractController
{
    private SubscriptionManager $manager;

    public function __construct(SubscriptionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/", name="subscribe")
     */
    public function subscribe(Request $request): Response
    {
        $form = $this->createForm(SubscriptionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->manageSubscription($form->getData());
            $this->addFlash("success", "subscription confirmed");
        }

        return $this->render('subscription/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/unsubscribe/{userUuid}/{newsletterUuid}", name="unsubscribe")
     */
    public function unsubscribe(string $userUuid, string $newsletterUuid): Response
    {
        $newsletter = $this->manager->unsubscribe($userUuid, $newsletterUuid);

        return new Response("Newsletter successfully unsubscribed: $newsletter");
    }
}

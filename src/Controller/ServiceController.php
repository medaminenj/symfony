<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/service/{name}', name: 'app_service_show')]
    public function showService(string $name): Response
    {
        return $this->render('service/showService.html.twig',[
            'name' => $name 
        ]);
    }

    #[Route('/gotoindex', name: 'gotoindexroute')]
    public function gotoindex(): Response
    {
        return $this->redirectToRoute('app_home');
    }

}

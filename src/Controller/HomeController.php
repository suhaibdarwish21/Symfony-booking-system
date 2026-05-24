<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        return $this->render('home_controller/index.html.twig', [
            'services' => $services,
            ]);
    }
    #[Route('/home/about', name: 'app_home_about')]
    public function about(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();
        return $this->render('home_controller/about.html.twig', [
            'services' => $services,
            ]);
    }
}

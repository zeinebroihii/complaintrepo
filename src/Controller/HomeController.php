<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserGestion\GlobalUserRepository;

class HomeController extends AbstractController
{
    #[Route('/home/{id}', name: 'home')]
    public function index(GlobalUserRepository $userRepository, int $id): Response
    {
        // Fetch data based on the provided ID
        $data = $userRepository->find($id);
    
        // Render the header and home templates
        $headerContent = $this->renderView("Components/header.twig", ['id' => $id]); // Pass the 'id' variable here
        $homeContent = $this->renderView('home/home.twig', [
            'data' => $data,
            'id' => $id,
        ]);
    
        // Concatenate the contents and return the response
        $content = $headerContent . $homeContent;
        return new Response($content);
    }
    

    #[Route('/nav', name: 'nav_bar')]
    public function nav_bar(): Response
    {
        return new Response
        (
        $this->renderView("Components/header.twig")
        );
    }
}

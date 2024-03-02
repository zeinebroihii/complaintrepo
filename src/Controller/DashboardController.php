<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ComplaintGestion\Complaint;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
       /*  $complaints = $this->getDoctrine()->getRepository(Complaint::class)->findAll();
        $listContent = $this->renderView('list.html.twig', [
            'complaints' => $complaints,
        ]);
        */
    
        return $this->render('/Complaint_gestion/dashboard.html.twig', [
        ]);
    }
}

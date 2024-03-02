<?php

namespace App\Controller;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\ComplaintGestion\Complaint;
use App\Entity\UserGestion\GlobalUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ComplaintGestion\ComplaintResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ResponseType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserGestion\GlobalUserRepository;
class AdminUserController extends AbstractController
{

    //l'objectif c'est behc nhezz links of usernames  wel lista el dahsboard 

    /**
     * @Route("/admin/users", name="admin_users_list")
     */
    public function index(): Response
    {
        $complaints = $this->getDoctrine()->getRepository(Complaint::class)->findAll();

        $uniqueUsernames = [];

        foreach ($complaints as $complaint) {
            $user = $complaint->getIdUser();
            if ($user) {
                $username = $user->getName();
                if (!in_array($username, $uniqueUsernames)) {
                    $uniqueUsernames[] = $username;
                }
            }
        }

        $userLinks = [];

        foreach ($uniqueUsernames as $username) {
            $userLinks[] = [
                'username' => $username,
                'link' => $this->generateUrl('user_complaints', ['username' => $username]),
            ];
        }

        return $this->render('ResponseGestion/user_links.html.twig', [
            'userLinks' => $userLinks,
        ]);
    }

    /**
     * @Route("/admin/users/{username}", name="user_complaints")
     */
    public function userComplaints(string $username): Response
    {
        $complaints = $this->getDoctrine()->getRepository(Complaint::class)->findAll();

        $userComplaints = [];

        foreach ($complaints as $complaint) {
            $user = $complaint->getIdUser();
            if ($user && $user->getName() === $username) {
                $userComplaints[] = $complaint;
            }
        }

        return $this->render('ResponseGestion/user_listComplaints.html.twig', [
            'username' => $username,
            'complaints' => $userComplaints,
        ]);
    }

   /**
     * @Route("/respond/{complaintId}", name="respond_to_complaint")
     */
    public function respondToComplaint(Request $request, int $complaintId): Response
    {
        $complaint = $this->getDoctrine()->getRepository(Complaint::class)->find($complaintId);

        if (!$complaint) {
            throw $this->createNotFoundException('Complaint not found');
        }

        $existingResponse = $complaint->getComplaintResponses()->first();

        if ($existingResponse) {
            
            return $this->redirectToRoute('complaint_response_list', ['complaintId' => $complaintId]);
        }

        $response = new ComplaintResponse();
        $response->setDate(new \DateTime());

        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response->setIdComplaint($complaint);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($response);
            $entityManager->flush();

            return $this->redirectToRoute('complaint_response_list', ['complaintId' => $complaintId]);
        }

        return $this->render('ResponseGestion/response.html.twig', [
            'form' => $form->createView(),
            'complaint' => $complaint,
        ]);
    }

    
    /**
     * @Route("/complaint/response/list/{complaintId}", name="complaint_response_list")
     */
    public function listComplaintResponses(int $complaintId): Response
    {
        $complaint = $this->getDoctrine()->getRepository(Complaint::class)->find($complaintId);

        if (!$complaint) {
            throw $this->createNotFoundException('Complaint not found');
        }

        $responses = $complaint->getComplaintResponses();

        return $this->render('ResponseGestion/response_list.html.twig', [
            'complaint' => $complaint,
            'responses' => $responses,
        ]);
    }


    /**
     * @Route("/admin/response/edit/{responseId}", name="edit_response")
     */
    public function editComplaintResponse(Request $request, int $responseId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $responseRepository = $entityManager->getRepository(ComplaintResponse::class);

        $response = $responseRepository->find($responseId);

        if (!$response) {
            throw $this->createNotFoundException('Response not found');
        }

        $form = $this->createForm(ResponseType::class, $response);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_users_list', ['responseId' => $responseId]);
        }

        return $this->render('ResponseGestion/Response.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/response/delete/{responseId}", name="delete_response")
     */
    public function deleteResponse(int $responseId, EntityManagerInterface $entityManager): Response
    {
        $response = $entityManager->getRepository(ComplaintResponse::class)->find($responseId);

        if (!$response) {
            throw $this->createNotFoundException('Response not found');
        }

        $complaintId = $response->getIdComplaint()->getId();

        $entityManager->remove($response);
        $entityManager->flush();

        return $this->redirectToRoute('complaint_response_list', ['complaintId' => $complaintId]);
    }
    
}

<?php

// src/Controller/ComplaintController.php

namespace App\Controller;

use App\Entity\ComplaintGestion\Complaint;
use App\Entity\ComplaintGestion\ComplaintResponse;
use App\Entity\UserGestion\GlobalUser;
use App\Form\ComplaintType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ComplaintGestion\ComplaintRepository;
use App\Repository\UserGestion\GlobalUserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use DateTime;
class ComplaintController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    
    public function complaint(Request $request, ComplaintRepository $complaintRepository, GlobalUserRepository $userRepository, int $id): Response
    {

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $complaint = new Complaint();

        $complaint->setDate(new DateTime());
        $complaint->setStatus('Sent');

        $complaint->setIdUser($user);

        $form = $this->createForm(ComplaintType::class, $complaint);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $description = $form->get('description')->getData();
            $wordCount = str_word_count($description);
            if ($wordCount > 20) {
                $form->get('description')->addError(new \Symfony\Component\Form\FormError('Description cannot exceed 20 words.'));
                return $this->render('/Complaint_gestion/Complaint.html.twig', [
                    'form' => $form->createView(),
                    'message' => 'Your message goes here',
                    'id' => $id,
                    'complaints' => $complaintRepository->findAll(),
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($complaint);
            $entityManager->flush();

            return $this->redirectToRoute('complaint_list', ['id' => $id]);
        }

        return $this->render('/Complaint_gestion/Complaint.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'complaints' => $complaintRepository->findAll(),
        ]);
    }





    public function listComplaints(int $id, GlobalUserRepository $userRepository): Response
    {

        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $complaints = $user->getComplaints();
        return $this->render('/Complaint_gestion/list.html.twig', [
            'complaints' => $complaints,
            'id' => $id,
            'user' => $user,
        ]);
    }





  public function editComplaint(
    Request $request,
    ManagerRegistry $doctrine,
    int $id,
    int $complaintId
): Response {
    $entityManager = $doctrine->getManager();
    $complaintRepository = $entityManager->getRepository(Complaint::class);
    $complaint = $complaintRepository->find($complaintId);

    if (!$complaint) {
        throw $this->createNotFoundException('Complaint not found');
    }

    $submissionTime = $complaint->getDate();

    $currentTime = new DateTime();
    $timeDifferenceSeconds = $currentTime->getTimestamp() - $submissionTime->getTimestamp();

    $editable = $timeDifferenceSeconds <= 120;

    $form = $this->createForm(ComplaintType::class, $complaint);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('complaint_list', ['id' => $id]);
    }

    return $this->render('/Complaint_gestion/edit.html.twig', [
        'form' => $form->createView(),
        'id' => $id,
        'complaintId' => $complaintId,
        'editable' => $editable,
    ]);
}





    public function deleteComplaint(int $id, int $complaintId, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $complaintRepository = $entityManager->getRepository(Complaint::class);
    
        $complaint = $complaintRepository->find($complaintId);
    
        if (!$complaint) {
            throw $this->createNotFoundException('Complaint not found');
        }
    
        // Remove the complaint entity
        $entityManager->remove($complaint);
        $entityManager->flush();
    
        return $this->redirectToRoute('complaint_list', ['id' => $id]);
    }
    



  /**
 * @Route("/complaint/response/{complaintId}", name="complaint_response")
 */
public function viewComplaintResponse(int $complaintId): Response
{
    $entityManager = $this->getDoctrine()->getManager();

    $response = $entityManager->getRepository(ComplaintResponse::class)->findOneBy(['id_Complaint' => $complaintId]);

    if ($response) {
        $response->setSeenByUser(true);
        $entityManager->flush(); 
    } else {
        throw $this->createNotFoundException('Response not found'); 
    }

    return $this->render('ResponseGestion/ReceivedResponse.html.twig', [
        'response' => $response,
        'seenByUser' => $response->getSeenByUser(),
    ]);
}
    
    public function indexAction(): Response
{
    $typeRequiredMessage = $this->getParameter('custom_translations')['type_required_message'];
    return $this->render('indexe.html.twig', [
        'type_required_message' => $typeRequiredMessage,
    ]);
}
}

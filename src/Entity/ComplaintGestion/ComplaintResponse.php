<?php

namespace App\Entity\ComplaintGestion;

use App\Repository\ComplaintGestion\ComplaintResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ComplaintResponseRepository::class)]
class ComplaintResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    public function getId(): ?int
    {
        return $this->id;
    }
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\NotBlank(message:"Date of reclamation is required")]

    private ?\DateTimeInterface $date = null;
  

    #[ORM\ManyToOne(inversedBy: 'ComplaintResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Complaint $id_Complaint = null;


    #[ORM\Column(length: 255)]

    private ?string $response = null;
    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    #[ORM\Column]
    private ?bool $seenByUser = false;

    
    public function isSeenByUser(): bool
    {
        return $this->seenByUser;
    }

    public function markAsSeenByUser(): void
    {
        $this->seenByUser = true;
    }
    
        public function setSeenByUser(bool $seenByUser): static
        {
            $this->seenByUser = $seenByUser;
    
            return $this;
        }
        public function getSeenByUser(): ?bool
        {
            return $this->seenByUser;
        }

    public function getIdComplaint(): ?Complaint
    {
        return $this->id_Complaint;
    }

    public function setIdComplaint(?Complaint $id_Complaint): static
    {
        $this->id_Complaint = $id_Complaint;

        return $this;
    }
    
}

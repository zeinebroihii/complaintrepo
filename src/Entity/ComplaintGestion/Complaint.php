<?php

namespace App\Entity\ComplaintGestion;

use App\Entity\UserGestion\GlobalUser;
use App\Repository\UserGestion\GlobalUserRepository;

use App\Repository\ComplaintGestion\ComplaintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ComplaintRepository::class)]
class Complaint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Complaints')]
    #[ORM\JoinColumn(nullable: true)]
    private ?GlobalUser $id_user = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\NotBlank(message:"Date of reclamation is required")]

    private ?\DateTimeInterface $date = null;




    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message:"Type is required")]

    private ?string $type = null;

    #[ORM\Column(length: 255)]

    private ?string $Description;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"")]

    private ?int $priority = null;

    #[ORM\OneToMany(mappedBy: 'id_Complaint', targetEntity:     ComplaintResponse::class, orphanRemoval: true)]
    private Collection $responses;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getIdUser(): ?GlobalUser
    {
        return $this->id_user;
    }

    public function setIdUser(?GlobalUser $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * @return Collection<int, Response>
     */
    public function getComplaintResponses(): Collection
    {
        return $this->responses;
    }

    public function addComplaintResponse(ComplaintResponse $response): static
    {
        if (!$this->responses->contains($response)) {
            $this->responses->add($response);
            $response->setIdComplaint($this);
        }

        return $this;
    }

    public function removeComplaintResponse(ComplaintResponse $response): static
    {
        if ($this->responses->removeElement($response)) {
            // set the owning side to null (unless already changed)
            if ($response->getIdComplaint() === $this) {
                $response->setIdComplaint(null);
            }
        }

        return $this;
    }

}

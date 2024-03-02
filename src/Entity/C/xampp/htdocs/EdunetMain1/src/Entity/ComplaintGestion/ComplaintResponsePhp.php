<?php

namespace App\Entity\C\xampp\htdocs\EdunetMain1\src\Entity\ComplaintGestion;

use App\Repository\C\xampp\htdocs\EdunetMain1\src\Entity\ComplaintGestion\ComplaintResponsePhpRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintResponsePhpRepository::class)]
class ComplaintResponsePhp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $IsseenUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsseenUser(): ?bool
    {
        return $this->IsseenUser;
    }

    public function setIsseenUser(bool $IsseenUser): static
    {
        $this->IsseenUser = $IsseenUser;

        return $this;
    }
}

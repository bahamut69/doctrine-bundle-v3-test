<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV7;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]  // <<-- type UUID pour Doctrine
    private AbstractUid $id;

    #[ORM\Column(length: 150)]
    private string $name;

    public function __construct()
    {
        $this->id = new UuidV7();
    }

    public function getId(): AbstractUid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}

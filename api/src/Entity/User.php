<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV7;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: '`user`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private AbstractUid $id;

    #[ORM\Column(length: 150)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $client;

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

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
}

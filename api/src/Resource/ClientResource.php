<?php

namespace App\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Client;
use App\Processor\CreateClientProcessor;
use App\Processor\UpdateClientProcessor;
use App\Provider\GetClientProvider;
use Symfony\Component\Uid\AbstractUid;

#[ApiResource(
    shortName: "Client",
    operations: [
        new Get(),
        new Post(processor: CreateClientProcessor::class),
        new Put(processor: UpdateClientProcessor::class),
    ],
    provider: GetClientProvider::class
)]
class ClientResource
{
    public ?AbstractUid $id = null;

    public string $name;

    public function fromModel(Client $client): self
    {
        $this->id = $client->getId();
        $this->name = $client->getName();

        return $this;
    }
}

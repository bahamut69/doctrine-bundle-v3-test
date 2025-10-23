<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Client;
use App\Resource\ClientResource;
use Doctrine\ORM\EntityManagerInterface;

class CreateClientProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @param ClientResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ClientResource
    {
        $client = new Client();
        $client->setName(trim($data->name));

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $clientResource = new ClientResource();
        $clientResource->id = $client->getId();
        $clientResource->name = $client->getName();

        return $clientResource;
    }
}

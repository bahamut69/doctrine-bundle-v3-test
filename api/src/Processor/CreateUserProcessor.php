<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Resource\ClientResource;
use App\Resource\UserResource;
use Doctrine\ORM\EntityManagerInterface;

class CreateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ClientRepository $clientRepository
    ) {}

    /**
     * @param UserResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = new User();
        $user->setName(trim($data->name));

        $client = $this->clientRepository->find($data->client->id);
        $user->setClient($client);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userResource = (new UserResource())->fromModel($user);
        $userResource->client = (new ClientResource())->fromModel($client);

        return $userResource;
    }
}

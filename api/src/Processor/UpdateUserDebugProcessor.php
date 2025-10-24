<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Resource\ClientResource;
use App\Resource\UserResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateUserDebugProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $repository,
        private readonly ClientRepository $clientRepository,
    ) {}

    /**
     * @param UserResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserResource
    {
        $user = $this->repository->find($uriVariables['id']);

        if(!$user){
            throw new NotFoundHttpException(sprintf('User #%s non trouvé.', $uriVariables['id']));
        }

        $client = $this->clientRepository->find("019a151e-5c1b-7db2-8c34-e90c960934c8");

        $client->setName(trim($data->name));

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $userResource = new UserResource()->fromModel($user);
        $userResource->client = new ClientResource()->fromModel($client);

        return $userResource;
    }
}


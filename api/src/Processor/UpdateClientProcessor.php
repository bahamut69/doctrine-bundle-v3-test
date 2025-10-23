<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\ClientRepository;
use App\Resource\ClientResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateClientProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ClientRepository $repository,
    ) {}

    /**
     * @param ClientResource $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ClientResource
    {
        $client = $this->repository->find($uriVariables['id']);

        if(!$client){
            throw new NotFoundHttpException(sprintf('Client #%s non trouvé.', $uriVariables['id']));
        }

        $client->setName(trim($data->name));

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        $clientResource = new ClientResource();
        $clientResource->id = $client->getId();
        $clientResource->name = $client->getName();

        return $clientResource;
    }
}


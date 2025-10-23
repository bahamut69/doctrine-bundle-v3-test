<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ClientRepository;
use App\Resource\ClientResource;

class GetClientProvider implements ProviderInterface
{
    public function __construct(
        private readonly ClientRepository $repository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $client = $this->repository->find($uriVariables['id']);

        if($client === null){
            return null;
        }

        $clientResource = new ClientResource();
        $clientResource->id = $client->getId();
        $clientResource->name = $client->getName();

        return $clientResource;
    }
}

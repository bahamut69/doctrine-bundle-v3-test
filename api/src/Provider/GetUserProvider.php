<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\UserRepository;
use App\Resource\ClientResource;
use App\Resource\UserResource;

class GetUserProvider implements ProviderInterface
{
    public function __construct(
        private readonly UserRepository $repository
    )
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $user = $this->repository->find($uriVariables['id']);

        if($user === null){
            return null;
        }

        $clientResource = (new UserResource())->fromModel($user);
        $clientResource->client = (new ClientResource())->fromModel($user->getClient());

        return $clientResource;
    }
}

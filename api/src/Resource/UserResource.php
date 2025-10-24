<?php

namespace App\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\User;
use App\Processor\CreateUserProcessor;
use App\Processor\UpdateUserDebugProcessor;
use App\Processor\UpdateUserProcessor;
use App\Provider\GetUserProvider;
use Symfony\Component\Uid\AbstractUid;

#[ApiResource(
    shortName: "User",
    operations: [
        new Get(),
        new Post(processor: CreateUserProcessor::class),
        new Put(
            '/users/debug/{id}',
            input: ClientResource::class,
            processor: UpdateUserDebugProcessor::class
        ),
        new Put(processor: UpdateUserProcessor::class),
    ],
    provider: GetUserProvider::class
)]
class UserResource
{
    public ?AbstractUid $id = null;

    public string $name;

    public ClientResource $client;

    public function fromModel(User $user): self
    {
        $this->id = $user->getId();
        $this->name = $user->getName();

        return $this;
    }
}

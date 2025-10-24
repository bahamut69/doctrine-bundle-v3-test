<?php

namespace App\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\UserRepository;
use App\Resource\UserResource;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $repository,
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

        $user->setName(trim($data->name));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userResource = new UserResource();
        $userResource->id = $user->getId();
        $userResource->name = $user->getName();

        return $userResource;
    }
}


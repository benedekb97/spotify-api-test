<?php

namespace App\Http\Controllers;

use App\Entities\UserInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    protected function getUser(): ?UserInterface
    {
        $user = Auth::user();

        if ($user instanceof UserInterface) {
            return $user;
        }

        return null;
    }

    protected function findOr404(string $entityName, $identifier): object
    {
        $repository = $this->entityManager->getRepository($entityName);

        $resource = $repository->find($identifier);

        if ($resource === null) {
            abort(404);
        }

        $this->entityManager->initializeObject($resource);

        return $resource;
    }
}

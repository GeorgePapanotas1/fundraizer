<?php

namespace Fundraiser\Identity\Adapters\Http\Controllers;

use Fundraiser\Common\Adapters\Helpers\HttpResponseHelper as Response;
use Fundraiser\Identity\Adapters\Models\User;
use Fundraiser\Identity\Core\Dto\Queries\UserQuery;
use Fundraiser\Identity\Core\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

readonly class UsersController
{
    public function __construct(private UserService $service) {}

    public function index(UserQuery $query): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        $paginator = $this->service->paginate(
            $query->pagination->perPage,
            $query->pagination->page,
            $query,
            ['roles'],
        );

        $paginator->getCollection()->transform(function (User $u) {
            $data = $u->toArray();
            $data['roles'] = $u->roles->pluck('name')->values()->all();
            unset($data['roles_count']);

            return $data;
        });

        return Response::success($paginator);
    }
}

<?php

namespace Fundraiser\Identity\Adapters\Http\Controllers;

use Fundraiser\Common\Adapters\Helpers\HttpResponseHelper as Response;
use Fundraiser\Identity\Core\Dto\Responses\MeResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

readonly class MeController
{
    public function show(): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return Response::error('Unauthenticated', 401);
        }

        $roles = $user->getRoleNames()
            ->filter(fn ($value) => is_string($value))
            ->map(fn ($p) => (string) $p)
            ->values()
            ->all();

        $permissions = $user->getAllPermissions()->pluck('name')
            ->filter(fn ($value) => is_string($value))
            ->map(fn ($p) => (string) $p)
            ->values()
            ->all();

        $dto = new MeResponse(
            id: (string) $user->id,
            name: (string) $user->name,
            email: (string) $user->email,
            roles: $roles,
            permissions: $permissions,
        );

        return Response::success($dto);
    }
}

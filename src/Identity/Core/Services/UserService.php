<?php

namespace Fundraiser\Identity\Core\Services;

use Fundraiser\Identity\Adapters\Models\User;
use Fundraiser\Identity\Core\Dto\Queries\UserQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class UserService
{
    /**
     * @param  array<string>  $with
     * @return Builder<User>
     */
    protected function baseQuery(UserQuery $filters, array $with = []): Builder
    {
        $query = User::query()
            ->when($filters->search, function (Builder $q, string $term) {
                $q->where(function (Builder $nested) use ($term) {
                    $nested->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            });

        if (! empty($with)) {
            $query->with($with);
        }

        return $query;
    }

    /**
     * @param  array<string>  $with
     * @return LengthAwarePaginator<int, User>
     */
    public function paginate(int $perPage, int $page, UserQuery $filters, array $with = []): LengthAwarePaginator
    {
        Gate::authorize('viewAny', User::class);

        $query = $this->baseQuery($filters, $with);

        return $query->paginate(perPage: $perPage, page: $page);
    }
}

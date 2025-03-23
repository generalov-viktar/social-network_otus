<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private User $model
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): User
    {
        return $this->model->newQuery()->create($data);
    }

    public function findById(int $id): ?User
    {
        return $this->model->newQuery()->find($id);
    }

    public function findByToken(string $token): ?User
    {
        return $this->model->newQuery()
            ->whereHas('tokens', function ($query) use ($token) {
                $query->where('token', $token)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    });
            })
            ->first();
    }

    /**
     * @return Collection<int, User>
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }
} 
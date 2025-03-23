<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): User;

    public function findById(int $id): ?User;

    public function findByToken(string $token): ?User;

    /**
     * @return Collection<int, User>
     */
    public function getAll(): Collection;
} 
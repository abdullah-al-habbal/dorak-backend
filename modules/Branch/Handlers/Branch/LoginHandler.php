<?php

declare(strict_types=1);

namespace Modules\Branch\Handlers\Branch;

use Illuminate\Support\Facades\Hash;
use Modules\Branch\CQRS\Command\Branch\LoginCommand;
use Modules\Branch\Repositories\BranchLoginEloquentResolver;
use Modules\Branch\ValuesObjects\BranchLoginResult;

final class LoginHandler
{
    public function __construct(
        private readonly BranchLoginEloquentResolver $resolver,
    ) {}

    public function handle(LoginCommand $command): BranchLoginResult
    {
        $branch = $this->resolver->findByEmail($command->email);

        if ($branch === null || ! Hash::check($command->password, $branch->password)) {
            return BranchLoginResult::invalidCredentials();
        }

        $token = $branch->createToken('branch-app')->plainTextToken;

        return BranchLoginResult::success(
            token: $token,
            branchData: [
                'id' => $branch->id,
                'name' => $branch->name,
                'email' => $branch->email,
                'status' => $branch->status,
            ],
        );
    }
}

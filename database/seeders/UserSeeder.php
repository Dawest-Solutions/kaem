<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pos;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public const USERS_FACTORY = [
        [
            'first_name' => 'account_1',
            'last_name' => 'account_1',
            'email' => '',
        ], [
            'first_name' => 'account_2',
            'last_name' => 'account_2',
            'email' => '',
        ], [
            'first_name' => 'account_3',
            'last_name' => 'account_3',
            'email' => '',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::USERS_FACTORY as $userFactory) {
            $user = User::factory()
                ->hasPos(1)
                ->create($userFactory);
            $this->assignRoleUserFactory($user);
        }

        User::factory(5)
            ->hasPos(1)
            ->create()
            ->each(fn($user) => $this->assignRoleUserFactory($user));
    }

    /**
     * @param User $user
     * @return User
     */
    public function assignRoleUserFactory(User $user): User
    {
        return $user->assignRole(User::availableRoles()
            ->pluck('data.name')
            ->reject(fn($roleName) => in_array($roleName, ['Admin', 'Central', 'RKS', 'PH']))
            ->random());
    }
}

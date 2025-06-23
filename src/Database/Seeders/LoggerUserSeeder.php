<?php

namespace Layman\LaravelLogger\Database\Seeders;

use Illuminate\Database\Seeder;
use Layman\LaravelLogger\Models\LoggerUser;

class LoggerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $config = config('logger.users');

        $users = array_map(function ($user) {
            $user['password'] = bcrypt($user['password']);
            return $user;
        }, $config);

        foreach ($users as $user) {
            LoggerUser::query()->create($user);
        }
    }
}

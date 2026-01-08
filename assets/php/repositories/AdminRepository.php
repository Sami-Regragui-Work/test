<?php

require __DIR__ . "/UserRepository.php";

class AdminRepository extends UserRepository
{
    protected function whichTable(): string
    {
        return 'users';
    }

    protected function mapToProp(): array
    {
        return [
            'username' => 'user',
            'password_hash' => 'pass',
            'first_name' => 'fName',
            'last_name' => 'lName',
            'phone' => 'phone',
            'email' => 'email'
        ];
    }
}

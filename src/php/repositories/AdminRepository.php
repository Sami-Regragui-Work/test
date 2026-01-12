<?php

require __DIR__ . "/UserRepository.php";

class AdminRepository extends UserRepository
{
    public function whichTable(): string
    {
        return 'users';
    }

    public function mapToProp(): array
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

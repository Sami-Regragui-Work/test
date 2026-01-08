<?php

require __DIR__ . "/User.php";

class Admin extends User
{
    public function __construct(
        ?int $id = null,
        string $user = "",
        string $email = "",
        string $pass = "",
        string $fName = "",
        string $lName = "",
        string $phone = "",
    ) {
        parent::__construct(
            $id,
            $user,
            $email,
            $pass,
            $fName,
            $lName,
            $phone
        );
    }

    protected function whichRole(): Role
    {
        return Role::ADMIN;
    }
}

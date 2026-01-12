<?php

require __DIR__ . "/UserRepository.php";

class PatientRepository extends UserRepository
{
    public function whichTable(): string
    {
        return 'patients';
    }

    public function mapToProp(): array
    {
        return [
            'user_id' => 'id',
            'gender' => 'gender',
            'date_of_birth' => 'dOB'
        ];
    }
}

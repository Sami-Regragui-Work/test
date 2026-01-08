<?php

require __DIR__ . "/UserRepository.php";

class PatientRepository extends UserRepository
{
    protected function whichTable(): string
    {
        return 'patients';
    }

    protected function mapToProp(): array
    {
        return [
            'user_id' => 'id',
            'gender' => 'gender',
            'date_of_birth' => 'dOB'
        ];
    }
}

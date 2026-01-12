<?php

require __DIR__ . "/UserRepository.php";

class DoctorRepository extends UserRepository
{
    public function whichTable(): string
    {
        return 'doctors';
    }

    public function mapToProp(): array
    {
        return [
            'user_id' => 'id',
            'specialization' => 'spec',
            'department_id' => 'fk'
        ];
    }
}

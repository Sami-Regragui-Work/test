<?php

require __DIR__ . "/UserRepository.php";

class DoctorRepository extends UserRepository
{
    protected function whichTable(): string
    {
        return 'doctors';
    }

    protected function mapToProp(): array
    {
        return [
            'user_id' => 'id',
            'specialization' => 'spec'
        ];
    }
}

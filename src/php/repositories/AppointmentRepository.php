<?php

require __DIR__ . "/BaseRepository.php";

class AppointmentRepository extends BaseRepository
{
    protected function whichTable(): string
    {
        return 'appointments';
    }

    protected function mapToProp(): array
    {
        return [
            'date' => 'date',
            'time' => 'time',
            'doctor_id' => 'fk',
            'patient_id' => 'fk',
            'reason' => 'reason',
            'status' => 'status'
        ];
    }
}

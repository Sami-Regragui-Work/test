<?php

require __DIR__ . "/BaseRepository.php";

class PrescriptionRepository extends BaseRepository
{
    public function whichTable(): string
    {
        return 'prescriptions';
    }

    public function mapToProp(): array
    {
        return [
            'date' => 'date',
            'doctor_id' => 'fk',
            'patient_id' => 'fk',
            'medication_id' => 'fk',
            'dosage_instructions' => 'dosInst'
        ];
    }
}

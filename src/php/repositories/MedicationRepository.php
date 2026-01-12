<?php

require __DIR__ . "/BaseRepository.php";

class MedicationRepository extends BaseRepository
{
    public function whichTable(): string
    {
        return 'medications';
    }

    public function mapToProp(): array
    {
        return [
            'name' => 'name',
            'instructions' => 'instruct',
            'prescription_id' => 'fk'
        ];
    }
}

<?php

require __DIR__ . "/BaseRepository.php";

class MedicationRepository extends BaseRepository
{
    protected function whichTable(): string
    {
        return 'medications';
    }

    protected function mapToProp(): array
    {
        return [
            'name' => 'name',
            'instructions' => 'instruct',
            'prescription_id' => 'fk'
        ];
    }
}

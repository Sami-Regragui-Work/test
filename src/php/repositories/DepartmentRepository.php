<?php

require __DIR__ . "/BaseRepository.php";

class DepartmentRepository extends BaseRepository
{
    public function whichTable(): string
    {
        return 'departments';
    }

    public function mapToProp(): array
    {
        return [
            'name' => 'name',
            'location' => 'location'
        ];
    }
}

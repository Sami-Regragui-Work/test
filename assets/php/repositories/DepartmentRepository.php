<?php

require __DIR__ . "/BaseRepository.php";

class DepartmentRepository extends BaseRepository
{
    protected function whichTable(): string
    {
        return 'departments';
    }

    protected function mapToProp(): array
    {
        return [
            'name' => 'name',
            'location' => 'location'
        ];
    }
}

<?php

class DoctorRepository extends BaseRepository
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

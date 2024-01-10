<?php

namespace App\Filament\Resources\LecturerResource\Pages;

use App\Filament\Resources\LecturerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateLecturer extends CreateRecord
{
    protected static string $resource = LecturerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['email'] = Str::random();
        $data['password'] = bcrypt('password');
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $record->assignRole('lecturer');
    }
}

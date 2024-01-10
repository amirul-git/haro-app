<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['email'] = Str::random();
        $data['password'] = bcrypt('password');
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $record->assignRole('student');
    }
}

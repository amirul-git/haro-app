<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Day;
use App\Models\Link;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Schedule Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('lecturer_id')
                    ->label('Lecturer')
                    ->options(User::role('lecturer')->get()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('user_id')
                    ->label('Student')
                    ->options(User::role('student')->get()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('day_id')
                    ->label('Day')
                    ->options(Day::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                TimePicker::make('time')->prefix('Starts')
                    ->required(),
                TextInput::make('duration')->label('Duration (minutes)')->numeric()->required()->default(90),
                Select::make('link_id')
                    ->label('Link')
                    ->options(Link::all()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lecturer.name')->searchable(),
                TextColumn::make('user.name')->label('Student')->searchable(),
                TextColumn::make('day.name'),
                TextColumn::make('time')->label('Starts')->sortable(),
                TextColumn::make('endTime')->label('Ends')->sortable(),
                TextColumn::make('duration')->label('Duration (minutes)')->sortable(),
                TextColumn::make('link.name'),
            ])
            ->filters([
                SelectFilter::make('lecturer')
                    ->relationship('lecturer', 'name', fn () => User::role('lecturer'))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('student')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('day')
                    ->relationship('day', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('link')
                    ->relationship('link', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}

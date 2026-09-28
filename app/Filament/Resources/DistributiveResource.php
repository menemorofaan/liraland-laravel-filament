<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistributiveResource\Pages;
use App\Models\Distributive;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DistributiveResource extends Resource
{
    protected static ?string $model = Distributive::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $modelLabel = 'Дистрибутив';
    protected static ?string $pluralModelLabel = 'Дистрибутиви';
    protected static ?string $navigationLabel = 'Дистрибутиви';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Назва програми')
                    ->placeholder('ПК ЛІРА-САПР 2026')
                    ->required(),

                Forms\Components\TextInput::make('version')
                    ->label('Версія')
                    ->placeholder('2026.1')
                    ->required(),

                Forms\Components\Select::make('os')
                    ->label('Операційна система')
                    ->options([
                        'Windows' => 'Windows',
                        'Linux' => 'Linux',
                        'macOS' => 'macOS',
                    ])
                    ->default('Windows')
                    ->required(),

                Forms\Components\Select::make('access_level')
                    ->label('Рівень доступу')
                    ->options([
                        'public' => '🟢 Вільний (для всіх)',
                        'registered' => '🟡 Тільки для зареєстрованих',
                        'licensed' => '🔴 Тільки для власників ліцензій',
                    ])
                    ->default('public')
                    ->required(),

                // УМНЫЙ ВЫБОР ФАЙЛА С СЕРВЕРА
                Forms\Components\Select::make('file_path')
                    ->label('Файл дистрибутиву (із сервера)')
                    ->options(function () {
                        $files = Storage::disk('public')->files('distributives');
                        return collect($files)->mapWithKeys(fn ($file) => [$file => basename($file)]);
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Оберіть готовий файл із сховища сервера (storage/app/public/distributives)'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Опубліковано')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->label('Версія')
                    ->searchable(),
                Tables\Columns\TextColumn::make('os')
                    ->label('ОС')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('Файл')
                    ->formatStateUsing(fn ($state) => basename($state))
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Статус'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListDistributives::route('/'),
            'create' => Pages\CreateDistributive::route('/create'),
            'edit' => Pages\EditDistributive::route('/{record}/edit'),
        ];
    }
}

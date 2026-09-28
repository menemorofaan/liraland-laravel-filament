<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LicenseResource\Pages;
use App\Filament\Resources\LicenseResource\RelationManagers;
use App\Models\License;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
	
	protected static ?string $modelLabel = 'Ліцензія';
	protected static ?string $pluralModelLabel = 'Ліцензії';
	protected static ?string $navigationLabel = 'Ліцензії';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('license_key')
                ->label('Ключ ліцензії')
                ->default(fn () => 'LIC-' . strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4))))
                ->required(),
            Forms\Components\Select::make('user_id')
                ->label('Прив’язати до акаунта (Email)')
                ->relationship('user', 'email')
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('client_name')
                ->label('Клієнт / Організація')
                ->required(),
            Forms\Components\TextInput::make('client_email')
                ->label('Контактний Email')
                ->email()
                ->required(),
            Forms\Components\Select::make('license_model')
                ->label('Тип ліцензування')
                ->options([
                    'subscription' => 'Підписка (Хмарна / Серверна)',
                    'perpetual' => 'Безстрокова (USB-ключ CodeMeter)',
                ])
                ->default('subscription')
                ->reactive()
                ->required(),
            Forms\Components\TextInput::make('dongle_id')
                ->label('Серійний номер USB-ключа (Dongle ID)')
                ->placeholder('CM-984210')
                ->visible(fn ($get) => $get('license_model') === 'perpetual'),
            Forms\Components\Select::make('type')
                ->label('Пакет')
                ->options([
                    'Trial' => 'Пробна (Trial)',
                    'Standard' => 'Стандартна',
                    'Enterprise' => 'Корпоративна (Enterprise)',
                ])
                ->default('Standard')
                ->required(),
            Forms\Components\TextInput::make('max_devices')
                ->label('Кількість робочих місць')
                ->numeric()
                ->default(1)
                ->required(),
            Forms\Components\DatePicker::make('expires_at')
                ->label('Діє до (для підписок)')
                ->default(now()->addYear()),
            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    'active' => 'Активна',
                    'expired' => 'Закінчився термін',
                    'blocked' => 'Заблокована',
                ])
                ->default('active')
                ->required(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('license_key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('client_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('max_devices')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
    ->label('Статус')
    ->badge()
    ->formatStateUsing(fn (string $state): string => match ($state) {
        'active' => 'Активна',
        'expired' => 'Термін минув',
        'blocked' => 'Заблокована',
        default => $state,
    })
    ->color(fn (string $state): string => match ($state) {
        'active' => 'success',
        'expired' => 'warning',
        'blocked' => 'danger',
    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
        ];
    }
}

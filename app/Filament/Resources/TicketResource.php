<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
	
	protected static ?string $modelLabel = 'Тікет';
	protected static ?string $pluralModelLabel = 'Тікети підтримки';
	protected static ?string $navigationLabel = 'Тікети підтримки';

    public static function form(Form $form): Form
	{
    return $form
        ->schema([
            Forms\Components\Section::make('Інформація від клієнта')
                ->schema([
                    Forms\Components\TextInput::make('ticket_number')
                        ->label('Номер тікета')
                        ->default(fn () => 'TCK-' . rand(10000, 99999))
                        ->readOnly()
                        ->required(),
                    Forms\Components\TextInput::make('client_name')
                        ->label("Ім'я клієнта")
                        ->required(),
                    Forms\Components\TextInput::make('client_email')
                        ->label('Email для зв’язку')
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('subject')
                        ->label('Тема звернення')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('message')
                        ->label('Опис проблеми')
                        ->rows(4)
                        ->required()
                        ->columnSpanFull(),
                ])->columns(3),

            Forms\Components\Section::make('Обробка тікета (Техпідтримка)')
                ->schema([
                    Forms\Components\Select::make('priority')
                        ->label('Пріоритет')
                        ->options([
                            'low' => 'Низький (Low)',
                            'medium' => 'Середній (Medium)',
                            'high' => 'Високий (High)',
                            'critical' => 'Критичний (Critical)',
                        ])
                        ->default('medium')
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->label('Статус')
                        ->options([
                            'new' => 'Новий',
                            'in_progress' => 'В роботі',
                            'resolved' => 'Вирішено',
                            'closed' => 'Закрито',
                        ])
                        ->default('new')
                        ->required(),
                    Forms\Components\Select::make('assigned_to')
                        ->label('Відповідальний співробітник')
                        ->relationship('assignedUser', 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Textarea::make('admin_comment')
                        ->label('Внутрішня замітка / Відповідь клієнту')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(3),
        ]);
	}

    public static function table(Table $table): Table
	{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('ticket_number')
                ->label('Номер')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('client_name')
                ->label('Клієнт')
                ->searchable(),
            Tables\Columns\TextColumn::make('subject')
                ->label('Тема')
                ->limit(30)
                ->searchable(),
            Tables\Columns\TextColumn::make('priority')
                ->label('Пріоритет')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'low' => 'Низький',
                    'medium' => 'Середній',
                    'high' => 'Високий',
                    'critical' => 'Критичний',
                    default => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'low' => 'gray',
                    'medium' => 'info',
                    'high' => 'warning',
                    'critical' => 'danger',
                }),
            Tables\Columns\TextColumn::make('status')
                ->label('Статус')
                ->badge()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'new' => 'Новий',
                    'in_progress' => 'В роботі',
                    'resolved' => 'Вирішено',
                    'closed' => 'Закрито',
                    default => $state,
                })
                ->color(fn (string $state): string => match ($state) {
                    'new' => 'danger',
                    'in_progress' => 'warning',
                    'resolved' => 'success',
                    'closed' => 'gray',
                }),
            Tables\Columns\TextColumn::make('assignedUser.name')
                ->label('Відповідальний')
                ->placeholder('Не призначено'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Створено')
                ->dateTime('d.m.Y H:i')
                ->sortable(),
        ])
        ->filters([
            // Сисадминская магия: фильтры по статусу и приоритету в один клик!
            Tables\Filters\SelectFilter::make('status')
                ->label('Фільтр за статусом')
                ->options([
                    'new' => 'Новий',
                    'in_progress' => 'В роботі',
                    'resolved' => 'Вирішено',
                    'closed' => 'Закрито',
                ]),
            Tables\Filters\SelectFilter::make('priority')
                ->label('Фільтр за пріоритетом')
                ->options([
                    'low' => 'Низький',
                    'medium' => 'Середній',
                    'high' => 'Високий',
                    'critical' => 'Критичний',
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}

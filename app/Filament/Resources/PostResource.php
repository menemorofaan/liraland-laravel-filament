<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Новина';
	protected static ?string $pluralModelLabel = 'Новини';
	protected static ?string $navigationLabel = 'Новини';

	public static function form(Form $form): Form
	{
    return $form
        ->schema([
            Forms\Components\TextInput::make('title')
                ->label('Заголовок новини')
                ->required()
                ->live(onBlur: true)
                // Автоматически создает url-slug при вводе заголовка
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

            Forms\Components\TextInput::make('slug')
                ->label('URL-посилання (Slug)')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\FileUpload::make('cover_image')
    ->label('Головне зображення / Обкладинка')
    ->image()
	->disk('public')
    ->directory('news')
    // Включаем встроенный графический редактор:
    ->imageEditor()
    // Настраиваем доступные пропорции для обрезки:
    ->imageEditorAspectRatios([
        null => 'Вільний формат (Без обмежень)',
        '16:9' => '16:9 (Банер для сайту)',
        '4:3' => '4:3 (Стандарт)',
        '1:1' => '1:1 (Квадрат)',
    ])
    ->columnSpanFull(),

            Forms\Components\Textarea::make('summary')
                ->label('Короткий анонс (для списку новин)')
                ->rows(2)
                ->columnSpanFull(),

            Forms\Components\RichEditor::make('content')
				->label('Повний текст новини')
				->fileAttachmentsDisk('public') // Сохранять в публичное хранилище
				->fileAttachmentsDirectory('news-attachments') // Папка для картинок
				->fileAttachmentsVisibility('public')
				->required()
				->columnSpanFull(),

            Forms\Components\DatePicker::make('published_at')
                ->label('Дата публікації')
                ->default(now())
                ->required(),

            Forms\Components\Toggle::make('is_published')
                ->label('Опубліковано на сайті')
                ->default(true),
        ]);
	}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('cover_image'),
                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

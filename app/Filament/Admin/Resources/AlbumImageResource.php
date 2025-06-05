<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlbumImageResource\Pages;
use App\Models\AlbumImage;
use App\Traits\HasImageUpload;
use App\Traits\SimpleFilamentOptimization;
use App\Models\Album;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AlbumImageResource extends Resource
{
    use HasImageUpload, SimpleFilamentOptimization;

    protected static ?string $model = AlbumImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Ảnh Album';

    protected static ?string $modelLabel = 'Ảnh Album';

    protected static ?string $pluralModelLabel = 'Ảnh Albums';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\Select::make('album_id')
                            ->label('Album')
                            ->relationship('album', 'title')
                            ->required()
                            ->searchable()
                            ->preload(),

                        self::createImageUpload(
                            'image_path',
                            'Hình ảnh',
                            'albums/images',
                            1200,
                            800,
                            5120,
                            'Ảnh sẽ được tự động tối ưu và chuyển sang WebP',
                            ['16:9', '4:3', '1:1'],
                            true,
                            true
                        ),

                        Forms\Components\TextInput::make('alt_text')
                            ->label('Alt text')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('caption')
                            ->label('Chú thích')
                            ->rows(3),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Cài đặt')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hoạt động',
                                'inactive' => 'Không hoạt động',
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Ảnh nổi bật')
                            ->default(false),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Hình ảnh')
                    ->size(60)
                    ->square(),

                Tables\Columns\TextColumn::make('album.title')
                    ->label('Album')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alt_text')
                    ->label('Alt text')
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('caption')
                    ->label('Chú thích')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                        default => $state,
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Nổi bật')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('album_id')
                    ->label('Album')
                    ->relationship('album', 'title'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Nổi bật'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order')
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListAlbumImages::route('/'),
            'create' => Pages\CreateAlbumImage::route('/create'),
            'edit' => Pages\EditAlbumImage::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'active')->count();
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return array (
  0 => 'id',
  1 => 'album_id',
  2 => 'image_path',
  3 => 'alt_text',
  4 => 'caption',
  5 => 'order',
  6 => 'status',
  7 => 'created_at',
);
    }

    /**
     * Lấy relationships cần thiết cho form
     */
    protected static function getFormRelationships(): array
    {
        return [
            'album' => function($query) {
                $query->select(['id,title']);
            }
        ];
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return array (
  0 => 'alt_text',
  1 => 'caption',
);
    }
}
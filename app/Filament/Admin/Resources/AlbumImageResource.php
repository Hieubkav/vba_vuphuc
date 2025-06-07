<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AlbumImageResource\Pages;
use App\Filament\Admin\Resources\AlbumResource;
use App\Models\AlbumImage;
use App\Traits\HasImageUpload;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AlbumImageResource extends Resource
{
    use HasImageUpload;

    protected static ?string $model = AlbumImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Ảnh Album';

    protected static ?string $modelLabel = 'Ảnh Album';

    protected static ?string $pluralModelLabel = 'Ảnh Albums';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin ảnh')
                    ->schema([
                        Forms\Components\Select::make('album_id')
                            ->label('Album')
                            ->relationship('album', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->helperText('Chọn album để thêm ảnh vào'),

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
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Hình ảnh')
                    ->size(80)
                    ->square(),

                Tables\Columns\TextColumn::make('album.title')
                    ->label('Album')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('album_id')
                    ->label('Album')
                    ->relationship('album', 'title'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('manage_albums')
                    ->label('Quản lý album')
                    ->icon('heroicon-o-book-open')
                    ->color('info')
                    ->url(fn () => AlbumResource::getUrl('index')),
            ])
            ->actions([
                Tables\Actions\Action::make('view_album')
                    ->label('Xem album')
                    ->icon('heroicon-o-book-open')
                    ->color('info')
                    ->url(fn ($record) => AlbumResource::getUrl('edit', ['record' => $record->album_id]))
                    ->visible(fn ($record) => $record->album_id),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
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


}
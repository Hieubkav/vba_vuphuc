<?php

namespace App\Filament\Admin\Resources\AlbumResource\RelationManagers;

use App\Filament\Admin\Resources\AlbumImageResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AlbumImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Ảnh trong album';

    protected static ?string $modelLabel = 'ảnh';

    protected static ?string $pluralModelLabel = 'ảnh';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin ảnh')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Hình ảnh')
                            ->image()
                            ->directory('albums/images')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Ảnh sẽ được tự động tối ưu và chuyển sang WebP')
                            ->required(),
                    ])
                    ->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('image_path')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Hình ảnh')
                    ->size(80)
                    ->square(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm ảnh mới'),
                Tables\Actions\Action::make('view_all_images')
                    ->label('Xem tất cả ảnh')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->url(fn () => AlbumImageResource::getUrl('index', [
                        'tableFilters[album_id][value]' => $this->ownerRecord->id
                    ])),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

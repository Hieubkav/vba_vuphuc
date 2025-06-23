<?php

namespace App\Filament\Admin\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Traits\HasImageUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class PostImagesRelationManager extends RelationManager
{
    use HasImageUpload;

    protected static string $relationship = 'images';

    protected static ?string $title = 'Hình ảnh bài viết';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->createGalleryUpload(
                    'image_link',
                    'Hình ảnh',
                    'posts/gallery',
                    800,
                    600
                )->required()->columnSpanFull(),

                Select::make('image_type')
                    ->label('Loại hình ảnh')
                    ->options([
                        'gallery' => '🖼️ Thư viện',
                        'inline' => '📄 Nội dung',
                        'featured' => '⭐ Nổi bật',
                        'thumbnail' => '🏷️ Thumbnail',
                    ])
                    ->default('gallery')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('title')
                    ->label('Tiêu đề ảnh')
                    ->maxLength(255)
                    ->columnSpan(1),

                TextInput::make('alt_text')
                    ->label('Alt text (SEO)')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('caption')
                    ->label('Chú thích')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Mô tả chi tiết')
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpanFull(),

                TextInput::make('order')
                    ->label('Thứ tự hiển thị')
                    ->integer()
                    ->default(0)
                    ->minValue(0)
                    ->columnSpan(1),

                Toggle::make('status')
                    ->label('Hiển thị')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpan(1),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('alt_text')
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->width(80),

                ImageColumn::make('image_link')
                    ->label('Hình ảnh')
                    ->height(80)
                    ->width(120),

                TextColumn::make('image_type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'gallery' => 'primary',
                        'inline' => 'success',
                        'featured' => 'warning',
                        'thumbnail' => 'secondary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'gallery' => '🖼️ Thư viện',
                        'inline' => '📄 Nội dung',
                        'featured' => '⭐ Nổi bật',
                        'thumbnail' => '🏷️ Thumbnail',
                        default => $state,
                    }),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->limit(30)
                    ->searchable()
                    ->description(fn ($record): string => $record->alt_text ? "Alt: {$record->alt_text}" : ''),

                TextColumn::make('caption')
                    ->label('Chú thích')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('dimensions')
                    ->label('Kích thước')
                    ->getStateUsing(fn ($record) => $record->getDimensions())
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('file_size')
                    ->label('Dung lượng')
                    ->getStateUsing(fn ($record) => $record->getFormattedFileSize())
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('status')
                    ->label('Hiển thị')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('image_type')
                    ->label('Loại hình ảnh')
                    ->options([
                        'gallery' => 'Thư viện',
                        'inline' => 'Nội dung',
                        'featured' => 'Nổi bật',
                        'thumbnail' => 'Thumbnail',
                    ]),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Trạng thái hiển thị')
                    ->boolean()
                    ->trueLabel('Đang hiển thị')
                    ->falseLabel('Đã ẩn')
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm hình ảnh'),
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
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }
}

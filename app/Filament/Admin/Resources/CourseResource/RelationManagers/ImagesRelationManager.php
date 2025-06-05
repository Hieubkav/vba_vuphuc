<?php

namespace App\Filament\Admin\Resources\CourseResource\RelationManagers;

use App\Traits\HasImageUpload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    use HasImageUpload;

    protected static string $relationship = 'images';

    protected static ?string $title = 'Hình ảnh khóa học';

    protected static ?string $modelLabel = 'hình ảnh';

    protected static ?string $pluralModelLabel = 'hình ảnh';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Tiêu đề ảnh')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Mô tả ngắn gọn về hình ảnh'),

                $this->createGalleryUpload(
                    'image_path',
                    'Hình ảnh',
                    'courses/gallery',
                    1200,
                    800
                )->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Mô tả chi tiết')
                    ->rows(3)
                    ->maxLength(500)
                    ->helperText('Mô tả chi tiết về hình ảnh (tùy chọn)'),

                Forms\Components\Toggle::make('is_main')
                    ->label('Ảnh đại diện')
                    ->helperText('Đặt làm ảnh đại diện chính của khóa học'),

                Forms\Components\TextInput::make('order')
                    ->label('Thứ tự hiển thị')
                    ->numeric()
                    ->default(0)
                    ->helperText('Số nhỏ hơn sẽ hiển thị trước'),

                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Ẩn',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Ảnh')
                    ->size(80)
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('is_main')
                    ->label('Ảnh chính')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Ẩn',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Ẩn',
                    ]),

                Tables\Filters\TernaryFilter::make('is_main')
                    ->label('Ảnh chính'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Thêm ảnh'),
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
            ->reorderable('order')
            ->defaultSort('order', 'asc');
    }
}

<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SliderResource\Pages;
use App\Models\Slider;
use App\Services\ImageService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?string $navigationLabel = 'Slider Banner';

    protected static ?string $modelLabel = 'slider banner';

    protected static ?string $pluralModelLabel = 'slider banner';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin slider')
                    ->schema([
                        FileUpload::make('image_link')
                            ->label('Hình ảnh Hero Banner')
                            ->helperText('Kích thước tối ưu: 1920x1080px (16:9) cho desktop, 800x450px cho mobile. Ảnh sẽ được tự động chuyển sang WebP.')
                            ->required()
                            ->image()
                            ->directory('sliders/banners')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth(1920)
                            ->imageResizeTargetHeight(1080)
                            ->maxSize(8192) // Tăng lên 8MB để cho phép ảnh chất lượng cao
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(function ($file, $get) {
                                $imageService = app(\App\Services\ImageService::class);
                                $title = $get('title') ?? 'hero-banner';
                                return $imageService->saveImage(
                                    $file,
                                    'sliders/banners',
                                    1920,  // width - tối ưu cho desktop
                                    1080,  // height - tỷ lệ 16:9
                                    85,    // quality - cân bằng giữa chất lượng và dung lượng
                                    "hero-banner-{$title}" // SEO-friendly name
                                );
                            })
                            ->columnSpanFull(),

                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->maxLength(255)
                            ->columnSpan(1),

                        TextInput::make('link')
                            ->label('Liên kết')
                            ->url()
                            ->maxLength(500)
                            ->columnSpan(1),

                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),

                        TextInput::make('alt_text')
                            ->label('Alt text (SEO)')
                            ->helperText('Để trống để tự động tạo từ tiêu đề. Alt text giúp tối ưu SEO và accessibility.')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Cấu hình hiển thị')
                    ->schema([
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
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->width(80),

                ImageColumn::make('image_link')
                    ->label('Hình ảnh')
                    ->height(60)
                    ->width(100),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('description')
                    ->label('Mô tả')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('link')
                    ->label('Liên kết')
                    ->limit(30)
                    ->url(fn ($record) => $record->link)
                    ->openUrlInNewTab(),

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
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Trạng thái hiển thị')
                    ->boolean()
                    ->trueLabel('Đang hiển thị')
                    ->falseLabel('Đã ẩn')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\DeleteAction::make()
                    ->label('Xóa'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}

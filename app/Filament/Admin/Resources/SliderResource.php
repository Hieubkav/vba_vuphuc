<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SliderResource\Pages;
use App\Models\Slider;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Support\Str;
class SliderResource extends Resource
{

    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?string $navigationLabel = 'Ảnh cuộn';

    protected static ?string $modelLabel = 'ảnh cuộn';

    protected static ?string $pluralModelLabel = 'ảnh cuộn';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin slider')
                    ->schema([
                        FileUpload::make('image_link')
                            ->label('Hình ảnh Hero Banner')
                            ->helperText('Ảnh sẽ được giữ nguyên kích thước gốc và tối ưu hóa thành WebP. Kích thước tối đa: 8MB. Nhấn nút "Edit" để chỉnh sửa ảnh.')
                            ->required()
                            ->image()
                            ->directory('sliders/banners')
                            ->visibility('public')
                            ->maxSize(8192)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '21:9',
                                '4:3',
                                '1:1',
                            ])
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->saveUploadedFileUsing(function ($file, $get) {
                                // Fallback an toàn: nếu có lỗi thì dùng Laravel default
                                try {
                                    $title = $get('title') ?? 'hero-banner';
                                    $customName = 'hero-banner-' . Str::slug($title);

                                    // Sử dụng ConvertImageToWebp action - giữ nguyên kích thước gốc
                                    $result = \App\Actions\ConvertImageToWebp::run(
                                        $file,
                                        'sliders/banners',
                                        $customName,
                                        0, // width = 0 nghĩa là không resize
                                        0  // height = 0 nghĩa là không resize
                                    );

                                    // Nếu convert thành công thì return, không thì fallback
                                    return $result ?: $file->store('sliders/banners', 'public');
                                } catch (\Exception $e) {
                                    // Fallback: lưu file gốc nếu có lỗi
                                    return $file->store('sliders/banners', 'public');
                                }
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
        return (string) static::getModel()::where('status', 'active')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    /**
     * Lấy danh sách cột cần thiết cho table
     */
    protected static function getTableColumns(): array
    {
        return array (
  0 => 'id',
  1 => 'title',
  2 => 'image_link',
  3 => 'link',
  4 => 'description',
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
        return [];
    }

    /**
     * Lấy các cột có thể search
     */
    protected static function getSearchableColumns(): array
    {
        return array (
  0 => 'title',
  1 => 'description',
);
    }
}
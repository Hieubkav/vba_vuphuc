<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers\ProductImagesRelationManager;
use App\Models\Product;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'sản phẩm';

    protected static ?string $pluralModelLabel = 'sản phẩm';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Quản lý sản phẩm';

    protected static ?string $navigationLabel = 'Sản phẩm';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin sản phẩm')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên sản phẩm')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Đường dẫn')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Select::make('category_id')
                            ->label('Danh mục')
                            ->relationship('productCategory', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('sku')
                            ->label('Mã sản phẩm (SKU)')
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        TextInput::make('brand')
                            ->label('Thương hiệu')
                            ->maxLength(255),

                        TextInput::make('unit')
                            ->label('Đơn vị tính')
                            ->maxLength(50),
                    ])->columns(2),

                Section::make('Thông tin giá')
                    ->schema([
                        TextInput::make('price')
                            ->label('Giá bán')
                            // ->required()
                            ->numeric()
                            ->prefix('VNĐ'),

                        TextInput::make('compare_price')
                            ->label('Giá khuyến mãi')
                            ->numeric()
                            ->prefix('VNĐ')
                            ->nullable()
                            ->lte('price'),

                        TextInput::make('stock')
                            ->label('Số lượng trong kho')
                            ->numeric()
                            ->default(1)
                            ->minValue(0),
                    ])->columns(3),

                Section::make('Mô tả sản phẩm')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Mô tả')
                            // ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('products')
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Tiêu đề SEO')
                            ->maxLength(255),

                        TextInput::make('seo_description')
                            ->label('Mô tả SEO')
                            ->maxLength(500),

                        TextInput::make('og_image_link')
                            ->label('Ảnh OG (Open Graph)')
                            ->maxLength(255),
                    ])->columns(1),

                Section::make('Cấu hình hiển thị')
                    ->schema([
                        TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->integer()
                            ->default(0),

                        Toggle::make('is_hot')
                            ->label('Nổi bật')
                            ->default(false)
                            ->onColor('success')
                            ->offColor('danger'),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hiển thị',
                                'inactive' => 'Ẩn',
                            ])
                            ->default('active')
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable(),

                ImageColumn::make('thumbnail')
                    ->label('Hình đại diện')
                    ->defaultImageUrl(fn() => asset('images/default-product.jpg'))
                    ->circular()
                    ->getStateUsing(function (Product $record) {
                        $firstImage = $record->productImages()->orderBy('order', 'asc')->first();
                        return $firstImage ? $firstImage->image_link : null;
                    }),

                TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('sku')
                    ->label('Mã sản phẩm')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Giá bán')
                    ->money('VND')
                    ->sortable(),

                TextColumn::make('productCategory.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Tồn kho')
                    ->sortable(),

                IconColumn::make('is_hot')
                    ->label('Nổi bật')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    })
                    ->sortable(),
            ])
            ->reorderable('order')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('productCategory', 'name')
                    ->label('Danh mục'),

                Tables\Filters\TernaryFilter::make('is_hot')
                    ->label('Nổi bật'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),
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
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            ProductImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['productCategory', 'productImages']);
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
<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use App\Traits\HasImageUpload;
use App\Traits\SimpleFilamentOptimization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    use HasImageUpload, SimpleFilamentOptimization;

    protected static ?string $model = Testimonial::class;

    protected static ?string $modelLabel = 'lời khen';

    protected static ?string $pluralModelLabel = 'lời khen khách hàng';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin khách hàng')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên khách hàng')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('position')
                            ->label('Chức vụ')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('company')
                            ->label('Công ty')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('location')
                            ->label('Địa điểm')
                            ->maxLength(255),

                        self::createLogoUpload(
                            'avatar',
                            'Ảnh đại diện',
                            'testimonials/avatars',
                            200
                        ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Nội dung đánh giá')
                    ->schema([
                        Forms\Components\Textarea::make('content')
                            ->label('Nội dung lời khen')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('rating')
                            ->label('Đánh giá (sao)')
                            ->options([
                                1 => '1 sao',
                                2 => '2 sao',
                                3 => '3 sao',
                                4 => '4 sao',
                                5 => '5 sao',
                            ])
                            ->default(5)
                            ->required(),
                    ]),

                Forms\Components\Section::make('Cài đặt hiển thị')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->helperText('Số nhỏ hơn sẽ hiển thị trước'),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Hiển thị',
                                'inactive' => 'Ẩn',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('Ảnh')
                    ->circular()
                    ->defaultImageUrl(fn($record) => $record->avatar_url),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên khách hàng')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('Chức vụ')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('company')
                    ->label('Công ty')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Đánh giá')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => $state === 'active' ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state === 'active' ? 'Hiển thị' : 'Ẩn'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hiển thị',
                        'inactive' => 'Ẩn',
                    ]),

                Tables\Filters\SelectFilter::make('rating')
                    ->label('Đánh giá')
                    ->options([
                        1 => '1 sao',
                        2 => '2 sao',
                        3 => '3 sao',
                        4 => '4 sao',
                        5 => '5 sao',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('order')
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
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
  1 => 'name',
  2 => 'position',
  3 => 'company',
  4 => 'content',
  5 => 'rating',
  6 => 'order',
  7 => 'status',
  8 => 'created_at',
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
  0 => 'name',
  1 => 'position',
  2 => 'company',
  3 => 'content',
);
    }
}
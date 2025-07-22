<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestimonialResource\Pages;
use App\Helpers\AvatarHelper;
use App\Models\Testimonial;
use App\Traits\HasImageUpload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    use HasImageUpload;

    protected static ?string $model = Testimonial::class;

    protected static ?string $modelLabel = 'lời khen';

    protected static ?string $pluralModelLabel = 'lời khen khách hàng';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $navigationGroup = 'Quản lý nội dung';

    protected static ?int $navigationSort = 14;

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

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->helperText('Email khách hàng (dành cho feedback từ website)'),

                        Forms\Components\TextInput::make('position')
                            ->label('Chức vụ')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('company')
                            ->label('Công ty')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('location')
                            ->label('Địa điểm')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('avatar')
                            ->label('Ảnh đại diện')
                            ->image()
                            ->directory('testimonials/avatars')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Ảnh sẽ được tự động tối ưu thành WebP với tên SEO-friendly. Feedback từ khách hàng sẽ có avatar chữ cái tự động.')
                            ->saveUploadedFileUsing(function ($file, $get) {
                                $customerName = $get('name') ?: 'khach-hang';
                                $customName = 'avatar-' . $customerName;

                                return \App\Actions\ConvertImageToWebp::run(
                                    $file,
                                    'testimonials/avatars',
                                    $customName,
                                    400,
                                    400
                                );
                            })
                            ->dehydrated(fn ($state) => filled($state)), // Chỉ save khi có file upload
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
                                'pending' => 'Chờ duyệt',
                            ])
                            ->default('pending')
                            ->required()
                            ->helperText('Feedback từ website sẽ có trạng thái "Chờ duyệt" ban đầu'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('avatar')
                    ->label('Avatar')
                    ->view('filament.tables.columns.testimonial-avatar'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên khách hàng')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable()
                    ->copyable()
                    ->placeholder('Không có'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Loại')
                    ->getStateUsing(function ($record) {
                        return $record->email ? 'Feedback KH' : 'Testimonial';
                    })
                    ->badge()
                    ->color(fn($state) => $state === 'Feedback KH' ? 'info' : 'success'),

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
                    ->color(fn($state) => match($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'inactive' => 'danger',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'active' => 'Hiển thị',
                        'pending' => 'Chờ duyệt',
                        'inactive' => 'Ẩn',
                        default => $state
                    }),

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
                        'pending' => 'Chờ duyệt',
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
        $pendingCount = static::getModel()::where('status', 'pending')->count();
        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'pending')->count();
        return $pendingCount > 0 ? 'warning' : null;
    }


}
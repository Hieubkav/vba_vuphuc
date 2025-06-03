<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Quản lý bán hàng';

    protected static ?string $navigationLabel = 'Đơn hàng';

    protected static ?string $modelLabel = 'Đơn hàng';

    protected static ?string $pluralModelLabel = 'Đơn hàng';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin đơn hàng')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Mã đơn hàng')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->default(fn () => \App\Models\Order::generateOrderNumber())
                            ->columnSpan(1),

                        Select::make('customer_id')
                            ->label('Khách hàng')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->columnSpan(1),

                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'processing' => 'Đang xử lý',
                                'shipping' => 'Đang giao hàng',
                                'delivered' => 'Đã giao hàng',
                                'cancelled' => 'Đã hủy',
                                'refunded' => 'Đã hoàn tiền',
                            ])
                            ->required()
                            ->default('pending')
                            ->columnSpan(1),

                        Select::make('payment_status')
                            ->label('Trạng thái thanh toán')
                            ->options([
                                'pending' => 'Chờ thanh toán',
                                'paid' => 'Đã thanh toán',
                                'failed' => 'Thanh toán thất bại',
                                'refunded' => 'Đã hoàn tiền',
                            ])
                            ->required()
                            ->default('pending')
                            ->columnSpan(1),
                    ])->columns(2),

                Section::make('Thông tin giao hàng')
                    ->schema([
                        TextInput::make('shipping_name')
                            ->label('Tên người nhận')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        TextInput::make('shipping_phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->columnSpan(1),

                        TextInput::make('shipping_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Textarea::make('shipping_address')
                            ->label('Địa chỉ giao hàng')
                            ->required()
                            ->rows(2)
                            ->columnSpan(1),
                    ])->columns(2),

                Section::make('Thông tin thanh toán')
                    ->schema([
                        TextInput::make('total')
                            ->label('Tổng tiền')
                            ->numeric()
                            ->prefix('₫')
                            ->required()
                            ->default(0)
                            ->minValue(0)
                            ->step(0.01)
                            ->columnSpan(2),

                        Select::make('payment_method')
                            ->label('Phương thức thanh toán')
                            ->options([
                                'cod' => 'Thanh toán khi nhận hàng',
                                'bank_transfer' => 'Chuyển khoản ngân hàng',
                                'online' => 'Thanh toán online',
                            ])
                            ->required()
                            ->columnSpan(2),
                    ])->columns(2),

                Section::make('Ghi chú')
                    ->schema([
                        Textarea::make('note')
                            ->label('Ghi chú đơn hàng')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Mã đơn hàng')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('customer.name')
                    ->label('Khách hàng')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('shipping_name')
                    ->label('Người nhận')
                    ->searchable()
                    ->limit(20),

                TextColumn::make('total')
                    ->label('Tổng tiền')
                    ->money('VND')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'primary' => 'processing',
                        'secondary' => 'shipping',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                        'gray' => 'refunded',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ xử lý',
                        'confirmed' => 'Đã xác nhận',
                        'processing' => 'Đang xử lý',
                        'shipping' => 'Đang giao hàng',
                        'delivered' => 'Đã giao hàng',
                        'cancelled' => 'Đã hủy',
                        'refunded' => 'Đã hoàn tiền',
                        default => $state,
                    }),

                BadgeColumn::make('payment_status')
                    ->label('Thanh toán')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chờ thanh toán',
                        'paid' => 'Đã thanh toán',
                        'failed' => 'Thất bại',
                        'refunded' => 'Đã hoàn tiền',
                        default => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Ngày đặt')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái đơn hàng')
                    ->options([
                        'pending' => 'Chờ xử lý',
                        'confirmed' => 'Đã xác nhận',
                        'processing' => 'Đang xử lý',
                        'shipping' => 'Đang giao hàng',
                        'delivered' => 'Đã giao hàng',
                        'cancelled' => 'Đã hủy',
                        'refunded' => 'Đã hoàn tiền',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Trạng thái thanh toán')
                    ->options([
                        'pending' => 'Chờ thanh toán',
                        'paid' => 'Đã thanh toán',
                        'failed' => 'Thanh toán thất bại',
                        'refunded' => 'Đã hoàn tiền',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Xem'),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Xóa đã chọn'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}

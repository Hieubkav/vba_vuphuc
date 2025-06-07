<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CourseGroupResource\Pages;
use App\Models\CourseGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CourseGroupResource extends Resource
{

    protected static ?string $model = CourseGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'nhóm khóa học';

    protected static ?string $pluralModelLabel = 'nhóm khóa học';

    protected static ?string $navigationLabel = 'Nhóm khóa học';

    protected static ?string $navigationGroup = 'Quản lý khóa học';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên nhóm')
                            ->required()
                            ->maxLength(255),



                        Forms\Components\Textarea::make('description')
                            ->label('Mô tả nhóm')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Cấu hình nhóm')
                    ->schema([
                        Forms\Components\TextInput::make('group_link')
                            ->label('Link nhóm')
                            ->url()
                            ->placeholder('https://www.facebook.com/groups/...')
                            ->maxLength(255),

                        Forms\Components\Select::make('group_type')
                            ->label('Loại nhóm')
                            ->required()
                            ->options([
                                'facebook' => 'Facebook',
                                'zalo' => 'Zalo',
                                'telegram' => 'Telegram',
                            ])
                            ->default('facebook'),


                    ])->columns(2),

                Forms\Components\Section::make('Thành viên')
                    ->schema([
                        Forms\Components\TextInput::make('max_members')
                            ->label('Số thành viên tối đa')
                            ->numeric()
                            ->placeholder('Để trống nếu không giới hạn'),

                        Forms\Components\TextInput::make('current_members')
                            ->label('Số thành viên hiện tại')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->placeholder('Để trống sẽ mặc định là 0'),
                    ])->columns(2),

                Forms\Components\Section::make('Cài đặt')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->required()
                            ->numeric()
                            ->default(0),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->required()
                            ->options([
                                'active' => 'Hoạt động',
                                'inactive' => 'Không hoạt động',
                            ])
                            ->default('active'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Tên nhóm')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group_type')
                    ->label('Loại nhóm')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'facebook' => 'info',
                        'zalo' => 'warning',
                        'telegram' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'facebook' => 'Facebook',
                        'zalo' => 'Zalo',
                        'telegram' => 'Telegram',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('members')
                    ->label('Thành viên')
                    ->getStateUsing(function ($record) {
                        $current = $record->current_members ?? 0;
                        if ($record->max_members) {
                            return "{$current}/{$record->max_members}";
                        }
                        return $current;
                    })
                    ->sortable(['current_members']),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group_type')
                    ->label('Loại nhóm')
                    ->options([
                        'facebook' => 'Facebook',
                        'zalo' => 'Zalo',
                        'telegram' => 'Telegram',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Hoạt động',
                        'inactive' => 'Không hoạt động',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_frontend')
                    ->label('Xem trên website')
                    ->icon('heroicon-o-globe-alt')
                    ->color('info')
                    ->url(fn () => route('course-groups.index'))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()
                    ->label('Sửa'),
                Tables\Actions\Action::make('view_group')
                    ->label('Xem nhóm chat')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn ($record) => $record->group_link)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->group_link)),
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
            'index' => Pages\ListCourseGroups::route('/'),
            'create' => Pages\CreateCourseGroup::route('/create'),
            'edit' => Pages\EditCourseGroup::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('status', 'active')->count();
    }

}
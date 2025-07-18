<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use App\Models\CatPost;
use App\Models\Post;
use App\Models\CatCourse;
use App\Models\Course;
use App\Models\CourseGroup;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;


class MenuItemResource extends Resource
{

    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Cấu hình hệ thống';

    protected static ?string $navigationLabel = 'Menu điều hướng';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Menu';

    protected static ?int $navigationSort = 51;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin menu')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Menu cha')
                            ->options(MenuItem::all()->pluck('label', 'id'))
                            ->searchable()
                            ->nullable()
                            ->columnSpan(1),

                        TextInput::make('label')
                            ->label('Nhãn menu')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Select::make('type')
                            ->label('Loại menu')
                            ->options([
                                'link' => 'Liên kết trực tiếp',
                                'cat_post' => 'Danh mục bài viết',
                                'all_posts' => 'Tất cả bài viết',
                                'post' => 'Bài viết',
                                'cat_course' => 'Danh mục khóa học',
                                'all_courses' => 'Tất cả khóa học',
                                'course' => 'Khóa học',
                                'course_group' => 'Nhóm khóa học',
                                'display_only' => 'Chỉ hiển thị (không dẫn đến đâu)',
                            ])
                            ->required()
                            ->reactive()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Cấu hình liên kết')
                    ->schema([
                        TextInput::make('link')
                            ->label('Liên kết URL')
                            ->url()
                            ->maxLength(500)
                            ->visible(fn ($get) => $get('type') === 'link')
                            ->required(fn ($get) => $get('type') === 'link'),

                        Select::make('cat_post_id')
                            ->label('Danh mục bài viết')
                            ->options(CatPost::all()->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('type') === 'cat_post')
                            ->required(fn ($get) => $get('type') === 'cat_post'),

                        Select::make('post_id')
                            ->label('Bài viết')
                            ->options(Post::all()->pluck('title', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('type') === 'post')
                            ->required(fn ($get) => $get('type') === 'post'),

                        Select::make('cat_course_id')
                            ->label('Danh mục khóa học')
                            ->options(CatCourse::all()->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('type') === 'cat_course')
                            ->required(fn ($get) => $get('type') === 'cat_course'),

                        Select::make('course_id')
                            ->label('Khóa học')
                            ->options(Course::all()->pluck('title', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('type') === 'course')
                            ->required(fn ($get) => $get('type') === 'course'),

                        Select::make('course_group_id')
                            ->label('Nhóm khóa học')
                            ->options(CourseGroup::all()->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn ($get) => $get('type') === 'course_group')
                            ->required(fn ($get) => $get('type') === 'course_group'),

                    ]),

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
            ->query(
                // Sắp xếp đơn giản theo order
                MenuItem::query()
                    ->with('parent')
                    ->orderBy('order')
            )
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->width(80),

                TextColumn::make('label')
                    ->label('Nhãn menu')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->formatStateUsing(function ($record) {
                        // Hiển thị cấu trúc phân cấp với ký tự đặc biệt và màu sắc
                        if ($record->parent_id) {
                            return '└─ ' . $record->label;
                        }
                        return $record->label;
                    })
                    ->color(function ($record) {
                        // Màu khác nhau cho parent và child
                        return $record->parent_id ? 'gray' : 'primary';
                    }),

                TextColumn::make('parent.label')
                    ->label('Menu cha')
                    ->searchable()
                    ->placeholder('Menu gốc'),

                TextColumn::make('type')
                    ->label('Loại')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'link' => 'gray',
                        'cat_post' => 'info',
                        'all_posts' => 'blue',
                        'post' => 'success',
                        'cat_course' => 'warning',
                        'all_courses' => 'orange',
                        'course' => 'emerald',
                        'course_group' => 'indigo',
                        'display_only' => 'purple',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'link' => 'Liên kết',
                        'cat_post' => 'Danh mục bài viết',
                        'all_posts' => 'Tất cả bài viết',
                        'post' => 'Bài viết',
                        'cat_course' => 'Danh mục khóa học',
                        'all_courses' => 'Tất cả khóa học',
                        'course' => 'Khóa học',
                        'course_group' => 'Nhóm khóa học',
                        'display_only' => 'Chỉ hiển thị',
                        default => $state,
                    }),

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
                Tables\Filters\SelectFilter::make('type')
                    ->label('Loại menu')
                    ->options([
                        'link' => 'Liên kết trực tiếp',
                        'cat_post' => 'Danh mục bài viết',
                        'all_posts' => 'Tất cả bài viết',
                        'post' => 'Bài viết',
                        'cat_course' => 'Danh mục khóa học',
                        'all_courses' => 'Tất cả khóa học',
                        'course' => 'Khóa học',
                        'course_group' => 'Nhóm khóa học',
                        'display_only' => 'Chỉ hiển thị (không dẫn đến đâu)',
                    ]),

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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
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


}

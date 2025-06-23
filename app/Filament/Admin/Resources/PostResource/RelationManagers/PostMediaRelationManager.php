<?php

namespace App\Filament\Admin\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ToggleColumn;

class PostMediaRelationManager extends RelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $title = 'Media & Tài liệu';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Select::make('media_type')
                            ->label('Loại media')
                            ->options([
                                'video' => '🎥 Video',
                                'audio' => '🎵 Audio', 
                                'document' => '📄 Tài liệu',
                                'embed' => '🔗 Nhúng (YouTube, Vimeo)',
                                'download' => '⬇️ File tải về',
                            ])
                            ->required()
                            ->live()
                            ->columnSpan(1),

                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Textarea::make('description')
                            ->label('Mô tả')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('alt_text')
                            ->label('Alt text (SEO)')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('File & Media')
                    ->schema([
                        FileUpload::make('file_path')
                            ->label('Upload file')
                            ->directory('posts/media')
                            ->visibility('public')
                            ->maxSize(102400) // 100MB
                            ->acceptedFileTypes([
                                'video/mp4', 'video/webm', 'video/ogg',
                                'audio/mpeg', 'audio/wav', 'audio/ogg',
                                'application/pdf', 'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/zip', 'application/x-rar-compressed'
                            ])
                            ->visible(fn (Get $get) => in_array($get('media_type'), ['video', 'audio', 'document', 'download']))
                            ->required(fn (Get $get) => in_array($get('media_type'), ['video', 'audio', 'document', 'download']))
                            ->columnSpanFull(),

                        TextInput::make('embed_url')
                            ->label('URL nhúng')
                            ->placeholder('https://www.youtube.com/watch?v=... hoặc https://vimeo.com/...')
                            ->url()
                            ->visible(fn (Get $get) => $get('media_type') === 'embed')
                            ->required(fn (Get $get) => $get('media_type') === 'embed')
                            ->columnSpanFull(),

                        Textarea::make('embed_code')
                            ->label('Mã nhúng (tùy chọn)')
                            ->placeholder('<iframe src="..." width="560" height="315"></iframe>')
                            ->rows(4)
                            ->visible(fn (Get $get) => $get('media_type') === 'embed')
                            ->columnSpanFull(),

                        FileUpload::make('thumbnail_path')
                            ->label('Ảnh thumbnail')
                            ->image()
                            ->directory('posts/thumbnails')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->imageEditor()
                            ->visible(fn (Get $get) => in_array($get('media_type'), ['video', 'audio']))
                            ->columnSpanFull(),
                    ]),

                Section::make('Cài đặt')
                    ->schema([
                        TextInput::make('duration')
                            ->label('Thời lượng (giây)')
                            ->numeric()
                            ->visible(fn (Get $get) => in_array($get('media_type'), ['video', 'audio']))
                            ->columnSpan(1),

                        TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),

                        Toggle::make('status')
                            ->label('Hiển thị')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->columnSpan(2),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->width(80),

                BadgeColumn::make('media_type')
                    ->label('Loại')
                    ->colors([
                        'primary' => 'video',
                        'success' => 'audio',
                        'warning' => 'document', 
                        'info' => 'embed',
                        'secondary' => 'download',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'video' => '🎥 Video',
                        'audio' => '🎵 Audio',
                        'document' => '📄 Tài liệu',
                        'embed' => '🔗 Nhúng',
                        'download' => '⬇️ Tải về',
                        default => $state,
                    }),

                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->limit(30),

                TextColumn::make('file_size')
                    ->label('Dung lượng')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $bytes = $state;
                        $units = ['B', 'KB', 'MB', 'GB'];
                        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                            $bytes /= 1024;
                        }
                        return round($bytes, 2) . ' ' . $units[$i];
                    }),

                TextColumn::make('duration')
                    ->label('Thời lượng')
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $minutes = floor($state / 60);
                        $seconds = $state % 60;
                        return sprintf('%02d:%02d', $minutes, $seconds);
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
                Tables\Filters\SelectFilter::make('media_type')
                    ->label('Loại media')
                    ->options([
                        'video' => 'Video',
                        'audio' => 'Audio',
                        'document' => 'Tài liệu', 
                        'embed' => 'Nhúng',
                        'download' => 'Tải về',
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
                    ->label('Thêm media'),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Xem')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn ($record) => $record->getFileUrl())
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->getFileUrl()),

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

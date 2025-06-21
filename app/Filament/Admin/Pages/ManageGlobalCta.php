<?php

namespace App\Filament\Admin\Pages;

use App\Models\WebDesign;
use App\Services\GlobalCtaService;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class ManageGlobalCta extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'CTA Toàn cục';
    protected static ?string $title = 'Quản lý CTA Toàn cục';
    protected static string $view = 'filament.admin.pages.manage-global-cta';
    protected static ?string $navigationGroup = 'Quản lý nội dung';
    protected static ?int $navigationSort = 5;

    public ?array $data = [];

    public function mount(): void
    {
        $webDesign = WebDesign::first();
        
        $this->form->fill([
            'enabled' => $webDesign->homepage_cta_enabled ?? true,
            'title' => $webDesign->homepage_cta_title ?? 'Bắt đầu hành trình với VBA Vũ Phúc',
            'description' => $webDesign->homepage_cta_description ?? 'Khám phá các khóa học VBA chất lượng cao và chuyên sâu. Học tập hiệu quả, hỗ trợ tận tâm từ giảng viên.',
            'primary_button_text' => $webDesign->homepage_cta_primary_button_text ?? 'Xem khóa học',
            'primary_button_url' => $webDesign->homepage_cta_primary_button_url ?? '/courses',
            'secondary_button_text' => $webDesign->homepage_cta_secondary_button_text ?? 'Đăng ký học',
            'secondary_button_url' => $webDesign->homepage_cta_secondary_button_url ?? '/students/register',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cấu hình CTA Toàn cục')
                    ->description('CTA này sẽ hiển thị trên tất cả các trang của website')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('enabled')
                                ->label('🎯 Hiển thị CTA')
                                ->helperText('Bật/tắt hiển thị CTA trên toàn bộ website')
                                ->default(true),
                        ]),

                        Grid::make(1)->schema([
                            TextInput::make('title')
                                ->label('📝 Tiêu đề chính')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Bắt đầu hành trình với VBA Vũ Phúc')
                                ->helperText('Tiêu đề chính của CTA'),
                        ]),

                        Grid::make(1)->schema([
                            Textarea::make('description')
                                ->label('📄 Mô tả')
                                ->required()
                                ->rows(3)
                                ->placeholder('Khám phá các khóa học VBA chất lượng cao và chuyên sâu...')
                                ->helperText('Mô tả ngắn gọn về lời kêu gọi'),
                        ]),

                        Section::make('Nút hành động')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextInput::make('primary_button_text')
                                        ->label('🔘 Text nút chính')
                                        ->required()
                                        ->placeholder('Xem khóa học'),
                                    TextInput::make('primary_button_url')
                                        ->label('🔗 Link nút chính')
                                        ->required()
                                        ->placeholder('/courses')
                                        ->helperText('URL tương đối hoặc tuyệt đối'),
                                ]),

                                Grid::make(2)->schema([
                                    TextInput::make('secondary_button_text')
                                        ->label('🔘 Text nút phụ')
                                        ->placeholder('Đăng ký học'),
                                    TextInput::make('secondary_button_url')
                                        ->label('🔗 Link nút phụ')
                                        ->placeholder('/students/register')
                                        ->helperText('URL tương đối hoặc tuyệt đối'),
                                ]),
                            ])
                            ->collapsible(),
                    ])
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('💾 Lưu cấu hình')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $webDesign = WebDesign::first();
        
        if (!$webDesign) {
            $webDesign = WebDesign::create([]);
        }

        $webDesign->update([
            'homepage_cta_enabled' => $data['enabled'],
            'homepage_cta_title' => $data['title'],
            'homepage_cta_description' => $data['description'],
            'homepage_cta_primary_button_text' => $data['primary_button_text'],
            'homepage_cta_primary_button_url' => $data['primary_button_url'],
            'homepage_cta_secondary_button_text' => $data['secondary_button_text'],
            'homepage_cta_secondary_button_url' => $data['secondary_button_url'],
        ]);

        // Clear cache
        app(GlobalCtaService::class)->clearCache();
        Cache::forget('web_design_settings');

        Notification::make()
            ->title('✅ CTA đã được cập nhật!')
            ->body('Cấu hình CTA toàn cục đã được lưu thành công.')
            ->success()
            ->duration(5000)
            ->send();
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class DashboardWelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'format' => 'filament',
            'title' => 'Chào mừng đến với VBA Vũ Phúc!',
            'body' => 'Dashboard mới đã được cập nhật với giao diện đẹp mắt và hiệu suất tối ưu.',
            'icon' => 'heroicon-o-sparkles',
            'iconColor' => 'success',
            'actions' => [
                [
                    'name' => 'view_courses',
                    'label' => 'Xem khóa học',
                    'url' => route('filament.admin.resources.courses.index'),
                ],
            ],
        ];
    }

    /**
     * Get the Filament notification representation of the notification.
     */
    public function toFilament(): FilamentNotification
    {
        return FilamentNotification::make()
            ->title('Chào mừng đến với VBA Vũ Phúc!')
            ->body('Dashboard mới đã được cập nhật với giao diện đẹp mắt và hiệu suất tối ưu.')
            ->icon('heroicon-o-sparkles')
            ->iconColor('success')
            ->actions([
                \Filament\Notifications\Actions\Action::make('view_courses')
                    ->label('Xem khóa học')
                    ->url(route('filament.admin.resources.courses.index')),
            ]);
    }
}

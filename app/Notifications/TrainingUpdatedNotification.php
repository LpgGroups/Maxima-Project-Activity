<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RegTraining;


class TrainingUpdatedNotification extends Notification
{
    use Queueable;
    protected $training;
    protected $triggeredBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(RegTraining $training, string $triggeredBy = 'user')
    {
        $this->training = $training;
        $this->triggeredBy = $triggeredBy;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        if ($this->triggeredBy === 'user') {
            return [
                'type' => 'update',
                'title' => 'Training Diperbarui oleh User',
                'message' => $this->training->user->name . ' telah memperbarui training ' . $this->training->activity,
                'training_id' => $this->training->id,
                'url' => route('dashboard.admin.training.show', $this->training->id),
            ];
        }

        return [
            'title' => 'Status Training Anda Telah Diubah',
            'message' => 'Admin telah memperbarui status training ' . $this->training->activity,
            'training_id' => $this->training->id,
            'url' => route('user.training.status', $this->training->id), // ganti sesuai route kamu
        ];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'training_id' => $this->training->id,
            'message' => 'Ada update data pelatihan dari user.',
            'from' => $this->triggeredBy, // "user" atau "admin"
        ];
    }
}

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
    protected $formName;

    /**
     * Create a new notification instance.
     */
    public function __construct(RegTraining $training, string $triggeredBy = 'user', string $formName = '')
    {
        $this->training = $training;
        $this->triggeredBy = $triggeredBy;
        $this->formName = $formName;
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

        $userName = $this->training->user->name ?? 'User';
        $activity = $this->training->activity ?? 'Training';

        $formText = $this->formName ? " pada {$this->formName}" : '';
        if ($this->triggeredBy === 'user') {
            return [
                'type' => 'update',
                'title' => 'Training Diperbarui oleh User',
                'message' => "{$userName} telah memperbarui training {$activity} {$formText}",
                'training_id' => $this->training->id,
                'url' => route('dashboard.admin.training.show', $this->training->id),
            ];
        }

        return [
            'title' => 'Status Training Anda Telah Diubah',
            'message' => "Admin telah memperbarui status training {$activity}{$formText}",
            'training_id' => $this->training->id,
            'url' => route('dashboard.form', $this->training->id), // ganti sesuai route kamu
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

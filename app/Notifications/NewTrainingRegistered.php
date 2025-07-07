<?php

namespace App\Notifications;

use App\Models\RegTraining;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;


class NewTrainingRegistered extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $training;

    public function __construct(RegTraining $training)
    {
        $this->training = $training;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database']; // atau ['mail', 'database'] jika ingin kirim email juga
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        $userName = $this->training->user->name ?? '-';
        return [
            'type' => 'new',
            'title' => 'Training Baru Telah Didaftarkan',
            'message' => $this->training->user->name . ' telah mendaftarkan training ' . $this->training->activity,
            'training_id' => $this->training->id,
            'user_name' => $userName,
            'url' => route('dashboard.admin.training.show', $this->training->id),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

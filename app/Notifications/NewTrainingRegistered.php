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
        return [
            'title' => 'Training Baru Telah Didaftarkan',
            'message' => $this->training->name_company . ' telah mendaftarkan training.',
            'training_id' => $this->training->id,
            'created_by' => $this->training->user->name,
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

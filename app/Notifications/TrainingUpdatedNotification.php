<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Notifications\Notification;
use App\Models\RegTraining;
use Illuminate\Support\Facades\Log;


class TrainingUpdatedNotification extends Notification
{
    use Queueable;
    protected $training;
    protected $triggeredBy;
    protected $formName;
    protected $customMessage;
    protected $customType;
    protected $actorName;
    protected $actorRole;


    public function __construct(RegTraining $training, string $triggeredBy = '', string $formName = '', string $customMessage = '', string $customType = '',  string $actorName = '', string $actorRole = '')
    {
        $this->training = $training;
        $this->triggeredBy = $triggeredBy;
        $this->formName = $formName;
        $this->customMessage = $customMessage;
        $this->customType = $customType;
        $this->actorName = $actorName;
        $this->actorRole = $actorRole;

        Log::info('NOTIFIKASI DIKIRIM:', [
            'triggeredBy' => $triggeredBy,
            'actorName' => $actorName,
            'customMessage' => $customMessage,
        ]);
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
        $userName = $this->resolveActorName();
        $activity = $this->training->activity ?? 'Training';

        $formText = $this->formName ? " pada {$this->formName}" : '';
        if ($this->triggeredBy === 'user') {
            return [
                'type' => 'update',
                'title' => 'Training Diperbarui oleh User',
                'message' => "{$userName} telah memperbarui training {$activity} {$formText}",
                'training_id' => $this->training->id,
                'user_name' => $userName,
                'user_role' => $this->actorRole,
                'url' => route('dashboard.admin.training.show', $this->training->id),
            ];
        }
        if (!empty($this->customMessage)) {
            $url = route('dashboard.form', $this->training->id); // Default untuk user

            // Jika pengguna adalah admin, arahkan ke rute admin
            if ($notifiable->hasRole('admin')) {
                $url = route('dashboard.admin.training.show', $this->training->id);
            }
            return [
                'type' => $this->customType ?: 'success',
                'title' => 'Informasi Pelatihan',
                'message' => $this->customMessage,
                'training_id' => $this->training->id,
                'user_name' => $userName,
                'user_role' => $this->actorRole,
                'url' => $url
            ];
        }

        // Default message admin → user (jangan diubah)
        return [
            'title' => 'Status Training Anda Telah Diubah',
            'message' => "Admin telah memperbarui status training {$activity}{$formText}",
            'training_id' => $this->training->id,
            'user_name' => $userName,
            'user_role' => $this->actorRole,
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
    protected function resolveActorName()
    {
        if (!empty($this->actorName)) {
            return $this->actorName;
        }

        if (strtolower($this->triggeredBy) === 'user') {
            return $this->training->user->name ?? 'User';
        }

        return 'System';
    }
}

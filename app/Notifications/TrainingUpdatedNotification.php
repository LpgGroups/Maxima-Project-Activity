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

    public function __construct(RegTraining $training, string $triggeredBy = 'user', string $formName = '', string $customMessage = '', string $customType = '')
    {
        $this->training = $training;
        $this->triggeredBy = $triggeredBy;
        $this->formName = $formName;
        $this->customMessage = $customMessage;
        $this->customType = $customType;
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
        $waMessage = "Status training {$activity} telah diperbarui oleh Admin{$formText}. Silakan cek dashboard.";
        $this->sendWhatsAppNotification($waMessage);

        if (!empty($this->customMessage)) {
            return [
                'type' => $this->customType ?: 'success',
                'title' => 'Informasi Pelatihan',
                'message' => $this->customMessage,
                'training_id' => $this->training->id,
                'url' => route('dashboard.form', $this->training->id),
            ];
        }

        // Default message admin → user (jangan diubah)
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
    protected function sendWhatsAppNotification($message)
    {
        $token = config('services.maxchat.token');
        $phone = 6285780004039;

        if (!$phone) {
            Log::warning('WhatsApp not sent: No phone number for user ID ' . $this->training->user_id);
            return;
        }

        $phone = preg_replace('/^0/', '62', $phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        try {
            $response = Http::withToken($token)->post('https://app.maxchat.id/api/messages/push', [
                'to' => $phone,
                'type' => 'text',
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WA sent to {$phone}");
            } else {
                Log::error("WA failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }
}

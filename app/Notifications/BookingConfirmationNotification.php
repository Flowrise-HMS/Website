<?php

namespace Modules\Website\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array{type: string, reference: ?string, service: ?string, starts_at: ?string, message: string}  $details
     */
    public function __construct(public array $details) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        if (method_exists($notifiable, 'routeNotificationForMail') && filled($notifiable->routeNotificationForMail())) {
            $channels[] = 'mail';
        }

        if (method_exists($notifiable, 'routeNotificationForSms') && filled($notifiable->routeNotificationForSms())) {
            $channels[] = 'sms';
        }

        return $channels !== [] ? $channels : ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject(__('Appointment booking update'))
            ->greeting(__('Hello'))
            ->line($this->details['message']);

        if (filled($this->details['service'] ?? null)) {
            $mail->line(__('Service: :service', ['service' => $this->details['service']]));
        }

        if (filled($this->details['starts_at'] ?? null)) {
            $mail->line(__('When: :when', ['when' => $this->details['starts_at']]));
        }

        if (filled($this->details['reference'] ?? null)) {
            $mail->line(__('Reference: :ref', ['ref' => $this->details['reference']]));
        }

        return $mail->line(__('Thank you for choosing us.'));
    }

    public function toSms(object $notifiable): string
    {
        $parts = array_filter([
            $this->details['message'] ?? null,
            isset($this->details['service']) ? 'Service: '.$this->details['service'] : null,
            isset($this->details['starts_at']) ? 'When: '.$this->details['starts_at'] : null,
            isset($this->details['reference']) ? 'Ref: '.$this->details['reference'] : null,
        ]);

        return implode(' | ', $parts);
    }
}

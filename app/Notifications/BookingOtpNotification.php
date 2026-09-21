<?php

namespace Modules\Website\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Notifications\Concerns\ResolvesNotificationChannels;

class BookingOtpNotification extends Notification implements ShouldQueue
{
    use Queueable, ResolvesNotificationChannels;

    public function __construct(public string $code) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return $this->channelsFor($notifiable, ['mail', 'sms']);
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Your booking verification code'))
            ->greeting(__('Hello'))
            ->line(__('Your verification code is :code', ['code' => $this->code]))
            ->line(__('This code expires in 10 minutes.'))
            ->line(__('If you did not request this, you can ignore this message.'));
    }

    public function toSms(object $notifiable): string
    {
        return __('Your booking verification code is :code', ['code' => $this->code]);
    }
}

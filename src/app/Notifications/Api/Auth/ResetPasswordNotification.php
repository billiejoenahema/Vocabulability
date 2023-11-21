<?php

declare(strict_types=1);

namespace App\Notifications\Api\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private string $passwordResetEndpoint;
    private string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->passwordResetEndpoint = env('APP_URL') . '/password-reset';
        $this->token = $token;
    }

    /**
     * @param mixed $notifiable
     */
    protected function resetUrl($notifiable): string
    {
        return $this->passwordResetEndpoint . '?' . http_build_query([
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('アカウントのパスワードリセットリクエストを受けつけました。')
            ->action('パスワードリセット', $this->resetUrl($notifiable))
            ->line('このパスワードリセットリンクの使用期限は60分です。')
            ->line('このメールに身に覚えがない場合は無視してください。');
    }
}

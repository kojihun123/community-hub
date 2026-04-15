<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class RealTimeAlert extends Notification
{
    use Queueable;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }
    
    public function via($notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type' => $this->details['type'],
            'title' => $this->details['title'],
            'message' => $this->details['message'] ?? $this->details['body'] ?? '',
            'link' => $this->details['link'] ?? null,
            'sender_name' => $this->details['sender_name'] ?? null,
        ]);
    }
}

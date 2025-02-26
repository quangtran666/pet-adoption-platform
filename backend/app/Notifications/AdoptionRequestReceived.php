<?php

namespace App\Notifications;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdoptionRequestReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly AdoptionRequest $adoptionRequest)
    {

    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Adoption Request Received')
            ->line('You have received a new adoption request for your pet ' . $this->adoptionRequest->pet->name)
            ->line('Message: ' . $this->adoptionRequest->message)
            ->action('View Request', url('/adoption-requests/' . $this->adoptionRequest->id))
            ->line('Thank you for using our platform!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'adoption_request_received',
            'adoption_request_id' => $this->adoptionRequest->id,
            'pet_name' => $this->adoptionRequest->pet->name,
            'requester_name' => $this->adoptionRequest->user->name,
            'message' => $this->adoptionRequest->message
        ];
    }
}

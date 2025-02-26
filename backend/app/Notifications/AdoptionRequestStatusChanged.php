<?php

namespace App\Notifications;

use App\Models\AdoptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdoptionRequestStatusChanged extends Notification implements ShouldQueue
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
        $status = $this->adoptionRequest->status;
        $petName = $this->adoptionRequest->pet->name;

        return (new MailMessage)
            ->subject("Adoption Request {$status}")
            ->line("Your adoption request for {$petName} has been {$status}")
            ->line($this->adoptionRequest->message)
            ->action('View Request', url('/adoption-requests/' . $this->adoptionRequest->id))
            ->line('Thank you for using our platform!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'adoption_request_status_changed',
            'adoption_request_id' => $this->adoptionRequest->id,
            'pet_name' => $this->adoptionRequest->pet->name,
            'status' => $this->adoptionRequest->status,
            'message' => $this->adoptionRequest->message
        ];
    }
}

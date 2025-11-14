<?php

namespace App\Notifications;

use App\Models\Allocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AllocationApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Allocation $allocation)
    {
    }

    public function via($notifiable): array
    {
        return [''mail''];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(''Land Allocation Approved - Techiman Customary Lands'')
            ->greeting(''Hello '' . $notifiable->name . ''!'')
            ->line(''We are pleased to inform you that your land allocation has been approved.'')
            ->line(''**Allocation Details:**'')
            ->line(''- Plot Number: '' . $this->allocation->land->plot_number)
            ->line(''- Location: '' . $this->allocation->land->location)
            ->line(''- Area: '' . number_format($this->allocation->land->area_acres, 2) . '' acres'')
            ->line(''- Approved Chief: '' . $this->allocation->chief->name)
            ->action(''View Allocation Details'', url(''/allocations/'' . $this->allocation->id))
            ->line(''Please visit the Lands Secretariat to complete the necessary documentation.'')
            ->salutation(''Regards, Techiman Customary Lands Secretariat'');
    }

    public function toArray($notifiable): array
    {
        return [
            ''allocation_id'' => $this->allocation->id,
            ''plot_number'' => $this->allocation->land->plot_number,
            ''message'' => ''Your land allocation has been approved.'',
        ];
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\Allocation;
use App\Notifications\AllocationApprovedNotification;
use App\Notifications\AllocationPendingNotification;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send allocation approval notification
     */
    public function sendAllocationApprovedNotification(Allocation $allocation): void
    {
        try {
            // Notify the client (if they have an email)
            if ($allocation->client->email) {
                Notification::route(''mail'', $allocation->client->email)
                    ->notify(new AllocationApprovedNotification($allocation));
            }

            // Notify relevant staff
            $staffUsers = User::whereHas(''roles'', function ($query) {
                $query->whereIn(''name'', [''admin'', ''registrar'']);
            })->get();

            Notification::send($staffUsers, new AllocationApprovedNotification($allocation));

            \Log::info(""Allocation approved notifications sent for allocation #{$allocation->id}"");

        } catch (\Exception $e) {
            \Log::error(''Failed to send allocation approved notification: '' . $e->getMessage());
        }
    }

    /**
     * Send allocation pending notification to chief
     */
    public function sendAllocationPendingNotification(Allocation $allocation): void
    {
        try {
            // Notify the chief (if they have a user account)
            if ($allocation->chief->user) {
                Notification::send(
                    $allocation->chief->user, 
                    new AllocationPendingNotification($allocation)
                );
            }

            \Log::info(""Allocation pending notification sent to chief for allocation #{$allocation->id}"");

        } catch (\Exception $e) {
            \Log::error(''Failed to send allocation pending notification: '' . $e->getMessage());
        }
    }

    /**
     * Send SMS notification (placeholder for Twilio integration)
     */
    public function sendSmsNotification(string $phoneNumber, string $message): bool
    {
        try {
            // This would integrate with Twilio or another SMS provider
            // For now, we''ll log the SMS that would be sent
            \Log::info(""SMS to {$phoneNumber}: {$message}"");

            return true;

        } catch (\Exception $e) {
            \Log::error(''Failed to send SMS notification: '' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk notifications for pending approvals
     */
    public function sendPendingApprovalsReminder(): void
    {
        $pendingAllocations = Allocation::where(''approval_status'', ''pending'')
            ->with([''chief'', ''land''])
            ->get();

        foreach ($pendingAllocations as $allocation) {
            // Send reminder to chief
            if ($allocation->chief->user) {
                $this->sendAllocationPendingNotification($allocation);
            }
        }

        \Log::info(""Sent pending approvals reminder for {$pendingAllocations->count()} allocations"");
    }
}

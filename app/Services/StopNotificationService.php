<?php

namespace App\Services;

use App\Mail\NewStopCreatedMail;
use App\Models\Stop;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StopNotificationService
{
    public function __construct(private ZaloOaNotificationService $zaloOaNotificationService)
    {
    }

    /**
     * Resolve the recipients that should receive STOP notifications.
     *
     * @param array<int, string>|null $overrideRecipients
     * @return array<int, string>
     */
    public function resolveRecipients(?array $overrideRecipients = null): array
    {
        $recipients = $overrideRecipients ?? $this->getSafetyOfficerEmails();

        return collect($recipients)
            ->map(function ($email) {
                return trim((string) $email);
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Get active safety officer emails from users table.
     *
     * @return array<int, string>
     */
    public function getSafetyOfficerEmails(): array
    {
        return User::query()
            ->where('is_active', true)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->where(function ($query) {
                $query->whereIn('role', ['admin', 'tchc_checker', 'tchc_manager'])
                    ->orWhere('is_tchc_checker', true)
                    ->orWhere('is_tchc_manager', true);
            })
            ->pluck('email')
            ->map(function ($email) {
                return trim((string) $email);
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Send notification email to safety officers for a newly created STOP card.
     *
     * @param array<int, string>|null $overrideRecipients
     * @return array<int, string>
     */
    public function sendNewStopCreatedNotification(Stop $stop, ?array $overrideRecipients = null): array
    {
        $recipients = $this->resolveRecipients($overrideRecipients);

        if (empty($recipients)) {
            Log::warning('No recipients found for new STOP notification.', [
                'stop_id' => $stop->id,
            ]);

            return [];
        }

        Mail::to($recipients)->queue(new NewStopCreatedMail($stop));

        Log::info('New STOP notification email queued.', [
            'stop_id' => $stop->id,
            'recipient_count' => count($recipients),
            'recipients' => $recipients,
        ]);

        return $recipients;
    }

    public function sendPriorityZeroNotification(Stop $stop): bool
    {
        return $this->zaloOaNotificationService->sendPriorityZeroAlert($stop);
    }
}

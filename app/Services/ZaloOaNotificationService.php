<?php

namespace App\Services;

use App\Models\Stop;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ZaloOaNotificationService
{
    public function isEnabled(): bool
    {
        return (bool) config('services.zalo_oa.enabled', false);
    }

    public function sendPriorityZeroAlert(Stop $stop): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $message = $this->buildPriorityZeroMessage($stop);
        $channel = (string) config('services.zalo_oa.channel', 'oa');
        $recipients = $this->resolvePriorityZeroRecipients($stop);

        if ($recipients->isEmpty()) {
            Log::warning('No recipients found for STOP level-0 Zalo notification.', [
                'stop_id' => $stop->id,
            ]);

            return false;
        }

        $webhookUrl = (string) config('services.zalo_oa.webhook_url', '');
        if ($channel === 'webhook' && $webhookUrl === '') {
            Log::warning('Zalo channel is webhook but no webhook URL configured.', [
                'stop_id' => $stop->id,
            ]);

            return false;
        }

        if ($channel === 'webhook') {
            return $this->sendViaWebhook($webhookUrl, $stop, $message, $recipients);
        }

        if ($channel === 'zns') {
            return $this->sendViaZnsApi($stop, $recipients);
        }

        return $this->sendViaOfficialApi($stop, $message, $recipients);
    }

    private function sendViaWebhook(string $webhookUrl, Stop $stop, string $message, $recipients): bool
    {
        try {
            $response = Http::timeout(5)->post($webhookUrl, [
                'event' => 'stop_priority_zero',
                'stop_id' => $stop->id,
                'priority_level' => $stop->priority_level,
                'recipient_count' => $recipients->count(),
                'recipients' => $recipients->map(function (User $user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->full_name,
                        'department' => $user->department,
                        'role' => $user->role,
                        'zalo_user_id' => $user->zalo_user_id,
                        'phone' => $user->phone,
                    ];
                })->values()->all(),
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('Zalo OA webhook notification sent.', [
                    'stop_id' => $stop->id,
                    'status' => $response->status(),
                ]);

                return true;
            }

            Log::warning('Zalo OA webhook notification failed.', [
                'stop_id' => $stop->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $exception) {
            Log::error('Zalo OA webhook notification exception.', [
                'stop_id' => $stop->id,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function sendViaOfficialApi(Stop $stop, string $message, $recipients): bool
    {
        $accessToken = (string) config('services.zalo_oa.access_token', '');
        $apiUrl = (string) config('services.zalo_oa.message_api_url', 'https://openapi.zalo.me/v3.0/oa/message/cs');
        $recipientIds = $recipients
            ->pluck('zalo_user_id')
            ->filter()
            ->map(function ($value) {
                return trim((string) $value);
            })
            ->filter()
            ->values()
            ->all();

        if (empty($recipientIds)) {
            $recipientIds = config('services.zalo_oa.recipient_ids', []);
        }

        if ($accessToken === '' || empty($recipientIds)) {
            Log::warning('Zalo OA notification skipped due to missing configuration.', [
                'stop_id' => $stop->id,
                'has_access_token' => $accessToken !== '',
                'recipient_count' => is_array($recipientIds) ? count($recipientIds) : 0,
            ]);

            return false;
        }

        $allSuccess = true;

        foreach ($recipientIds as $recipientId) {
            try {
                $response = Http::withHeaders([
                    'access_token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])->timeout(6)->post($apiUrl, [
                    'recipient' => [
                        'user_id' => (string) $recipientId,
                    ],
                    'message' => [
                        'text' => $message,
                    ],
                ]);

                if (!$response->successful()) {
                    $allSuccess = false;

                    Log::warning('Zalo OA API notification failed.', [
                        'stop_id' => $stop->id,
                        'recipient_id' => (string) $recipientId,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    continue;
                }

                Log::info('Zalo OA API notification sent.', [
                    'stop_id' => $stop->id,
                    'recipient_id' => (string) $recipientId,
                    'status' => $response->status(),
                ]);
            } catch (\Throwable $exception) {
                $allSuccess = false;

                Log::error('Zalo OA API notification exception.', [
                    'stop_id' => $stop->id,
                    'recipient_id' => (string) $recipientId,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $allSuccess;
    }

    private function sendViaZnsApi(Stop $stop, $recipients): bool
    {
        $accessToken = (string) config('services.zalo_oa.access_token', '');
        $znsApiUrl = (string) config('services.zalo_oa.zns_api_url', 'https://business.openapi.zalo.me/message/template');
        $templateId = (string) config('services.zalo_oa.zns_template_id', '');

        if ($accessToken === '' || $templateId === '') {
            Log::warning('ZNS notification skipped due to missing configuration.', [
                'stop_id' => $stop->id,
                'has_access_token' => $accessToken !== '',
                'has_template_id' => $templateId !== '',
            ]);

            return false;
        }

        $phones = $recipients
            ->pluck('phone')
            ->map(function ($phone) {
                return preg_replace('/\D+/', '', (string) $phone);
            })
            ->filter()
            ->unique()
            ->values();

        if ($phones->isEmpty()) {
            Log::warning('ZNS notification skipped due to no recipient phone number.', [
                'stop_id' => $stop->id,
            ]);

            return false;
        }

        $templateData = [
            'stop_id' => (string) $stop->id,
            'priority_level' => (string) $stop->priority_level,
            'observer_name' => (string) $stop->observer_name,
            'location' => (string) $stop->location,
            'issue' => Str::limit((string) $stop->issue_description, 100, '...'),
        ];

        $allSuccess = true;
        foreach ($phones as $phone) {
            try {
                $response = Http::withHeaders([
                    'access_token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])->timeout(8)->post($znsApiUrl, [
                    'phone' => (string) $phone,
                    'template_id' => $templateId,
                    'template_data' => $templateData,
                    'tracking_id' => 'stop_' . $stop->id . '_' . now()->timestamp,
                ]);

                if (!$response->successful()) {
                    $allSuccess = false;
                    Log::warning('ZNS notification failed.', [
                        'stop_id' => $stop->id,
                        'phone' => (string) $phone,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    continue;
                }

                Log::info('ZNS notification sent.', [
                    'stop_id' => $stop->id,
                    'phone' => (string) $phone,
                ]);
            } catch (\Throwable $exception) {
                $allSuccess = false;
                Log::error('ZNS notification exception.', [
                    'stop_id' => $stop->id,
                    'phone' => (string) $phone,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $allSuccess;
    }

    private function resolvePriorityZeroRecipients(Stop $stop)
    {
        $stop->loadMissing('user');
        $creator = $stop->user;

        $shiftLeaders = collect();
        if ($creator && !empty($creator->department)) {
            $shiftLeaders = User::query()
                ->where('is_active', true)
                ->where('role', 'approver')
                ->where('department', $creator->department)
                ->get();
        }

        $safetyRoles = config('services.zalo_oa.safety_roles', ['admin', 'tchc_checker', 'tchc_manager']);
        $safetyOfficers = User::query()
            ->where('is_active', true)
            ->where(function ($query) use ($safetyRoles) {
                $query->whereIn('role', $safetyRoles)
                    ->orWhere('is_tchc_checker', true)
                    ->orWhere('is_tchc_manager', true);
            })
            ->get();

        return $shiftLeaders
            ->concat($safetyOfficers)
            ->unique('id')
            ->values();
    }

    private function buildPriorityZeroMessage(Stop $stop): string
    {
        $description = trim((string) $stop->issue_description);
        $description = mb_substr($description, 0, 120);

        return sprintf(
            '[KDNVPP] CANH BAO STOP MUC 0\nID: %s\nNguoi ghi nhan: %s\nVi tri: %s\nNoi dung: %s',
            (string) $stop->id,
            trim((string) $stop->observer_name),
            trim((string) $stop->location),
            $description
        );
    }
}

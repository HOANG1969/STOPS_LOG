<?php

namespace App\Console\Commands;

use App\Models\Stop;
use App\Services\StopNotificationService;
use Illuminate\Console\Command;

class TestStopNotificationEmail extends Command
{
    protected $signature = 'stop:test-mail
                            {stop_id? : STOP ID to test with}
                            {--to=* : Override recipient emails}';

    protected $description = 'Send test email for new STOP notification';

    public function handle(StopNotificationService $stopNotificationService): int
    {
        $stopId = $this->argument('stop_id');
        $stop = $stopId
            ? Stop::with('user')->find($stopId)
            : Stop::with('user')->latest('id')->first();

        if (!$stop) {
            $this->error('No STOP record found. Create a STOP card first or pass stop_id.');

            return self::FAILURE;
        }

        $overrideRecipients = $this->option('to');
        $overrideRecipients = is_array($overrideRecipients) && !empty($overrideRecipients)
            ? $overrideRecipients
            : null;

        try {
            $recipients = $stopNotificationService->sendNewStopCreatedNotification($stop, $overrideRecipients);
        } catch (\Throwable $exception) {
            $this->error('Failed to send test STOP email: ' . $exception->getMessage());

            return self::FAILURE;
        }

        if (empty($recipients)) {
            $this->warn('No recipient emails found for safety officers.');
            $this->line('Hint: check users.role / users.is_tchc_checker / users.is_tchc_manager / users.email.');

            return self::SUCCESS;
        }

        $this->info('STOP notification email sent successfully.');
        $this->line('STOP ID: ' . $stop->id);
        $this->line('Recipients: ' . implode(', ', $recipients));

        return self::SUCCESS;
    }
}

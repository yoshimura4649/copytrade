<?php

use App\Jobs\SendMailNotice;
use App\Jobs\TurnOffTrade;
use App\Jobs\UpdateTokens;
use Illuminate\Support\Facades\Schedule;

// Update Token for Users once a day
Schedule::job(new UpdateTokens)->dailyAt('01:00');

// Turn Off Trade after 1 month
Schedule::job(new TurnOffTrade)->dailyAt('01:30');

// Send notification emails to each user
Schedule::job(new SendMailNotice)->dailyAt('01:30');

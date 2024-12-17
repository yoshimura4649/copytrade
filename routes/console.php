<?php

use App\Jobs\UpdateTokens;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new UpdateTokens)->dailyAt('01:00');

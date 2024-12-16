<?php

use App\Jobs\UpdateTokens;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new UpdateTokens)->everySecond();

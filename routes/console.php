<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;

Schedule::call(function () {

    $now = Carbon::now();
    $start = $now->copy()->firstOfQuarter();

    $week1Date = $start->copy();          // Day 1
    $week2Date = $start->copy()->addWeek(); // Day 8
    $lastWeekStart = $end->copy()->subDays(6); // last 7 days start

    
    if ($now->isSameDay($week1Date)) {

        foreach (User::all() as $user) {
            Mail::to($user->email)->send(new ReminderMail($user, 'week1'));
        }
    }

    if ($now->isSameDay($week2Date)) {

        foreach (User::all() as $user) {
            Mail::to($user->email)->send(new ReminderMail($user, 'week2'));
        }
    }

    // Last week of quarter
    if ($now->isSameDay($lastWeekStart)) {
        foreach ($users as $user) {
            Mail::to($user->email)->send(new ReminderMail($user, 'last_week'));
        }
    }

})->dailyAt('13:00');


// Schedule::call(function () {
//     $user = Auth::user();
//     Mail::to('example@gmail.com')->send(new ReminderMail($user, 'test_week'));
    

// })->dailyAt('15:00');

Schedule::command('app:send-reminder-emails')
    ->dailyAt('16:09');

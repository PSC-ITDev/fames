<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderMail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminder-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // $users = User::whereHas('role', function ($query) {
        //     $query->where('name', 'User');
        // })->get();

        // foreach ($users as $user) {
            // Mail::to($user->email)->send(new ReminderMail($user));
        // }

        $user = User::first();
        Mail::to('example@gmail.com')->send(new ReminderMail($user, 'test_week'));

        $this->info('Reminder email sent.');

        return self::SUCCESS;
    }
}
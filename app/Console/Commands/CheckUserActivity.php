<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUserActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checked user activity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sessionLifetime = config('session.lifetime', 120);
        $now = now();
        
        $fiveMinutesAgo = $now->copy()->subMinutes(5);
        $sessionExpiredTime = $now->copy()->subMinutes($sessionLifetime);

        // Update status ke 'online' jika last_activity kurang dari 5 menit
        User::whereNotNull('last_activity')
            ->where('last_activity', '>=', $fiveMinutesAgo)
            ->where(function ($query) {
                $query->where('status_activity', '!=', 'online')
                      ->orWhereNull('status_activity');
            })
            ->update(['status_activity' => 'online']);

        // Update status ke 'idle' jika last_activity lebih dari 5 menit tapi belum expired
        User::whereNotNull('last_activity')
            ->where('last_activity', '<', $fiveMinutesAgo)
            ->where('last_activity', '>=', $sessionExpiredTime)
            ->where(function ($query) {
                $query->where('status_activity', '!=', 'idle')
                      ->orWhereNull('status_activity');
            })
            ->update(['status_activity' => 'idle']);

        // Update status ke 'offline' jika last_activity sudah lewat batas session
        User::where(function ($query) use ($sessionExpiredTime) {
                $query->whereNull('last_activity')
                      ->orWhere('last_activity', '<', $sessionExpiredTime);
            })
            ->where(function ($query) {
                $query->where('status_activity', '!=', 'offline')
                      ->orWhereNull('status_activity');
            })
            ->update(['status_activity' => 'offline']);

        $this->info('Status aktifitas user berhasil diupdate.');
    }
}

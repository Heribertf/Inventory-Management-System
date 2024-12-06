<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDaysLeftOnProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:updateDaysLeft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update days left on projects table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Executing...');

        $this->updateProjects();

        $this->logUpdateDetails();
    }

    private function updateProjects(): void
    {
        $currentDate = Carbon::now()->toDateString();

        DB::table('projects')
            ->where('state', 'running')
            ->update([
                'days_left' => DB::raw("DATEDIFF(day, '{$currentDate}', deadline)"),
                'state' => DB::raw("CASE WHEN status = 2 THEN 'closed' ELSE state END"),
            ]);
    }

    private function logUpdateDetails(): void
    {
        $this->info('Projects update completed on ' . Carbon::now());
    }
}

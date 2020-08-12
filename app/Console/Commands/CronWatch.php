<?php

namespace App\Console\Commands;

use App\Facades\ActionManagerFacade as ActionManager;
use App\Models\Action;
use App\Models\Cron;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CronWatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aquabox:cron_watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search if we need to run a cron action';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ok = true;

        $days_ref = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // format now
        $now = now();
        $hour = $now->hour;
        $minute = $now->minute;
        $day_name = $now->format('l');
        $day_number = $now->day;
        $month = $now->month;

        // format day
        $day = array_search($day_name, $days_ref);
        $day++;

        // format week
        if( $day_number <= 7 )
            $week = 1;
        elseif ( $day_number <= 14 )
            $week = 2;
        elseif ( $day_number <= 23 )
            $week = 3;
        else
            $week = 4;

        /*
        // debug data
        $hour = 13;
        $minute = 5;
        $day = 1;
        $week = 1;
        $month = 1;
        */

        // search cron to run now
        $crons = Cron::where('hour', $hour)->orWhere('hour', '-1')
            ->where('minute', $minute)->orWhere('minute', '-1')
            ->where('day', $day)->orWhere('day', '-1')
            ->where('week', $week)->orWhere('week', '-1')
            ->where('month', $month)->orWhere('month', '-1')
            ->get();

        foreach( $crons as $cron )
        {
            $action = Action::find($cron->action_id);
            $rez = ActionManager::run($action->id);

            if( ! $rez )
            {
                $ok = false;
                Log::warning("#CronWatcher: Erreur à l'execution de {$action->label}");
            }
        }


        return $ok;
    }
}

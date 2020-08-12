<?php
namespace App\Services;

use App\Models\Action;
use Illuminate\Support\Facades\Log;

class ActionManagerService
{
    private $_script_path;

    public function __construct()
    {
        $this->_script_path = base_path() . '/' . env('PI_SCRIPTS_PATH', 'pi_scripts/');
    }

    public function run($action_id) : bool
    {
        $ok = false;

        $action = Action::find($action_id);

        if( $this->check($action) )
            $ok = $this->run_cmd($action->cmd);

        return $ok;
    }

    public function check($action) : bool
    {
        $ok = false;

        if( $action )
        {
            $cmd_exploded = $this->explode_cmd($action->cmd);

            if( $this->script_exist($cmd_exploded[0]) )
                $ok = true;
            else
                Log::warning("#ActionManager: Invalid script {$cmd_exploded[0]}");
        }
        else
        {
            Log::warning("#ActionManager: Action {$action} not found");
        }

        return $ok;
    }

    public function explode_cmd($cmd) : array
    {
        $details = explode(' ', $cmd);

        return $details;
    }

    public function script_exist($script) : bool
    {
        $exist = false;

        $full_script_path = $this->_script_path . $script;

        if( file_exists($full_script_path) )
             if( is_executable($full_script_path) )
                 $exist = true;
             else
                 Log::warning("#ActionManager: File {$full_script_path} is not executable by apache");
        else
            Log::warning("#ActionManager: File {$full_script_path} not found");

        return $exist;
    }

    public function run_cmd($cmd) : bool
    {
        $ok = false;

        $full_cmd_path = $this->_script_path . $cmd;

        $output = [];
        exec($full_cmd_path, $output, $ok);

        if( $ok == 0 )
        {
            $ok = true;
        }
        else
        {
            $ok = false;
            Log::warning("#ActionManager: Fail to run {$full_cmd_path}");
        }

        return $ok;
    }
}

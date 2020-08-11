<?php


namespace App\Http\Controllers\Api;


use App\Facades\ActionManagerFacade as ActionManager;

class ActionApiController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function do($action_id)
    {
        $ok = ActionManager::run($action_id);

        if( $ok )
            return $this->sendSuccess();
        else
            return $this->sendError('Error during action', 500);
    }
}

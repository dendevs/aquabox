<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function tokenToUser(Request $request)
    {
        $current_user = false ;

        $header = $request->header('Authorization');
        if( ! is_null( $header ) )
        {
            $token = str_replace('Bearer ', '', $header);
            $user = User::where('api_token', $token)->first();
            if( $user )
                $current_user = $user;
        }

        return $current_user;
    }

    public function sendSuccess()
    {
        return Response::json($this->makeResponse( "it's ok", [], 200 ), 200 );
    }

    public function sendResponse($datas, $message, $code = 200) // 200
    {
        return Response::json($this->makeResponse( $message, $datas, $code ), $code );
    }

    public function sendNoContent($message) // 202
    {
        return Response::json($this->makeResponse($message, [], 202), 202);
    }

    public function sendError($error, $code = 404) // 404
    {
        return Response::json($this->makeError($error), $code);
    }

    // utils
    public function makeResponse($message, $datas, $code = 200)
    {
        $count = 0;
        if( is_array($datas))
            $count = count( $datas );
        else if( $datas )
            $count = 1;

        $success = ( $code >= 200 && $code < 300 ) ? true : false;

        $result = [
            'success' => $success,
            'data'    => $datas,
            'count'   => $count,
            'message' => $message,
            'code'    => $code
        ];

        return $result;
    }

    public function makeError($error, array $datas = [])
    {
        $result = [
            'success' => false,
            'data'    => $datas,
            'count'   => count($datas),
            'message' => $error,
        ];

        return $result;
    }
}

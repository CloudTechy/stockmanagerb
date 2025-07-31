<?php

namespace App\Http\Controllers;

use App\Helper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use \Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function exception(\Exception $bug, $message, $code = 500)
    {

        if ($code == 500) {
            $message = "Oops. An unexpected server error occured";
        }

        $error = $bug->getMessage();

        return Helper::invalidRequest($error, $message, $code);
    }

}

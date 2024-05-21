<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;

class APIController extends Controller
{
    use ApiResponses;

    //    public function include($relationship): bool
    //    {
    //        $param  = request('include');
    //
    //        if (!$param ){
    //            return false;
    //        }
    //
    //        $includedValues = explode(',', strtolower($param));
    //
    //       return  in_array(strtolower($relationship), $includedValues);
    //    }
}

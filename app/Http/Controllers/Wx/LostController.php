<?php

namespace App\Http\Controllers\Wx;

use App\Models\Lost;
use App\Http\Controllers\Controller;
class LostController extends Controller
{

    public function get_list(){


        return responseToJson(0, 'success',Lost::get_list());
        
    }

}
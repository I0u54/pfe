<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class MessageCotroller extends Controller
{
    public function createMessage(Request $request){


        $validator = Validator::make($request->all(), [
            'message_text' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error( $validator->errors() , 'Verify inputs' , 404);
        }


    }
}

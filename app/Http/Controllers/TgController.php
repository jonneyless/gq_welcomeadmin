<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
use App\Service\AssistService;
use App\Service\RedisService;
use App\Service\TgService;


class TgController extends Controller
{
    public function index()
    {
        $parameters = request()->all();
        
        Log::info($parameters);
        
        if (is_right_data($parameters, "chat_member")) {
            $chat_member = $parameters["chat_member"];
            
            TgService::handleIn($chat_member);
        }
        
        if (is_right_data($parameters, "message")) {
            $message = $parameters["message"];
            $message_id = $message["message_id"];

            if (is_right_data($message, "chat")) {
                $chat = $message["chat"];
                $chat_id = $chat["id"];
                
                if (is_right_data($chat, "type")) {
                    if ($chat["type"] != "private") {
                        if (is_right_data($message, "from")) {
                            $from = $message["from"];
                            
                            $chat = AssistService::handleChat($chat);
                            $from = AssistService::handleFrom($from);
                            

                            if (is_right_data($message, "from")) {
                                $fromer = $message["from"];
                                
                                $user1 = AssistService::handleFrom($fromer);
  
                                if (is_right_data($message, "message_id") and is_right_data($message, "date")) {
                                    TgService::handleMsg48($message, $chat_id, $fromer["id"], $message["message_id"], $message["date"]);
                                }
                            }

                        }
                    }
                }
            }
        }
        
        return "success";
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reply;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $temp = getWebhookInfo();
        // $temp = setAdmin("-1001919593910", "6336578665");
        
        dd($temp);
        
        
        dd("2");
        
        
        
        // $search_base_url = "http://3.1.240.129/prod-api";
        // $search_push_url = $search_base_url . "/bot/record/batchSaveGq";
        // $search_cancel_url = $search_base_url . "/bot/record/cancelGq";
        
        // $url = "https://t.me/+xVbaMwdQCcg5MjZl";

        // $now = time();
        // $now = $now * 1000;

        // $data = [
        //     "url" => $url,
        //     "timestamp" => $now,
        //     "md5" => md5($now . "6ac892aae6dfd5c1438c12999632ac6d"),
        // ];
        // $data = json_encode($data);
        
        // echo $url;
        // echo "\n";
        
        // $result = curlPost($search_cancel_url, $data);
        // dd($result);  
        
        // $objs = Reply::query()->where("flag", 2)
        //     ->where("val", "like", "%http%")
        //     ->get();
        // foreach ($objs as $obj) {
        //     $url = $obj["val"];
            
        //     if ($url) {
        //         $now = time();
        //         $now = $now * 1000;

        //         $data = [
        //             "urls" => [$url],
        //             "timestamp" => $now,
        //             "md5" => md5($now . "6ac892aae6dfd5c1438c12999632ac6d"),
        //         ];
        //         $data = json_encode($data);
                
        //         echo $url;
        //         echo "\n";
                
        //         $result = curlPost($search_push_url, $data);
        //         dd($result);   
        //     }
        // }       
 
 
 
        
        // $group_tg_id = "-1001919593910";
        // $temp = getChatAdministrators($group_tg_id);
        // dd($temp);
        
        // $group_tg_id = "-1001959940500";
        // $user_tg_id = "1890717643";
        
        // $temp = unbanChatMember($group_tg_id, $user_tg_id);
        // dd($temp);
        
        // $fullname = "q你好a";
        
        // dd(str_split($fullname, 1));
        
        // $url = "http://127.0.0.1:8000/";
        // $data = [
        //     "type_ops" => "promote",
        //     "group_tg_id" => "-1001702075687",
        //     "user_tg_id" => "1317798328",
        // ];
        
        // curlGet($url, $data);

    }
}

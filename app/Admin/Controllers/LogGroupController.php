<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use App\Service\LogGroupService;
use Illuminate\Http\Request;


class LogGroupController extends AdminController
{
    public function data(Request $request)
    {
        $parameters = request()->all();

        $result = LogGroupService::search([
            "start" => $parameters["start"],
            "len" => $parameters["length"],
            
            "group_tg_id" => $parameters["group_tg_id"],
            "startTime" => $parameters["startTime"],
            "endTime" => $parameters["endTime"],

            "is_arr" => true,
        ]);

        return array(
            "draw" => $request->get("draw"),
            'recordsTotal' => $result["count"],
            "recordsFiltered" => $result["count"],
            'data' => $result["data"],
        );
    }
}
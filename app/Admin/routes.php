<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', function () {
        return redirect(config('admin.route.prefix') . "/group");
    });
    
    $router->resource("reply", ReplyController::class);
    $router->resource("aduser", AdUserController::class);
    $router->resource("addata", AdDataController::class);


    $router->resource('cheatspecial', CheatSpecialController::class);
    Route::get("/cheatspecial/create", "CheatSpecialController@add");
    Route::post("/cheatspecial/save", "CheatSpecialController@save");


    $router->resource("group", GroupController::class);
    Route::group(["prefix" => "group"], function (Router $router) {
        Route::post("notice", "GroupController@notice");
        
        Route::post("changeBusinessType", "GroupController@changeBusinessType");
        Route::post("notice/add", "GroupController@noticeAdd");
        Route::post("notice/delete", "GroupController@noticeDelete");

        Route::post("kickAll", "GroupController@kickAll");
        Route::post("unban", "GroupController@unban");

        Route::post("changeTitle", "GroupController@changeTitle");
        Route::post("changeDesc", "GroupController@changeDesc");
        Route::post("changeWelcomeStatus", "GroupController@changeWelcomeStatus");
        Route::post("changeInfo", "GroupController@changeInfo");
        Route::post("changeFlag", "GroupController@changeFlag");
        Route::post("changeTradeType", "GroupController@changeTradeType");
        Route::post("changePeopleLimit", "GroupController@changePeopleLimit");
        Route::post("changeLimitOneTime", "GroupController@changeLimitOneTime");
        Route::post("setTime", "GroupController@setTime");
        Route::post("change", "GroupController@change");
        Route::post("changeTradeRate", "GroupController@changeTradeRate");
        
        Route::post("changeLimitOneTime", "GroupController@changeLimitOneTime");
        
        Route::post("changeBusinessDetailType", "GroupController@changeBusinessDetailType");
        Route::post("changeStatusMoney", "GroupController@changeStatusMoney"); 
        
        Route::post("changeSendUserChange", "GroupController@changeSendUserChange"); 
        
        Route::post("changeStatusFollow", "GroupController@changeStatusFollow"); 
        Route::post("changeStatusXianjing", "GroupController@changeStatusXianjing"); 
        
        Route::post("setUserTitle", "GroupController@setUserTitle");
        Route::post("setAdmin", "GroupController@setAdmin");
        Route::post("flushAdmin", "GroupController@flushAdmin");
        Route::post("removeAdmin", "GroupController@removeAdmin");
        Route::post("leave", "GroupController@leave");

        Route::post("changeStatusApprove", "GroupController@changeStatusApprove");
        Route::post("approve", "GroupController@approve");
        Route::post("approve/declineAndKick", "GroupController@approveDeclineAndKick");
        Route::post("approve/declineAndKickAndCheat", "GroupController@approveDeclineAndKickAndCheat");
        Route::post("approve/clearAllapprove", "GroupController@clearAllapprove");
        Route::post("approves", "GroupController@approves");
        Route::post("rejectAllApprove", "GroupController@rejectAllApprove");
        
        
        Route::post("changeStatusIn", "GroupController@changeStatusIn");
        Route::post("changeNum", "GroupController@changeNum");
        
        
        Route::post("changeRules", "GroupController@changeRules");
        
        Route::post("kickOneUser", "GroupController@kickOneUser");
    });

    $router->get("user", "UserController@index");
    Route::group(["prefix" => "user"], function (Router $router) {
        Route::post("search", "UserController@search");
        Route::post("kick", "UserController@kick");
        Route::post("restrict", "UserController@restrict");
        Route::post("deleteAndRestrict", "UserController@deleteAndRestrict");
        Route::post("cancelRestrict", "UserController@cancelRestrict");
        Route::post("deleteAndKick", "UserController@deleteAndKick");
        Route::post("unban", "UserController@unban");
        Route::post("unbanall", "UserController@unbanall");
        Route::post("addCheat", "UserController@addCheat");
    });

    $router->get('msg', 'MsgController@index');
    Route::group(["prefix" => "msg"], function (Router $router) {
        Route::post("search", "MsgController@search");
        Route::post("delete", "MsgController@delete");
        Route::post("kick", "MsgController@kick");
        Route::post("restrict", "MsgController@restrict");
        Route::post("addCheat", "MsgController@addCheat");
    });

    Route::group(["prefix" => "word"], function (Router $router) {
        // 发言敏感词
        Route::get("/", "WordController@index");
        Route::post("data", "WordController@data");
        Route::post("add", "WordController@add");
        Route::post("delete", "WordController@delete");
        Route::post("change/level", "WordController@changeLevel");

        // 昵称敏感词
        Route::group(["prefix" => "in"], function (Router $router) {
            Route::get("/", "WordController@in");
            Route::post("data", "WordController@data");
            Route::post("add", "WordController@add");
            Route::post("delete", "WordController@delete");
            Route::post("change/level", "WordController@changeLevel");
        });

        // 用户名敏感词
        Route::group(["prefix" => "username"], function (Router $router) {
            Route::get("/", "WordController@username");
            Route::post("data", "WordController@data");
            Route::post("add", "WordController@add");
            Route::post("delete", "WordController@delete");
            Route::post("change/level", "WordController@changeLevel");
        });

        // 简介敏感词
        Route::group(["prefix" => "intro"], function (Router $router) {
            Route::get("/", "WordController@intro");
            Route::post("data", "WordController@data");
            Route::post("add", "WordController@add");
            Route::post("delete", "WordController@delete");
            Route::post("change/level", "WordController@changeLevel");
        });
    });

    $router->get('message', 'MessageController@index');

    $router->resource('cheat', CheatController::class);
    Route::get("/cheat/create", "CheatController@add");
    Route::post("/cheat/save", "CheatController@save");
    $router->resource('cheatcoin', CheatCoinController::class);
    $router->resource('cheatbank', CheatBankController::class);

    $router->resource('official', OfficialController::class);
    $router->resource('white', WhiteUserController::class);
    $router->resource('whitebot', WhiteUserBotController::class);
    $router->resource('whitefullname', WhiteFullnameController::class);

    $router->resource('operation', LogOperationController::class);
    Route::group(["prefix" => "log"], function (Router $router) {
        $router->resource('ban', LogBanController::class);
        $router->resource('unban', LogUnbanController::class);
        $router->resource('restrict', LogRestrictController::class);
        $router->resource('cancelrestrict', LogCancelrestrictController::class);
        $router->resource('delete', LogDeleteController::class);
        $router->resource('user', LogUserController::class);
        $router->resource('approve', LogApproveController::class);
        $router->resource('search', LogSearchController::class);
        $router->post('group', "LogGroupController@data");
    });

    Route::group(["prefix" => "config"], function (Router $router) {
        Route::get("/", "ConfigController@index");
        Route::post("change", "ConfigController@change");
        
        Route::post("setReplyKey", "ConfigController@setReplyKey");
        Route::post("setReplyVal", "ConfigController@setReplyVal");
    });

    $router->resource('bullhorn', BullhornController::class);
    Route::group(["prefix" => "bullhorn"], function (Router $router) {
        Route::post("add", "BullhornController@add");
    });
    
    $router->resource('searchwordreply', SearchWordReplyController::class);
    $router->resource('searchwordlike', SearchWordLikeController::class);
    
    $router->resource('grouptradereport', GroupTradeReportController::class);

    
    $router->resource('danbao', DanbaoController::class);
    $router->resource('yuefei', LogDanbaoYuefeiController::class);

    $router->resource('wordtemp', WordTempController::class);

    $router->resource('ads', AdsController::class);
});

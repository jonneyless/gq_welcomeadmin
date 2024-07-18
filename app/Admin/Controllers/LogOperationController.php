<?php

namespace App\Admin\Controllers;

use App\Models\AdminUser;
use App\Models\LogOperation;
use App\Service\LogOperationService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;

class LogOperationController extends AdminController
{
    protected $title = '骗子日志';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new LogOperation());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->column('id', __('id'));
        $grid->column('admin_id', __('admin_id'))->display(function ($admin_id) {
            $admin = AdminUser::query()->where("id", $admin_id)->first();
            if ($admin) {
                return $admin["name"];
            } else {
                return "";
            }
        });
        $grid->column('type', __('type'))->display(function ($type) {
            $text = "未知";
            if ($type == LogOperationService::$type_in_black) {
                $text = "加入黑名单";
            } elseif ($type == LogOperationService::$type_out_black) {
                $text = "解除黑名单";
            } elseif ($type == LogOperationService::$type_in_cheat) {
                $text = "加入骗子库";
            } elseif ($type == LogOperationService::$type_in_cheat_bank) {
                $text = "加入骗子库_银行卡";
            } elseif ($type == LogOperationService::$type_in_cheat_coin) {
                $text = "加入骗子库_虚拟币";
            }

            return $text;
        })->filter([
            1 => "加入黑名单",
            2 => "解除黑名单",
            3 => "加入骗子库",
            4 => "加骗子银行卡",
            5 => "加骗子虚拟币",
        ]);
        $grid->column('created_at', __('创建时间'))->sortable();

        $grid->disableCreateButton();
        $grid->disableTools(false);
        $grid->disableActions();

        $grid->paginate(100);

        return $grid;
    }
}
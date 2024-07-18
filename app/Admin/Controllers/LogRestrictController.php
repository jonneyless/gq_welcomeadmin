<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use App\Models\LogRestrictUser;
use Encore\Admin\Grid;
use App\Service\OfficialUserService;
use App\Models\AdminUser;


class LogRestrictController extends AdminController
{
    protected $title = '禁言日志';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new LogRestrictUser());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('group_tg_id', "群tgid");
                $filter->equal('user_tg_id', "用户tgid");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('group_tg_id', __('群tgid'));
        $grid->column('user_tg_id', __('用户tgid'));
        $grid->column('until_data', __('禁言天数'));
        $grid->column('reason', __('原因'));
        $grid->column('admin_id', __('操作人'))->display(function ($admin_id) {
            if ($this->admin_id != "-1") {
                $offical_user = OfficialUserService::one([
                    "tg_id" => $this->admin_id,
                ]);
                if ($offical_user) {
                    return "@" . $offical_user["username"];
                }
            }

            if ($admin_id == -1) {
                return "自动添加";
            }

            $admin = AdminUser::query()->where("id", $admin_id)->first();
            if ($admin) {
                return $admin["name"];
            } else {
                return "";
            }
        });
        $grid->column('created_at', __('创建时间'))->sortable();

        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions();

        $grid->paginate(10);

        return $grid;
    }
}
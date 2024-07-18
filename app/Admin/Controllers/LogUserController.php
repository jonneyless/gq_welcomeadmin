<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use App\Models\LogUser;
use Encore\Admin\Grid;

class LogUserController extends AdminController
{
    protected $title = '用户信息更改日志';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new LogUser());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('tg_id', "用户tgid");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('tg_id', __('用户tgid'));
        $grid->column('before_username', __('修改前用户名'));
        $grid->column('before_fullname', __('修改后昵称'));
        $grid->column('after_username', __('修改前用户名'));
        $grid->column('after_fullname', __('修改后昵称'));

        $grid->column('created_at', __('创建时间'))->sortable();

        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions();

        $grid->paginate(10);

        return $grid;
    }
}
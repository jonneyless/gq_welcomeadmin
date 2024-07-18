<?php

namespace App\Admin\Controllers;

use App\Models\LogApprove;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;

class LogApproveController extends AdminController
{
    protected $title = '进群申请';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new LogApprove());
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
        $grid->column('status', __('类型'))->display(function ($status) {
            if ($status == 1) {
                return "<span class='label label-success'>同意</span>";
            } elseif ($status == 2) {
                return "<span class='label label-default'>等待中</span>";
            } elseif ($status == 3) {
                return "<span class='label label-danger'>禁止</span>";
            }
        })->filter([
            1 => '同意',
            2 => '等待中',
            3 => '禁止',
        ]);
        $grid->column('created_at', __('创建时间'))->sortable();

        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions();

        $grid->paginate(10);

        return $grid;
    }
}
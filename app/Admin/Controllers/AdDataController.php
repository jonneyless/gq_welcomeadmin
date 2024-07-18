<?php

namespace App\Admin\Controllers;

use App\Models\AdData;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class AdDataController extends AdminController
{
    protected $title = '广告';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new AdData());

        $grid->column('info', __('内容'))->limit(30);
        $grid->column('status_send', __('类型'))->display(function ($status_send) {
            if ($status_send == 1) {
                return "<span class='label label-default'>创建成功，等待发送</span>";
            } elseif ($status_send == 2) {
                return "<span class='label label-success'>发送成功</span>";
            } elseif ($status_send == 3) {
                return "<span class='label label-danger'>发送失败</span>";
            } elseif ($status_send == 4) {
                return "<span class='label label-default'>取消发送</span>";
            }
        })->filter([
            1 => '创建成功，等待发送',
            2 => '发送成功',
            3 => '发送失败',
            4 => '取消发送',
        ]);

        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView(true);
            $actions->disableEdit(true);
            $actions->disableDelete(false);
        });

        $grid->paginate(10);

        return $grid;
    }
}
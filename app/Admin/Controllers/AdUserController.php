<?php

namespace App\Admin\Controllers;

use App\Models\AdUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class AdUserController extends AdminController
{
    protected $title = '广告操作人';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new AdUser());

        $grid->column('tg_id', __('tg_id'));

        $grid->disableCreateButton(false);
        $grid->disableFilter(false);
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

    protected function form()
    {
        $form = new Form(new AdUser());

        $form->text('tg_id', __('tg_id'))->rules('required|unique:ad_user', [
            'required' => 'tg_id不能为空',
            'unique' => 'tg_id已存在',
        ]);

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableCreatingCheck();
            $footer->disableEditingCheck();
        });

        return $form;
    }
}
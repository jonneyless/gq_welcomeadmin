<?php

namespace App\Admin\Controllers;

use App\Models\WhiteUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class WhiteUserController extends AdminController
{
    protected $title = '白名单账号';
    protected $description = [
        'index' => ' ',
        "create" => " ",
        "edit" => " "
    ];

    protected function grid()
    {
        $grid = new Grid(new WhiteUser());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('tg_id', "tgid");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('tg_id', __('tg_id'));
        $grid->column('firstname', __('firstname'));
        $grid->column('lastname', __('lastname'));
        $grid->column('username', __('username'));

        $grid->disableCreateButton(false);
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete(false);
        });

        $grid->paginate(100);

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new WhiteUser());

        $form->text('tg_id', __('tg_id'))->rules('required|unique:white_user', [
            'required' => 'tgid不能为空',
            'unique' => 'tgid已存在',
        ]);
        $form->text('firstname', __('firstname'));
        $form->text('lastname', __('lastname'));
        $form->text('username', __('username'));

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

<?php

namespace App\Admin\Controllers;

use App\Models\WhiteFullname;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class WhiteFullnameController extends AdminController
{
    protected $title = '白名单管理员昵称';
    protected $description = [
        'index' => ' ',
        "create" => " ",
        "edit" => " "
    ];

    protected function grid()
    {
        $grid = new Grid(new WhiteFullname());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('fullname', "昵称");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('fullname', __('昵称'))->editable();

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
        $form = new Form(new WhiteFullname());

        $form->text('fullname', __('昵称'))->rules('required|unique:white_group_admin_fullnames', [
            'required' => '昵称不能为空',
            'unique' => '昵称已存在',
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

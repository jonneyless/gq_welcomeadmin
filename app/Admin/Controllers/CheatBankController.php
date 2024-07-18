<?php

namespace App\Admin\Controllers;

use App\Models\CheatBank;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Service\LogOperationService;

class CheatBankController extends AdminController
{
    protected $title = '骗子银行卡';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new CheatBank());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('num', "卡号");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('name', __('姓名'));
        $grid->column('num', __('卡号'));
        $grid->column('created_at', __('创建时间'))->sortable();

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
        $form = new Form(new CheatBank());

        $form->text('name', __('姓名'))->rules('required', [
            'required' => '姓名不能为空',
        ]);
        $form->text('num', __('卡号'))->rules('required|unique:cheat_bank', [
            'required' => '卡号不能为空',
            'unique' => '卡号已存在',
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

    public function store()
    {
        $admin = auth()->user();

        LogOperationService::create([
            "type" => LogOperationService::$type_in_cheat_bank,
            "info" => json_encode(request()->all()),
            "admin_id" => $admin["id"],
        ]);

        return $this->form()->store([
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
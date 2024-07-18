<?php

namespace App\Admin\Controllers;

use App\Models\CheatCoin;
use App\Service\LogOperationService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class CheatCoinController extends AdminController
{
    protected $title = '骗子虚拟币';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new CheatCoin());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('address', "地址");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('address', __('地址'));
        $grid->column('reason', __('原因'));
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
        $form = new Form(new CheatCoin());

        $form->text('address', __('地址'))->rules('required|unique:cheat_coin', [
            'required' => '地址不能为空',
            'unique' => '地址已存在',
        ]);
        $form->text('reason', __('原因'));

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
            "type" => LogOperationService::$type_in_cheat_coin,
            "info" => json_encode(request()->all()),
            "admin_id" => $admin["id"],
        ]);

        return $this->form()->store([
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
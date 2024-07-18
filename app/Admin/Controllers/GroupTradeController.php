<?php

namespace App\Admin\Controllers;

use App\Models\GroupTrade;
use App\Models\Group;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Service\LogOperationService;

class GroupTradeController extends AdminController
{
    protected $title = '群交易记录';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new GroupTrade());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->like('title', "群名");
                $filter->equal('trade_at', "日期");
            });
        });
        
        // $grid->column('group_tg_id', __('群名'))->display(function ($group_tg_id) {
        //     $text = "";
        //     $group = Group::query()->where("chat_id", $group_tg_id)->first();
        //     if ($group) {
        //         $text = $group["title"];
        //     }
        //     return $text;
        // });
        $grid->column('title', __('群名'));
        $grid->column('trade_at', __('日期'));

        $grid->disableCreateButton(false);
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView(true);
            $actions->disableEdit(true);
            $actions->disableDelete(false);
        });

        $grid->paginate(100);

        return $grid;
    }

    protected function form()
    {
        $groups = Group::query()->where("flag", 2)
            ->where("status_in", 1)
            ->select(["chat_id", "title"])
            ->get();
        $groupsData = [];
        foreach ($groups as $group) {
            $groupsData[$group["chat_id"]] = $group["title"];
        }
        
        $form = new Form(new GroupTrade());

        $form->select('group_tg_id', __('群名'))->options($groupsData)->rules('required', [
            'required' => '群名不能为空',
        ]);
        $form->date('trade_at', __('日期'))->format('YYYY-MM-DD')->rules('required', [
            'required' => '日期不能为空',
        ]);

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableCreatingCheck(false);
            $footer->disableEditingCheck();
        });

        return $form;
    }
    
    public function store()
    {
        $title = "";
        
        $data = request()->all();
        
        if (is_right_data($data, "group_tg_id")) {
            $group_tg_id = $data["group_tg_id"];
            if ($group_tg_id) {
                $group = Group::query()->where("chat_id", $group_tg_id)->first();
                if ($group) {
                    $title = $group["title"];
                }
            }
        }
        
        return $this->form()->store([
            "title" => $title,
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
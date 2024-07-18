<?php

namespace App\Admin\Controllers;

use App\Models\SearchWordReply;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class SearchWordReplyController extends AdminController
{
    protected $title = '搜索回复';
    protected $description = [
        'index' => ' ',
        "create" => " ",
        "edit" => " "
    ];

    protected function grid()
    {
        $grid = new Grid(new SearchWordReply());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('key', "关键字");
            });
        });

        $grid->column('id', __('id'))->sortable();
        $grid->column('key', __('关键字'));
        $grid->column('val', __('回复'))->limit(100);;
        $grid->column('created_at', __('创建时间'));

        $grid->disableCreateButton(false);
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit(false);
            $actions->disableDelete(false);
        });

        $grid->paginate(100);

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new SearchWordReply());

        $form->text('key', __('关键字'))->rules('required|unique:search_word_reply', [
            'required' => '关键字不能为空',
            'unique' => '关键字已存在',
        ]);

        $form->text('val', __('回复'));

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
        return $this->form()->store([
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}

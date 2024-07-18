<?php

namespace App\Admin\Controllers;

use App\Models\SearchWordLike;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class SearchWordLikeController extends AdminController
{
    protected $title = '搜索同义词';
    protected $description = [
        'index' => '多个同义词通过 , 分割',
        "create" => "多个同义词通过 , 分割",
        "edit" => "多个同义词通过 , 分割"
    ];

    protected function grid()
    {
        $grid = new Grid(new SearchWordLike());

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('name', "同义词");
            });
        });

        $grid->column('id', __('id'))->sortable();
        $grid->column('name', __('同义词'));
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
        $form = new Form(new SearchWordLike());

        $form->text('name', __('同义词'))->rules('required', [
            'required' => '同义词不能为空',
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
        $data = request()->all();
        
        $name = $data["name"];
        $name = str_replace(" ", "", $name);
        $name = str_replace("，", ",", $name);
        
        return $this->form()->store([
            "name" => $name,
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
    
    public function update($id)
    {
        $data = request()->all();

        $name = $data["name"];
        $name = str_replace(" ", "", $name);
        $name = str_replace("，", ",", $name);
        
        return $this->form()->update($id, null, [
            "name" => $name,
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}

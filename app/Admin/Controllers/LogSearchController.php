<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use App\Models\LogSearch;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\DB;

class LogSearchController extends AdminController
{
    protected $title = '用户搜索词';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new LogSearch());

        $grid->model()->groupBy('text')
            ->select([DB::raw("sum(id) as temp"), 'text', "id"])
            ->orderBy("text", "desc");
        

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('text', "搜索内容");
            });
        });

        $grid->column('text', __('搜索内容'));
        $grid->column('temp', __('搜索量'))->sortable();
        $grid->column('id', __('搜索结果数量'))->display(function ($id) {
            $text = $this->text;
            
            $obj = LogSearch::query()->where("text", $text)
                ->orderBy("id", "desc")
                ->first();
            if ($obj) {
                return $obj["data_count"];
            } else {
                return "";
            }
        });
        
        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions();

        $grid->paginate(10);

        return $grid;
    }
}
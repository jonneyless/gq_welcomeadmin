<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AdminController;
use App\Models\WordTemp;
use Encore\Admin\Grid;
use App\Models\AdminUser;

class WordTempController extends AdminController
{
    protected $title = '临时敏感词';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new WordTemp());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->like('name', "原因");
            });
        });

        $grid->column('name', __('临时敏感词'));
        $grid->column('user_tg_id', __('添加人'));
        $grid->column('created_at', __('添加时间'))->sortable();

        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions();

        $grid->paginate(100);

        return $grid;
    }
}
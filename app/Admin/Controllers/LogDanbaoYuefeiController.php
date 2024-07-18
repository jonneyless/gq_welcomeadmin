<?php

namespace App\Admin\Controllers;

use App\Models\LogDanbaoYuefei;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use App\Service\GroupBusinessService;
use App\Admin\Extensions\LogDanbaoYuefeiExporter;



class LogDanbaoYuefeiController extends AdminController
{
    protected $title = '月费收取记录';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new LogDanbaoYuefei());
        $grid->model()
            ->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                // $filter->equal('group_tg_id', "群tgid");
                $filter->like('title', "群名");
                // $filter->equal('num', "群编号");
                
                // $filter->like('user_info', "收取人信息");
            });
        });

        $grid->column('id', 'id')->hide();
        $grid->column('type', '类型')->display(function ($type) {
            if ($type == 1) {
                return "<span class='label label-default'>上押扣除</span>";
            } elseif ($type == 2) {
                return "<span class='label label-default'>下押扣除</span>";
            } elseif ($type == 3) {
                return "<span class='label label-default'>日常缴纳</span>";
            }
        })->filter([
            1 => '上押扣除',
            2 => '下押扣除',
            3 => '日常缴纳',
        ]);
        $grid->column('title', '群名');
        $grid->column('uid', '工作人员');
        $grid->column('money', '金额')->sortable();
        // $grid->column('user_info', '用户信息')->hide()->editable();
        // $grid->column('user_tg_id', '用户tgid')->hide()->editable();
        // $grid->column('remark', '备注')->editable()->hide();
        $grid->column('currency', '币种')->filter([
            1 => 'usdt',
            2 => '美金',
            3 => '汇旺u',
        ])->radio([
            1 => 'usdt',
            2 => '美金',
            3 => '汇旺u',
        ]);
        $grid->column('month', '月份')->filter([
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
        ]);
        $grid->column('created_at_jinbian', "金边时间")->sortable();

        // $grid->column('group_tg_id', __('群tgid'))->hide();
        // $grid->column('title', __('群名'));
        $grid->column('remark', __('备注'));
        
        // $business = GroupBusinessService::all();
        // $grid->column('business_detail_type', __('业务类型'))->display(function ($business_detail_type) {
        //     return GroupBusinessService::one_id($business_detail_type);
        // })->filter($business);
        
        // $grid->column('user_info', __('收取人信息'));
        // $grid->column('money', __('金额'));
        // $grid->column('type', __('收取状态'))->filter([
        //     1 => '正常收取',
        //     2 => '后来补充',
        // ])->radio([
        //     1 => '正常收取',
        //     2 => '后来补充',
        // ]);
        // $grid->column('flag', __('是否收取'))->display(function ($flag) {
        //     if ($flag == 1) {
        //         return "<span class='label label-success'>正常收取</span>";
        //     } elseif ($flag == 2) {
        //         return "<span class='label label-danger'>不收取</span>";
        //     }
        // })->filter([
        //     1 => '正常收取',
        //     2 => '不收取',
        // ]);
        // $grid->column('created_at', __('应收取日'));
        // $grid->column('start_at', __('实际收取日'));
        
        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });

        $grid->paginate(100);

        $grid->disableRowSelector(false);
        $grid->disableExport(false);
        
        $arr_columns = $this->get_columns(request()->all());
        $e = new LogDanbaoYuefeiExporter($grid, $arr_columns);
        $grid->exporter($e);

        return $grid;
    }
    
    public function get_columns($data)
    {
        $arr_columns = [
            "type" => "类型",
            "title" => "群名",
            "uid" => "工作人员",
            "money" => "金额",
            "currency" => "币种",
            "month" => "月份",
            "created_at_jinbian" => "金边时间",
        ];
        
        if (is_right_data($data, "_columns_") and count($data["_columns_"]) > 0) {
            $_columns_ = $data["_columns_"];
            $_columns_ = explode(",", $_columns_);
            
            $arr = [];
            foreach ($arr_columns as $key => $val) {
                foreach ($_columns_ as $k => $v) {
                    if ($key == $v) {
                        $arr[$key] = $val;
                    }
                }
            }

            if (count($arr) > 0) {
                $arr_columns = $arr;
            }
        }
        
        return $arr_columns;
    }
}
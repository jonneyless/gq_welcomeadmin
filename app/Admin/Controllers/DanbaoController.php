<?php

namespace App\Admin\Controllers;

use App\Models\LogDanbao;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use Illuminate\Support\Facades\Log;
use App\Service\GroupBusinessService;
use App\Admin\Extensions\DanbaoExporter;



class DanbaoController extends AdminController
{
    protected $title = '担保记录';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new LogDanbao());
        $grid->model()
            ->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('group_tg_id', "群tgid");
                $filter->like('title', "群名");
                $filter->equal('num', "群编号");
                
                $filter->like('info_jiaoyiyuan', "交易员");
                $filter->like('info_boss', "群老板");
                $filter->like('info_yewuyuan', "业务员");
                $filter->between('created_at', "开群时间")->date();
            });
        });

        $grid->column('group_tg_id', __('群tgid'))->hide();
        $grid->column('title', __('群名'));
        $grid->column('num', __('群编号'))->sortable();
        
        $grid->column('info_creator', __('群主'))->limit(50);
        $grid->column('info_jiaoyiyuan', __('交易员'))->limit(50);
        $grid->column('info_boss', __('群老板'))->limit(50);
        $grid->column('info_yewuyuan', __('业务员'))->limit(50);
        
        $business = GroupBusinessService::all();
        $grid->column('business_detail_type', __('业务类型'))->display(function ($business_detail_type) {
            return GroupBusinessService::one_id($business_detail_type);
        })->filter($business);
        
        // $grid->column('yajin_u', __('押金u'));
        // $grid->column('yajin_m', __('押金m'));
        // $grid->column('yajin', __('总押金'));
        
        $grid->column('yuefei', __('月费'))->editable()->sortable();
        $grid->column('yuefei_day', __('月费收取日'))->filter([
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
        ])->editable();
        
        $grid->column('remark', __('备注'))->editable();

        $grid->column('tuoguan', __('是否托管'))->filter([
            1 => '托管',
            2 => '否',
        ])->radio([
            1 => '托管',
            2 => '否',
        ]);
        
        $grid->column('status', __('状态'))->display(function ($status) {
            if ($status == 1) {
                return "<span class='label label-success'>开启</span>";
            } else {
                return "<span class='label label-danger'>关闭</span>";
            }
        })->filter([
            1 => '开启',
            2 => '关闭',
        ]);
        
        $grid->column('created_at', __('开群时间'))->editable()->sortable();
        $grid->column('ended_at', __('结束时间'));
        $grid->column('remark1', __('下押备注'))->editable();
        $grid->column('remark2', __('交易情况'))->editable();

        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit();
            $actions->disableDelete();
        });

        $grid->paginate(100);

        $grid->disableRowSelector(false);
        $grid->disableExport(false);
        
        $arr_columns = $this->get_columns(request()->all());
        $e = new DanbaoExporter($grid, $arr_columns);
        $grid->exporter($e);

        return $grid;
    }

    public function get_columns($data)
    {
        $arr_columns = [
            "title" => "群名",
            "num" => "群编号",
            "info_creator" => "群主",
            "info_jiaoyiyuan" => "交易员",
            "info_boss" => "群老板",
            "info_yewuyuan" => "业务员",
            "business_detail_type" => "业务类型",
            "yuefei" => "月费",
            "yuefei_day" => "月费收取日",
            "remark" => "备注",
            "tuoguan" => "托管",
            "status" => "状态",
            "created_at" => "开群时间",
            "ended_at" => "结束时间",
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

    protected function form()
    {
        $form = new Form(new LogDanbao());

        $form->text('yuefei', __('月费'));
        $form->text('yuefei_day', __('月费收取日'));
        $form->text('created_at', __('开群时间'));
        $form->text('tuoguan', __('是否托管'));
        $form->text('remark', __('备注'));
        $form->text('remark1', __('备注'));
        $form->text('remark2', __('备注'));

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
    
    public function update($id)
    {
        $data = request()->all();
        
        $arr = [];
        
        if (is_right_data($data, "name")) {
            if ($data["name"] == "created_at") {
                $arr["created_at"] = $data["value"];
                $arr["yuefei_day"] = intval(date("d", strtotime($data["value"])));
            } else {
                $arr[$data["name"]] = $data["value"];
            }
        }
        
        return $this->form()->update($id, null, $arr); 
    }
}
<?php

namespace App\Admin\Controllers;

use App\DataModels\GroupTradeReport as DataGroupTradeReport;
use App\Service\GroupTradeReportService;
use App\Models\GroupTradeReport;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Service\GroupService;
use App\Service\GroupAdminService;
use App\Models\UserNew;
use App\Admin\Actions\GroupTradeReportOver;
use Illuminate\Support\MessageBag;
use Encore\Admin\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;


class GroupTradeReportController extends AdminController
{
    protected $title = '报备';
    protected $description = [
        'index' => ' '
    ];

    public function home(Content $content)
    {
        return $content
            ->title('报备管理')
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(DataGroupTradeReport::index());
                });
            });
    }

    public function data()
    {
        $parameters = request()->all();
        
        $result = GroupTradeReportService::search($parameters);
        
        return handle_response($result, "success");
    }
    
    public function del()
    {
        $parameters = request()->all();
        
        if (is_wrong_data($parameters, "ids")) {
            return handle_response([], "报备数据不能为空");
        }
        if (is_wrong_data($parameters, "title")) {
            return handle_response([], "群名不能为空");
        }
        if (is_wrong_data($parameters, "group_tg_id")) {
            return handle_response([], "群tgid不能为空");
        }
        if (is_wrong_data($parameters, "startTime")) {
            // return handle_response([], "开始时间不能为空");
        }
        if (is_wrong_data($parameters, "endTime")) {
            // return handle_response([], "结束时间不能为空");
        }
        
        $ids = GroupTradeReportService::get_ids($parameters);
        
        foreach ($parameters["ids"] as $id) {
            if (!in_array($id, $ids)) {
                return handle_response([], "存在异常数据");
            }
        }
        
        GroupTradeReportService::del($parameters["ids"]);
        
        return handle_response([], "操作成功");
    }

    public function over()
    {
        $parameters = request()->all();
        
        if (is_wrong_data($parameters, "ids")) {
            return handle_response([], "报备数据不能为空");
        }
        if (is_wrong_data($parameters, "title")) {
            return handle_response([], "群名不能为空");
        }
        if (is_wrong_data($parameters, "group_tg_id")) {
            return handle_response([], "群tgid不能为空");
        }
        if (is_wrong_data($parameters, "startTime")) {
            // return handle_response([], "开始时间不能为空");
        }
        if (is_wrong_data($parameters, "endTime")) {
            // return handle_response([], "结束时间不能为空");
        }
        
        $ids = GroupTradeReportService::get_ids($parameters);

        foreach ($parameters["ids"] as $id) {
            if (!in_array($id, $ids)) {
                return handle_response([], "存在异常数据");
            }
        }
        
        GroupTradeReportService::over($parameters["ids"]);
        
        return handle_response([], "操作成功");
    }

    public function change()
    {
        $parameters = request()->all();
        
        if (is_wrong_data($parameters, "ids")) {
            return handle_response([], "报备数据不能为空");
        }
        if (is_wrong_data($parameters, "title")) {
            return handle_response([], "群名不能为空");
        }
        if (is_wrong_data($parameters, "group_tg_id")) {
            return handle_response([], "群tgid不能为空");
        }
        if (is_wrong_data($parameters, "new_group_tg_id")) {
            return handle_response([], "新群tgid不能为空");
        }
        if (is_wrong_data($parameters, "startTime")) {
            // return handle_response([], "开始时间不能为空");
        }
        if (is_wrong_data($parameters, "endTime")) {
            // return handle_response([], "结束时间不能为空");
        }
        
        if ($parameters["group_tg_id"] == $parameters["new_group_tg_id"]) {
            return handle_response([], "新旧群tgid不能相同");
        }
        
        $ids = GroupTradeReportService::get_ids($parameters);

        foreach ($parameters["ids"] as $id) {
            if (!in_array($id, $ids)) {
                return handle_response([], "存在异常数据");
            }
        }
        
        $group = GroupService::get([
            "chat_id" => $parameters["new_group_tg_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "新群不存在");
        }
        
        GroupTradeReportService::change($parameters["ids"], $group);
        
        return handle_response([], "操作成功");
    }

    protected function grid()
    {
        Admin::css('/css/grouptradereport.css');
        
        $grid = new Grid(new GroupTradeReport());
        $grid->model()
            ->where("status", "!=", 2)
            ->orderBy('created_at', 'desc');
            
        $grid->expandFilter();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('group_tg_id', "群tgid");
                $filter->like('title', "群名");
                $filter->between('created_at', "创建时间")->datetime();
            });
        });

        $grid->column('id', __('id'));
        $grid->column('group_tg_id', __('群tgid'));
        $grid->column('title', __('群名'));
        $grid->column('admin_tg_id', __('报备管理tgid'));
        $grid->column('admin_username', __('报备管理用户名'));
        $grid->column('user_tg_id', __('客户tgid'));
        $grid->column('user_username', __('客户用户名'));
        $grid->column('money', __('报备金额u'));
        $grid->column('uid', __('编号'));
        $grid->column('status', __('类型'))->display(function ($flag) {
            if ($flag == 1) {
                return "<span class='label label-success'>已确定(已发送在群里)</span>";
            } elseif ($flag == 2) {
                return "<span class='label label-default'>待定</span>";
            } elseif ($flag == 3) {
                return "<span class='label label-default'>管理取消</span>";
            } elseif ($flag == 4) {
                return "<span class='label label-default'>客户取消</span>";
            } elseif ($flag == 5) {
                return "<span class='label label-success'>客户已经确认</span>";
            } elseif ($flag == 6) {
                return "<span class='label label-default'>官方取消</span>";
            } elseif ($flag == 9) {
                return "<span class='label label-default'>发送群里失败</span>";
            } elseif ($flag == 10) {
                return "<span class='label label-default'>管理已完成</span>";
            } elseif ($flag == 11) {
                return "<span class='label label-success'>客户已完成</span>";
            } elseif ($flag == 12) {
                return "<span class='label label-success'>官方已完成</span>";
            } elseif ($flag == 13) {
                return "<span class='label label-default'>报备后取消中</span>";
            } elseif ($flag == 14) {
                return "<span class='label label-default'>报备后成功取消</span>";
            }
        })->filter([
            1 => '已确定(已发送在群里)',
            2 => '待定',
            3 => '管理取消',
            4 => '客户取消',
            5 => '客户已经确认',
            6 => '官方取消',
            9 => '发送群里失败',
            10 => '管理已完成',
            11 => '客户已完成',
            12 => '官方已完成',
            13 => '报备后取消中',
            14 => '报备后成功取消',
        ]);
        $grid->column('info', __('报备内容'))->limit(50);
        // $grid->column('num', __('金额'));
        $grid->column('day', __('天数'));
        $grid->column('status_type', __('类型'))->display(function ($status_type) {
            if ($status_type == 1) {
                return "<span class='label label-success'>tg录入</span>";
            } elseif ($status_type == 2) {
                return "<span class='label label-primary'>后台录入</span>";
            }
        })->filter([
            1 => 'tg录入',
            2 => '后台录入',
        ]);
        $grid->column('created_at', __('创建时间'))->sortable();

        $grid->disableCreateButton(false);
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->disableColumnSelector(false);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
            $actions->disableEdit(false);
            $actions->disableDelete();
        });


        $grid->disableRowSelector(false);

        $grid->paginate(30);

        return $grid;
    }
    
    protected function form()
    {
        $form = new Form(new GroupTradeReport());

        $form->select('group_tg_id', __('群名'))->options(function ($id) {
            $group = GroupService::get([
                "chat_id" => $id,
                "is_one_obj" => true,
            ]);
            $title = "";
            if ($group) {
                $title = $group["title"];
            }
            
            return [$id => $title];
        })->ajax('/api/group/titles')->required();
        
        $form->text('admin_tg_id', __('群老板tgid'))->required();
        $form->text('user_tg_id', __('客户tgid'))->required();
        $form->text('money', __('报备金额u'))->required();
        $form->text('day', __('天数'))->required();
        $form->radio('status', __('状态'))->options(['5' => '客户已经确认', '11' => '客户已完成 '])->default('5');
        $form->textarea('info', __('报备内容'))->rows(20)->required();
        
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
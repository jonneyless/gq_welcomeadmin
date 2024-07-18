<?php

namespace App\Admin\Controllers;

use App\DataModels\Group as DataGroup;
use App\Jobs\RejectApprove;
use App\Models\Group;
use App\Service\GroupBusinessService;


use App\Service\RedisService;
use App\Service\AssistService;
use App\Service\GroupNoticeService;
use App\Service\GroupAdminService;
use App\Service\GroupService;
use App\Service\LogApproveService;
use App\Service\OfficialUserService;
use App\Service\UserGroupService;
use App\Service\GroupLinkService;
use App\Service\LogBanUserService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;
use App\Service\CheatService;
use Illuminate\Support\Facades\Log;
use Encore\Admin\Form;
use App\Service\LogGroupService;


class GroupController extends AdminController
{
    protected $title = '群组管理';
    protected $description = [
        'index' => "<span style='color:red;'>红色字体为骗子群</span>",
        "show" => " ",
    ];

    public function grid()
    {
        $grid = new Grid(new Group());
        $grid->model()
            ->where("deleted", 2)
            ->orderBy("status_in", "asc");

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('id', "id");
                $filter->equal('chat_id', "群tgid");
                $filter->like('title', "群名");
                $filter->equal('group_num', "编号");
            });
        });
        
        $grid->column('id', __('id'))->hide()->sortable();
        $grid->column('chat_id', __('chat_id'))->hide();
        $grid->column('title', __('群名'))->display(function () {
            if ($this->url) {
                if ($this->flag == 3) {
                    return "<a style='color:red;' target='_blank' href='" . $this->url . "'>" . $this->title . "</a>";
                } else {
                    return "<a target='_blank' href='" . $this->url . "'>" . $this->title . "</a>";
                }
            } else {
                if ($this->flag == 3) {
                    return "<span style='color:red;'>" . $this->title . "</span>";
                } else {
                    return $this->title;
                }
            }
        });
        $grid->column('num', __('群人数'))->sortable();
        $grid->column('num_real', __('群人数(过滤已注销)'))->sortable()->hide();
        $grid->column('group_num', __('群编号'))
        // ->editable()
        ->sortable();
        $grid->column('flag', __('类型'))->display(function ($flag) {
            if ($flag == 1) {
                return "<span class='label label-default'>待定</span>";
            } elseif ($flag == 2) {
                return "<span class='label label-success'>真公群</span>";
            } elseif ($flag == 3) {
                return "<span class='label label-danger'>骗子群</span>";
            } elseif ($flag == 4) {
                return "<span class='label label-primary'>游戏群</span>";
            }
        })->filter([
            1 => '待定',
            2 => '真公群',
            3 => '骗子群',
            4 => '游戏群',
        ]);
        
        $business = GroupBusinessService::all();
        $grid->column('business_detail_type', __('业务类型'))->display(function ($business_detail_type) {
            return GroupBusinessService::one_id($business_detail_type);
        })->filter($business);
        
        $grid->column('status_in', __('机器人状态'))->display(function ($status_in) {
            if ($status_in == 1) {
                return "<span class='label label-success'>在群</span>";
            } elseif ($status_in == 2) {
                return "<span class='label label-danger'>已离群</span>";
            } elseif ($status_in == 3) {
                return "<span class='label label-default'>待判断</span>";
            }
        })->filter([
            1 => '在群',
            2 => '已离群',
            3 => '待判断',
        ]);
        $grid->column('xianjing_status', __('陷阱模式'))->display(function ($xianjing_status) {
            if ($xianjing_status == 1) {
                return "<span class='label label-danger'>开启</span>";
            } elseif ($xianjing_status == 2) {
                return "<span class='label label-success'>关闭</span>";
            }
        })->filter([
            1 => '开启',
            2 => '关闭',
        ]);
        $grid->column('open_status', __('上课状态'))->display(function ($open_status) {
            if ($open_status == 1) {
                return "<span class='label label-success'>上课</span>";
            } elseif ($open_status == 2) {
                return "<span class='label label-danger'>下课</span>";
            }
        })->filter([
            1 => '上课',
            2 => '下课',
        ])->hide();
        $grid->column('welcome_status', __('欢迎语状态'))->display(function ($welcome_status) {
            if ($welcome_status == 1) {
                return "<span class='label label-success'>开启</span>";
            } elseif ($welcome_status == 2) {
                return "<span class='label label-danger'>关闭</span>";
            }
        })->filter([
            1 => '开启',
            2 => '关闭',
        ])->hide();
        $grid->column('has_hidden_members', __('隐藏成员'))->display(function ($has_hidden_members) {
            if ($has_hidden_members == 1) {
                return "<span class='label label-success'>是</span>";
            } else {
                return "<span class='label label-danger'>否</span>";
            }
        })->filter([
            1 => '是',
            2 => '否',
        ]);
        $grid->column('status_money', __('押金状态'))->display(function ($status_money) {
            if ($status_money == 1) {
                return "<span class='label label-success'>已上押</span>";
            } elseif ($status_money == 2) {
                return "<span class='label label-danger'>已退押</span>";
            } elseif ($status_money == 3) {
                return "<span class='label label-default'>待判断</span>";
            }
        })->filter([
            1 => '已上押',
            2 => '已退押',
            3 => '待判断',
        ])->hide();
        $grid->column('yajin_m', __('押金(美金)'))->sortable();
        $grid->column('yajin_u', __('押金(u)'))->sortable();
        $grid->column('yajin', __('总押金'))->sortable();
        // ->totalRow();
        
        $grid->column('yajin_all', __('总押金(所有)'));
        
        $grid->column('status_approve_vip', __('vip自动通过'))->display(function ($status_approve_vip) {
            if ($status_approve_vip == 1) {
                return "<span class='label label-success'>开启</span>";
            } elseif ($status_approve_vip == 2) {
                return "<span class='label label-danger'>关闭</span>";
            }
        })->filter([
            1 => '开启',
            2 => '关闭',
        ])->hide();
        $grid->column('actvie_status', __('活跃状态'))->display(function ($actvie_status) {
            if ($actvie_status == 1) {
                return "<span class='label label-success'>活跃</span>";
            } elseif ($actvie_status == 2) {
                return "<span class='label label-danger'>不活跃</span>";
            }
        })->filter([
            1 => '活跃',
            2 => '不活跃',
        ])->hide();
        $grid->column('search_sort', __('搜索优先级'))->sortable()->hide();
        $grid->column('created_at', __('迎宾机器人进群时间'));
        $grid->column('out_at', __('注销时间'))->hide();

        $user = auth()->user();
        
        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->disableColumnSelector(false);


        if ($user->isRole("administrator")) {
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView(false);
                $actions->disableEdit();
                $actions->disableDelete();
            });
        } else {
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView(false);
                $actions->disableEdit();
                $actions->disableDelete();
            });
        }
        
        $grid->disableRowSelector();

        $grid->paginate(100);

        return $grid;
    }

    public function show($id, Content $content)
    {
        Admin::disablePjax();

        return $content
            ->title($this->title)
            ->description(" ")
            ->row(function (Row $row) use ($id) {
                $row->column(12, function (Column $column) use ($id) {
                    $column->append(DataGroup::detail($id));
                });
            });
    }
    
    protected function form()
    {
        $form = new Form(new Group());

        $form->text('id', __('id'));
        $form->text('group_num', __('group_num'));
        

        return $form;
    }
    
    public function notice(Request $request)
    {
        $result = GroupNoticeService::get([
            "chat_id" => $request->get("chat_id"),
            "is_arr" => true,
        ]);

        return array(
            "draw" => $request->get("draw"),
            'recordsTotal' => count($result),
            "recordsFiltered" => count($result),
            'data' => $result,
        );
    }

    public function noticeAdd()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "msg")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "space")) {
            return handle_response([], "error");
        }
        if (!is_numeric($parameters["space"])) {
            return handle_response([], "error");
        }
        if (!is_numeric($parameters["flag"])) {
            return handle_response([], "error");
        }
        if ($parameters["space"] < 0) {
            return handle_response([], "发言间隔最小1分钟");
        }

        GroupNoticeService::add($parameters);

        return handle_response([], "success");
    }

    public function noticeDelete()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "id")) {
            return "error";
        }
        $groupNotice = GroupNoticeService::get([
            "id" => $parameters["id"],
            "is_one_obj" => true,
        ]);
        if (!$groupNotice) {
            return "error";
        }

        GroupNoticeService::delete($groupNotice);

        return handle_response([], "success");
    }

    public function kickAll()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群组不存在");
        }
        
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 11,
            "info" => "踢出群组内所有非管理员用户",
            "admin" => auth()->user(),
        ]);
        
        $admin_user = auth()->user();

        $admins = GroupAdminService::get([
            "chat_id" => $parameters["chat_id"],
            "is_arr" => true,
        ]);
        
        $users = UserGroupService::get_in($parameters["chat_id"]);
        
        $arr = [];
        foreach ($users as $user) {
            $is_admin = false;
            foreach ($admins as $admin) {
                if ($user["user_tg_id"] == $admin["user_id"]) {
                    $is_admin = true;
                    break;
                }
            }
            
            if (!$is_admin) {
                if (!in_array($user["user_tg_id"], $arr)) {
                    array_push($arr, $user["user_tg_id"]);
                }
            }
        }
        
        // AssistService::saveKickAllUsers($parameters["chat_id"], $arr);
        
        foreach ($arr as $user_tg_id) {
            AssistService::saveRedisData4TG([
                "type_ops" => "ban",
                "group_tg_id" => $parameters["chat_id"],
                "user_tg_id" => $user_tg_id,
                "admin_id" => $admin_user["id"],
            ]);
        }
        
        return handle_response([], "success");
    }

    public function unban()
    {
        $parameters = request()->all();
        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 13,
            "info" => "移除该群所有黑名单",
            "admin" => auth()->user(),
        ]);

        $admin_user = auth()->user();

        $admins = GroupAdminService::get([
            "chat_id" => $parameters["chat_id"],
            "is_arr" => true,
        ]);

        // $users = UserGroupService::get_out($parameters["chat_id"]);
        
        $users = LogBanUserService::users([
            "group_tg_id" => $parameters["chat_id"],
        ]);
        
        $arr = [];
        foreach ($users as $user) {
            $is_admin = false;
            foreach ($admins as $admin) {
                if ($user["user_tg_id"] == $admin["user_id"]) {
                    $is_admin = true;
                    break;
                }
            }
            
            if (!$is_admin) {
                if (!in_array($user["user_tg_id"], $arr)) {
                    array_push($arr, $user["user_tg_id"]);
                }
            }
        }
        
        foreach ($arr as $user_tg_id) {
            AssistService::saveRedisData4TG([
                "type_ops" => "unban",
                "group_tg_id" => $parameters["chat_id"],
                "user_tg_id" => $user_tg_id,
                "admin_id" => $admin_user["id"],
            ]);
        }

        return handle_response([], "success");
    }

    public function changeTitle(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "title")) {
            return handle_response([], "title不能为空");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群组不存在");
        }
        
        $new_chat_title = $parameters["title"];
        $old_chat_title = $group["title"];

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 1,
            "info" => sprintf("修改群名：旧群名 %s，新群名%s", $group["title"], $parameters["title"]),
            "admin" => auth()->user(),
        ]);

        $result = updateChatTitle($group["chat_id"], $parameters["title"]);

        if (is_right_data($result, "ok") && $result["ok"]) {
            $group->title = $parameters["title"];
            $group->save();
            
            $url = "http://welcome.huionedb.com/api/saveChangeTitle";
            curlPost($url, [
                "key" => "huionedbw",
                "chat_id" => $parameters["chat_id"],
                "new_chat_title" => $new_chat_title,
                "old_chat_title" => $old_chat_title,
            ]);
            
            return handle_response([], "标题更改成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "标题更改失败");
            }
        }
    }
    
    public function changeRules(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "rules")) {
            return handle_response([], "群规不能为空");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群组不存在");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 26,
            "info" => sprintf("修改群名：旧群规 %s，新群规%s", $group["rules"], $parameters["rules"]),
            "admin" => auth()->user(),
        ]);
        
        $group->rules = $parameters["rules"];
        $group->save();
        
        return handle_response([], "更改成功");
    }
    
    public function changeDesc(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "description")) {
            return handle_response([], "description不能为空");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群组不存在");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 2,
            "info" => sprintf("修改简介：旧简介 %s，新简介 %s", $group["description"], $parameters["description"]),
            "admin" => auth()->user(),
        ]);

        $result = updateChatDescription($group["chat_id"], $parameters["description"]);

        if (is_right_data($result, "ok") && $result["ok"]) {
            $group->description = $parameters["description"];
            $group->save();
            return handle_response([], "群简介更改成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "群简介更改失败");
            }
        }
    }

    public function changeWelcomeStatus()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "type")) {
            return handle_response([], "error");
        }
        if (!in_array($parameters["type"], [1, 2])) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        $arr = [
            1 => '开启',
            2 => '关闭',
        ];
        $old_info = "";
        $new_info = "";
        foreach ($arr as $key => $val) {
            if ($key == $group["welcome_status"]) {
                $old_info = $val;
            }
            if ($key == $parameters["type"]) {
                $new_info = $val;
            }
        }
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 9,
            "info" => sprintf("修改欢迎语状态：旧状态 %s，新状态 %s", $old_info, $new_info),
            "admin" => auth()->user(),
        ]);

        GroupService::changeWelcomeStatus($group, $parameters["type"]);

        return handle_response([], "success");
    }

    public function changeInfo(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id") or true) {
            return handle_response([], "暂时关闭");
        }
        if (is_wrong_data($parameters, "info")) {
            return handle_response([], "info不能为空");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群组不存在");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 3,
            "info" => sprintf("修改欢迎语：旧欢迎语 %s，新欢迎语 %s", $group["welcome_info"], $parameters["info"]),
            "admin" => auth()->user(),
        ]);

        GroupService::setWelcomeInfo($group, $parameters["info"]);

        return handle_response([], "欢迎语更改成功");
    }

    public function changeFlag()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "flag")) {
            return handle_response([], "error");
        }
        if (!in_array($parameters["flag"], [1, 2, 3, 4])) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        $arr = [
            1 => '待定',
            2 => '真公群',
            3 => '骗子群',
            4 => "游戏群",
        ];
        $old_info = "";
        $new_info = "";
        foreach ($arr as $key => $val) {
            if ($key == $group["flag"]) {
                $old_info = $val;
            }
            if ($key == $parameters["flag"]) {
                $new_info = $val;
            }
        }
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 4,
            "info" => sprintf("修改群类型：旧类型 %s，新类型 %s", $old_info, $new_info),
            "admin" => auth()->user(),
        ]);

        GroupService::changeFlag($group, $parameters["flag"]);

        return handle_response([], "success");
    }
    
    public function changeBusinessType()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "type")) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        GroupService::changeBusinessType($group, $parameters["type"]);

        return handle_response([], "success");
    }
    
    public function changeBusinessDetailType()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "type")) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        $arr = [
            1 => '待定（默认）',
            
            2 => '公群2交易群',
            
            100 => '卡接一道回u',
            101 => '码接回u',
            102 => '卡接二道回u',
            204 => '海外直通车',
            103 => '限额划扣',
            
            200 => '支付宝微信',
            201 => '现存承兑',
            // 202 => 'pos和备付金',
            203 => '多种资金',
            210 => '抖音快手等核销',
            211 => '实物回U',
            
            
            800 => '白资',
            801 => '二道保时',
            802 => '二道混料',
            803 => '一道放料',
            804 => '其他资金',
            805 => '二道保时',
            
            300 => '卡商中介',
            301 => '固话手机口',
            302 => 'app跑分',
            303 => '招车队',
            304 => '贴卡片',
            310 => '快递代发',
            305 => '租号',
            
            400 => '查档',
            401 => '飞机会员',
            402 => '票务酒店',
            403 => '设计美工',
            404 => '搭建开发',
            405 => '接码代实名',
            
            500 => '卖各种号',
            503 => '买卖手机卡',
            501 => '卖数据',
            502 => '能量trx',
            504 => '收粉引流',
            505 => '烟酒奢侈品',
            
            // 600 => '其他类',
            601 => '外围',
            603 => 'AI变脸',
            604 => '抖音代发',
            602 => '服务器等等',
            
            700 => 'vip小公群',
            701 => 'VIP代收类',
            702 => 'VIP承兑类',
            703 => 'VIP收u代付类',
            
            1000 => '资源群',

        ];
        $old_info = "";
        $new_info = "";
        foreach ($arr as $key => $val) {
            if ($key == $group["business_detail_type"]) {
                $old_info = $val;
            }
            if ($key == $parameters["type"]) {
                $new_info = $val;
            }
        }
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 5,
            "info" => sprintf("修改真公群类型：旧类型 %s，新类型 %s", $old_info, $new_info),
            "admin" => auth()->user(),
        ]);

        GroupService::changeBusinessDetailType($group, $parameters["type"]);

        return handle_response([], "success");
    }
    
    public function changeStatusMoney()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "flag")) {
            return handle_response([], "error");
        }
        if (!in_array($parameters["flag"], [1, 2, 3, 4])) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        $arr = [
            1 => '已上押',
            2 => '已退押',
            3 => '待定',
        ];
        $old_info = "";
        $new_info = "";
        foreach ($arr as $key => $val) {
            if ($key == $group["status_money"]) {
                $old_info = $val;
            }
            if ($key == $parameters["flag"]) {
                $new_info = $val;
            }
        }
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 7,
            "info" => sprintf("修改押金类型：旧类型 %s，新类型 %s", $old_info, $new_info),
            "admin" => auth()->user(),
        ]);

        GroupService::changeStatusMoney($group, $parameters["flag"]);

        return handle_response([], "success");
    }

    public function changeTradeType()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "type")) {
            return handle_response([], "error");
        }
        if (!in_array($parameters["type"], [1, 2, 3, 4, 9])) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        $arr = [
            1 => '待定',
            2 => '代收群',
            3 => '代付群',
            4 => '支付宝',
            9 => '全部',
        ];
        $old_info = "";
        $new_info = "";
        foreach ($arr as $key => $val) {
            if ($key == $group["trade_type"]) {
                $old_info = $val;
            }
            if ($key == $parameters["type"]) {
                $new_info = $val;
            }
        }
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 6,
            "info" => sprintf("修改群交易类型类型：旧类型 %s，新类型 %s", $old_info, $new_info),
            "admin" => auth()->user(),
        ]);

        GroupService::changeTradeType($group, $parameters["type"]);

        return handle_response([], "success");
    }

    public function changePeopleLimit()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "type")) {
            return handle_response([], "error");
        }
        if (!in_array($parameters["type"], [1, 2, 3])) {
            return handle_response([], "error");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        $arr = [
            1 => '进群自动禁言',
            2 => '关闭',
            3 => '进群验证',
        ];
        $old_info = "";
        $new_info = "";
        foreach ($arr as $key => $val) {
            if ($key == $group["people_limit"]) {
                $old_info = $val;
            }
            if ($key == $parameters["type"]) {
                $new_info = $val;
            }
        }
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 8,
            "info" => sprintf("修改入群限制：旧类型 %s，新类型 %s", $old_info, $new_info),
            "admin" => auth()->user(),
        ]);

        GroupService::changePeopleLimit($group, $parameters["type"]);

        return handle_response([], "success");
    }

    public function changeLimitOneTime()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "type")) {
            return handle_response([], "error1");
        }
        if ($parameters["type"] < 0) {
            return handle_response([], "必须为正整数");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 10,
            "info" => sprintf("修改欢迎语频率(秒)：旧频率 %s，新频率 %s", $group["limit_one_time"], $parameters["type"]),
            "admin" => auth()->user(),
        ]);

        GroupService::changeLimitOneTime($group, intval($parameters["type"]));

        return handle_response([], "success");
    }

    public function setTime()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "startTime")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "endTime")) {
            return handle_response([], "error");
        }

        $date = date("Y-m-d");
        $startTime = strtotime($date . " " . $parameters["startTime"]);
        $endTime = strtotime($date . " " . $parameters["endTime"]);
        if ($startTime >= $endTime) {
            return handle_response([], "开始营业时间不能大于结束营业时间");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        GroupService::setTime($group, $parameters["startTime"], $parameters["endTime"]);

        return handle_response([], "success");
    }

    public function change(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "key")) {
            return handle_response([], "key不能为空");
        }
        if (is_wrong_data($parameters, "val")) {
            return handle_response([], "val不能为空");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群组不存在");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 22,
            "info" => sprintf("修改群配置信息 %s %s", $parameters["key"], $parameters["val"]),
            "admin" => auth()->user(),
        ]);

        if ($parameters["key"] == "switch_group_status" && $parameters["val"] == 2) {
            setChatPermissions($group["chat_id"], true);
        }

        GroupService::setVal($group, $parameters["key"], $parameters["val"]);

        return handle_response([], "成功");
    }

    public function setOfficialAdmin(Request $request)
    {
        $parameters = $request->all();
        
        if (is_wrong_data($parameters, "chat_id") or true) {
            // return handle_response([], "暂时关闭");
        }
        if (is_wrong_data($parameters, "user_id")) {
            return handle_response([], "user_id不能为空");
        }
        
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 16,
            "info" => sprintf("新增官方管理 %s", $parameters["user_id"]),
            "admin" => auth()->user(),
        ]);
        
        $official = OfficialUserService::one([
            "tg_id" => $parameters["user_id"],
        ]);
        if (!$official) {
            return handle_response([], "超管必须是官方账号");
        }
        
        $result = setAdmin($parameters["chat_id"], $parameters["user_id"]);

        if (is_right_data($result, "ok") && $result["ok"]) {
            GroupService::flushAdmin([
                "chat_id" => $parameters["chat_id"],
            ]);
            
            cacheSetAdmin($parameters["chat_id"], $parameters["user_id"]);

            return handle_response([], "设置超管成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "设置超管失败");
            }
        }
    }

    public function kickOneUser(Request $request)
    {
        $parameters = $request->all();
        
        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "user_id")) {
            return handle_response([], "user_id不能为空");
        }
        
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 17,
            "info" => sprintf("踢出指定用户 %s", $parameters["user_id"]),
            "admin" => auth()->user(),
        ]);
        
        $result = banChatMember($parameters["chat_id"], $parameters["user_id"]);
        
        if ($result && is_right_data($result, "ok") && $result["ok"]) {
            return handle_response([], "踢出成功");
        } else {
            return handle_response([], "踢出失败");
        }
    }

    public function kickOneUserBack(Request $request)
    {
        $parameters = $request->all();
        
        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "user_id")) {
            return handle_response([], "user_id不能为空");
        }
        
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 17,
            "info" => sprintf("踢出指定用户 %s", $parameters["user_id"]),
            "admin" => auth()->user(),
        ]);
        
        $result = banChatMemberBack($parameters["chat_id"], $parameters["user_id"]);
        
        if ($result && is_right_data($result, "ok") && $result["ok"]) {
            return handle_response([], "踢出成功");
        } else {
            return handle_response([], "踢出失败");
        }
    }

    public function setAdmin(Request $request)
    {
        $parameters = $request->all();
        
        if (is_wrong_data($parameters, "chat_id") or true) {
            // return handle_response([], "暂时关闭");
        }
        if (is_wrong_data($parameters, "user_id")) {
            return handle_response([], "user_id不能为空");
        }
        
        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群不存在");
        }
        
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 14,
            "info" => sprintf("新增管理 %s", $parameters["user_id"]),
            "admin" => auth()->user(),
        ]);
        
        if ($group["flag"] != 2 and $group["flag"] != 4) {
            return handle_response([], "只能为官方群和游戏群添加管理，请先标记真群");
        }
        
        // $can_manage_chat = (is_right_data($parameters, "can_manage_chat") && $parameters["can_manage_chat"] == 1) ? true : false;
        $can_manage_chat = false;

        $can_delete_messages = (is_right_data($parameters, "can_delete_messages") && $parameters["can_delete_messages"] == 1) ? true : false;
        
        $can_manage_voice_chats = (is_right_data($parameters, "can_manage_voice_chats") && $parameters["can_manage_voice_chats"] == 1) ? true : false;

        $can_restrict_members = (is_right_data($parameters, "can_restrict_members") && $parameters["can_restrict_members"] == 1) ? true : false;

        $can_promote_members = (is_right_data($parameters, "can_promote_members") && $parameters["can_promote_members"] == 1) ? true : false;
        
        $can_change_info = (is_right_data($parameters, "can_change_info") && $parameters["can_change_info"] == 1) ? true : false;
        
        $can_invite_users = (is_right_data($parameters, "can_invite_users") && $parameters["can_invite_users"] == 1) ? true : false;
        
        $can_pin_messages = (is_right_data($parameters, "can_pin_messages") && $parameters["can_pin_messages"] == 1) ? true : false;
        
        $result = setAdminSpecial($parameters["chat_id"], $parameters["user_id"], compact("can_manage_chat",
            "can_delete_messages", "can_manage_voice_chats", "can_restrict_members", "can_promote_members", 
            "can_change_info", "can_invite_users", "can_pin_messages"
        ));

        if (is_right_data($result, "ok") && $result["ok"]) {
            GroupService::flushAdmin([
                "chat_id" => $parameters["chat_id"],
            ]);
            
            cacheSetAdmin($parameters["chat_id"], $parameters["user_id"]);

            return handle_response([], "设置管理成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "设置管理失败");
            }
        }
    }

    public function flushAdmin()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "缺少参数");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群不存在");
        }
        
        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 15,
            "info" => "刷新管理",
            "admin" => auth()->user(),
        ]);
        
        if ($group["flag"] != 2 and $group["flag"] != 4) {
            return handle_response([], "只能为官方群和游戏群添加管理，请先标记真群");
        }
        
        GroupService::flushAdmin($group);

        return handle_response([], "success");
    }

    public function flushBasic()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "缺少参数");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "群不存在");
        }
        
        RedisService::setFlushBot($parameters["chat_id"]);
        
        $result = getChat($parameters["chat_id"]);
        if ($result && is_right_data($result, "ok") && $result["ok"]) {
            if (is_right_data($result, "result")) {
                $info = $result["result"];

                if (is_right_data($info, "title")) {
                    $group->title = $info["title"];
                }
                
                if (is_right_data($info, "description")) {
                    $group->description = $info["description"];
                }

                if (is_right_data($info, "invite_link")) {
                    $group->url = $info["invite_link"];
                }
                
                if (is_right_data($info, "has_hidden_members") and $info["has_hidden_members"]) {
                    $group->has_hidden_members = 1;
                } else {
                    $group->has_hidden_members = 2;
                }
                
                $group->status_in = 1;

                $group->save();
            }
        } else {
            if ($group->status_in == 1) {
            
                $group->status_in = 2;
                $group->out_at = date("Y-m-d H:i:s");
                $group->save();
               
            }
        }
        
        return handle_response([], "更新成功");
    }



    public function removeAdminOld(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "user_id")) {
            return handle_response([], "user_id不能为空");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 18,
            "info" => sprintf("移除管理 %s", $parameters["user_id"]),
            "admin" => auth()->user(),
        ]);

        $result = removeAdminOld($parameters["chat_id"], $parameters["user_id"]);
        

        if (is_right_data($result, "ok") && $result["ok"]) {
            GroupService::flushAdmin([
                "chat_id" => $parameters["chat_id"],
            ]);
            
            cacheRemoveAdmin($parameters["chat_id"], $parameters["user_id"]);
            
            flushAdmin($parameters["chat_id"], $parameters["user_id"]);

            return handle_response([], "移除管理成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "移除管理失败");
            }
        }
    }

    public function removeAdmin(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "user_id")) {
            return handle_response([], "user_id不能为空");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 18,
            "info" => sprintf("移除管理 %s", $parameters["user_id"]),
            "admin" => auth()->user(),
        ]);

        $result = removeAdmin($parameters["chat_id"], $parameters["user_id"]);

        if (is_right_data($result, "ok") && $result["ok"]) {
            GroupService::flushAdmin([
                "chat_id" => $parameters["chat_id"],
            ]);
            
            cacheRemoveAdmin($parameters["chat_id"], $parameters["user_id"]);
            
            flushAdmin($parameters["chat_id"], $parameters["user_id"]);

            return handle_response([], "移除管理成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "移除管理失败");
            }
        }
    }

    public function baobeiLeave(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        
        $chat_id = $parameters["chat_id"];
        
        $result = leaveChatBaobei($chat_id);
        
        if (is_right_data($result, "ok") && $result["ok"]) {
            return handle_response([], "退群成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "退群失败");
            }
        }
    }

    public function leave(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 11,
            "info" => "机器人退群",
            "admin" => auth()->user(),
        ]);

        $result = leaveChat($parameters["chat_id"]);

        if (is_right_data($result, "ok") && $result["ok"]) {
            $group = GroupService::get([
                "chat_id" => $parameters["chat_id"],
                "is_one_obj" => true,
            ]);
            if ($group) {
                $group->status_in = 2;
                $group->save();
            }

            return handle_response([], "退群成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "退群失败");
            }
        }
    }

    public function setUserTitle(Request $request)
    {
        $parameters = $request->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "chat_id不能为空");
        }
        if (is_wrong_data($parameters, "user_id")) {
            return handle_response([], "user_id不能为空");
        }
        if (is_wrong_data($parameters, "custom_title")) {
            return handle_response([], "头衔不能为空");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 19,
            "info" => sprintf("设置头衔 %s %s", $parameters["user_id"], $parameters["custom_title"]),
            "admin" => auth()->user(),
        ]);

        $groupAdmin = GroupAdminService::get([
            "chat_id" => $parameters["chat_id"],
            "user_id" => $parameters["user_id"],
            "is_one_obj" => true,
        ]);
        if (!$groupAdmin) {
            return handle_response([], "管理员不存在");
        }

        $result = setChatAdministratorCustomTitle($parameters["chat_id"], $parameters["user_id"], $parameters["custom_title"]);

        if (is_right_data($result, "ok") && $result["ok"]) {
            GroupAdminService::setUserTitle($groupAdmin, $parameters["custom_title"]);

            return handle_response([], "头衔设置成功");
        } else {
            if (is_right_data($result, "description")) {
                return handle_response([], $result["description"]);
            } else {
                return handle_response([], "头衔设置失败");
            }
        }
    }

    public function changeStatusApprove()
    {
        $parameters = request()->all();
        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "key")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "val")) {
            return handle_response([], "error");
        }

        $key = $parameters["key"];
        $val = $parameters["val"];

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 25,
            "info" => sprintf("审核进群状态修改 %s %s", $key, $val),
            "admin" => auth()->user(),
        ]);

        GroupService::setApproveVal($group, $key, $val);
        
        flushGroup($parameters["chat_id"]);

        return handle_response([], "success");
    }

    public function approve(Request $request)
    {
        $result = LogApproveService::get([
            "chat_id" => $request->get("chat_id"),
            "is_arr" => true,
        ]);

        return array(
            "draw" => $request->get("draw"),
            'recordsTotal' => count($result),
            "recordsFiltered" => count($result),
            'data' => $result,
        );
    }

    public function link(Request $request)
    {
        $result = GroupLinkService::get([
            "chat_id" => $request->get("chat_id"),
            "is_arr" => true,
        ]);

        return array(
            "draw" => $request->get("draw"),
            'recordsTotal' => count($result),
            "recordsFiltered" => count($result),
            'data' => $result,
        );
    }

    public function linkDelete()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        
        if (is_wrong_data($parameters, "data_id")) {
            return handle_response([], "error");
        }
        
        $obj = GroupLinkService::get([
            "id" => $parameters["data_id"],
            "is_one_obj" => true,
        ]);
        if (!$obj) {
            return handle_response([], "群链接不存在");
        }
        
        $result = revokeChatInviteLink($parameters["chat_id"], $obj["link"]);
        
        $flag = false;
        
        if ($result and is_right_data($result, "ok")) {
            if ($result["ok"]) {
                $flag = true;
            } else {
                if (is_right_data($result, "description")) {
                    $description = $result["description"];
                    if ($description == "Bad Request: INVITE_HASH_EXPIRED") {
                        $flag = true;
                    }
                }
            }
        }
        
        if ($flag) {
            $obj->status = 2;
            $obj->save();
            
            return handle_response([], "注销成功");
        }
        
        return handle_response([], "请重试");
    }

    public function linkAdd()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        
        if (is_wrong_data($parameters, "type")) {
            return handle_response([], "error");
        }
        
        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }
        
        $chat_id = $parameters["chat_id"];
        
        $result = createBotApproveLinkType($chat_id, $parameters["type"]);
        if ($result and is_right_data($result, "ok") and $result["ok"]) {
            $resultResult = $result["result"];
            $creator = $resultResult["creator"];
            
            $creator_tg_id = "";
            $creator_fullname = "";
            if (is_right_data($creator, "first_name")) {
                $creator_fullname .= $creator["first_name"];
            }
            if (is_right_data($creator, "last_name")) {
                $creator_fullname .= $creator["last_name"];
            }
            if (is_right_data($creator, "id")) {
                $creator_tg_id .= $creator["id"];
            }
            
            $link = $resultResult["invite_link"];
            GroupLinkService::create([
                "group_tg_id" => $chat_id,
                "link" => $link,
                "type" => $parameters["type"],
                "title" => $group["title"],
                "creator_tg_id" => $creator_tg_id,
                "creator_fullname" => $creator_fullname,
            ]);
            
            return handle_response([], "链接生成成功");
        }
        
        return handle_response([], "请重试");
    }

    public function rejectAllApprove()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }

        $chat_id = $parameters["chat_id"];

        $approves = LogApproveService::get([
            "chat_id" => $chat_id,
            "status" => 2,
        ]);

        foreach ($approves as $approve) {
            RejectApprove::dispatch($approve);
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 26,
            "info" => "拒绝全部审核",
            "admin" => auth()->user(),
        ]);

        return handle_response([], "success");
    }
    
    public function approves(Request $request)
    {
        $parameters = request()->all();
        
        if (is_wrong_data($parameters, "group_tg_id")) {
            return handle_response([], "group_tg_id不能为空");
        }
        
        $page = 1;
        $page_len = 10;
        if (is_right_data($parameters, "page")) {
            $page = $parameters["page"];
        }
        if (is_right_data($parameters, "page_len")) {
            $page_len = $parameters["page_len"];
        }

        $sort = 1;
        if (is_right_data($parameters, "sort")) {
            $sort = $parameters["sort"];
        }
        
        $fullname = "";
        if (is_right_data($parameters, "fullname")) {
            $fullname = $parameters["fullname"];
        }
        
        $result = LogApproveService::search([
            "group_tg_id" => $parameters["group_tg_id"],
            "sort" => $sort,
            "page" => $page,
            "page_len" => $page_len,
            "fullname" => $fullname,
        ]);
        
        return handle_response($result, "success");
    }
    
    public function approveDeclineAndKick()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "group_tg_id")) {
            return handle_response([], "group_tg_id不能为空");
        }
        
        if (is_wrong_data($parameters, "users")) {
            return handle_response([], "users不能为空");
        }
        
        $group_tg_id = $parameters["group_tg_id"];
        $users = $parameters["users"];
        
        $arr_kick = [];
        $arr_decline = [];
        foreach ($users as $user) {
            $user_tg_id = $user["user_tg_id"];
            $status = $user["status"];
            
            if (!in_array($user_tg_id, $arr_kick)) {
                array_push($arr_kick, $user_tg_id);
            }
            
            if ($status == 2) {
                if (!in_array($user_tg_id, $arr_decline)) {
                    array_push($arr_decline, $user_tg_id);
                }
            }
        }
        
        $admin = auth()->user();
        
        foreach ($arr_kick as $user_tg_id) {
            // AssistService::saveRedisData4TG([
            //     "type_ops" => "ban",
            //     "group_tg_id" => $group_tg_id,
            //     "user_tg_id" => $user_tg_id,
            //     "admin_id" => $admin["id"],
            // ]);
            AssistService::saveRedisData4handleUser([
                "type" => "kick",
                "all" => 1,
                "search_type" => 5,
                "search_text" => $user_tg_id,
                "except_game" => 1,
                "admin_id" => $admin["id"],
            ]);
        }
        
        foreach ($arr_decline as $user_tg_id) {
            $approve = LogApproveService::get([
                "chat_id" => $group_tg_id,
                "user_tg_id" => $user_tg_id,
                "status" => 2,
                "is_one_obj" => true,
            ]);
            if ($approve) {
                RejectApprove::dispatch($approve);
            }
        }
        
        return handle_response([], "操作中，请等待...");
    }
    
    public function approveDeclineAndKickAndCheat()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "group_tg_id")) {
            return handle_response([], "group_tg_id不能为空");
        }
        
        if (is_wrong_data($parameters, "users")) {
            return handle_response([], "users不能为空");
        }
        
        $reason = "";
        if (is_right_data($parameters, "reason")) {
            $reason = $parameters["reason"];
        }
        
        $group_tg_id = $parameters["group_tg_id"];
        $users = $parameters["users"];
        
        $arr_kickAndCheat = [];
        $arr_decline = [];
        foreach ($users as $user) {
            $user_tg_id = $user["user_tg_id"];
            $status = $user["status"];
            
            if (!in_array($user_tg_id, $arr_kickAndCheat)) {
                array_push($arr_kickAndCheat, $user_tg_id);
            }
            
            if ($status == 2) {
                if (!in_array($user_tg_id, $arr_decline)) {
                    array_push($arr_decline, $user_tg_id);
                }
            }
        }
        
        $admin = auth()->user();
        
        foreach ($arr_kickAndCheat as $user_tg_id) {
            // AssistService::saveRedisData4TG([
            //     "type_ops" => "ban",
            //     "group_tg_id" => $group_tg_id,
            //     "user_tg_id" => $user_tg_id,
            //     "admin_id" => $admin["id"],
            // ]);
            
            AssistService::saveRedisData4handleUser([
                "type" => "kick",
                "all" => 1,
                "search_type" => 5,
                "search_text" => $user_tg_id,
                "except_game" => 1,
                "admin_id" => $admin["id"],
            ]);
            
            CheatService::create($user_tg_id, $admin["id"], $reason);
        }
        
        foreach ($arr_decline as $user_tg_id) {
            $approve = LogApproveService::get([
                "chat_id" => $group_tg_id,
                "user_tg_id" => $user_tg_id,
                "status" => 2,
                "is_one_obj" => true,
            ]);
            if ($approve) {
                RejectApprove::dispatch($approve);
            }
        }
        
        return handle_response([], "操作中，请等待...");
    }
    
    public function clearAllapprove()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "group_tg_id")) {
            return handle_response([], "group_tg_id不能为空");
        }

        $group_tg_id = $parameters["group_tg_id"];

        $approves = LogApproveService::get([
            "chat_id" => $group_tg_id,
            "status" => 2,
        ]);
        
        if (count($approves) == 0) {
            return handle_response([], "无可操作数据");
        }
        
        $admin = auth()->user();
        
        $data = [];
        foreach ($approves as $approve) {
            array_push($data, [
                "data_id" => $approve["id"],
                "group_tg_id" => $group_tg_id,
                "user_tg_id" => $approve["user_tg_id"],
            ]);
        }
        
        RedisService::setMsg([
            "opeType" => "approve",
            "type" => "reject",
            "all" => 2,
            "search_type" => is_right_data($parameters, "search_type") ? $parameters["search_type"] : -1,
            "search_text" => "",
            "except_game" => is_right_data($parameters, "except_game") ? $parameters["except_game"] : 2,
            "admin_id" => $admin["id"],
            "data" => $data,
            "reason" => "",
        ]);

        return handle_response([], "操作中，请等待...");
    }
    
    public function changeTradeRate()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "val")) {
            return handle_response([], "error1");
        }
        if ($parameters["val"] <= 0) {
            return handle_response([], "必须为大于0小于等于1的数字");
        }
        if ($parameters["val"] > 1) {
            return handle_response([], "必须为大于0小于等于1的数字");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }

        LogGroupService::save([
            "group_tg_id" => $parameters["chat_id"],
            "type" => 10,
            "info" => sprintf("修改报备报警比例(秒)：旧比例 %s，新比例 %s", $group["trade_rate"], $parameters["val"]),
            "admin" => auth()->user(),
        ]);

        GroupService::changeTradeRate($group, $parameters["val"]);

        return handle_response([], "success");
    }
    
    public function changeSendUserChange()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "val")) {
            return handle_response([], "error1");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }
    
        $group->send_user_change = $parameters["val"];
        $group->save();

        return handle_response([], "success");
    }
    
    public function changeStatusFollow()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "val")) {
            return handle_response([], "error1");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }
    
        $group->status_follow = $parameters["val"];
        $group->save();

        return handle_response([], "success");
    }
    
    public function changeStatusXianjing()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "chat_id")) {
            return handle_response([], "error");
        }
        if (is_wrong_data($parameters, "val")) {
            return handle_response([], "error1");
        }

        $group = GroupService::get([
            "chat_id" => $parameters["chat_id"],
            "is_one_obj" => true,
        ]);
        if (!$group) {
            return handle_response([], "error");
        }
    
        $group->xianjing_status = $parameters["val"];
        $group->save();

        return handle_response([], "success");
    }
}
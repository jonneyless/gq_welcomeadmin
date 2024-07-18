<?php

namespace App\Admin\Controllers;

use App\Models\AdminUser;
use App\Models\CheatSpecial;
use App\Service\LogOperationService;
use App\Service\OfficialUserService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use App\DataModels\CheatSpecial as DataCheat;
use Encore\Admin\Layout\Row;
use Encore\Admin\Layout\Column;
use App\Service\CheatSpecialService;
use App\Service\GroupAdminService;


class CheatSpecialController extends AdminController
{
    protected $title = '担保骗子库';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new CheatSpecial());
        $grid->model()
            ->groupBy("tgid")
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('tgid', "tgid");
                $filter->equal('username', "用户名");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('tgid', __('tgid'));
        $grid->column('username', __('用户名'));
        $grid->column('firstname', __('firstname'));
        $grid->column('lastname', __('lastname'));
        $grid->column('reason', __('原因'));
        $grid->column('admin_id', __('操作人'))->display(function ($admin_id) {
            if ($this->official_id != "-1") {
                $offical_user = OfficialUserService::one([
                    "tg_id" => $this->official_id,
                ]);
                if ($offical_user) {
                    return "@" . $offical_user["username"];
                }
            }

            if ($admin_id == -1) {
                return "自动添加";
            }

            $admin = AdminUser::query()->where("id", $admin_id)->first();
            if ($admin) {
                return $admin["name"];
            } else {
                return "";
            }
        });
        // $grid->column('remark', __('文本'));
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

        $grid->paginate(100);

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new CheatSpecial());

        $form->text('tgid', __('tgid'))->rules('required|unique:cheats_special', [
            'required' => 'tgid不能为空',
            'unique' => 'tgid已存在',
        ]);
        $form->text('username', __('用户名'));
        $form->text('firstname', __('firstname'));
        $form->text('lastname', __('lastname'));
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
        
$form->deleting(function () {

    dd("deleting");
});

$form->deleted(function () {

    dd("deleted");
});

        return $form;
    }

    public function add(Content $content)
    {
        return $content
            ->title("担保骗子库")
            ->description('创建')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(DataCheat::add());
                });
            });
    }

    public function save()
    {
        $parameters = request()->all();
        
        if (is_wrong_data($parameters, "tgid")) {
            return handle_response([], "tg_id 不能为空");
        }
        $tgid = $parameters["tgid"];
        
        $username = "";
        if (is_right_data($parameters, "username")) {
            $username = $parameters["username"];
        }
        $firstname = "";
        if (is_right_data($parameters, "firstname")) {
            $firstname = $parameters["firstname"];
        }
        $lastname = "";
        if (is_right_data($parameters, "lastname")) {
            $lastname = $parameters["lastname"];
        }
        $reason = "";
        if (is_right_data($parameters, "reason")) {
            $reason = $parameters["reason"];
        }
        $flag = 1;
        if (is_right_data($parameters, "flag")) {
            $flag = $parameters["flag"];
        }
        
        $cheat = CheatSpecialService::one($tgid);
        if ($cheat) {
            return handle_response([], "该用户已经在骗子库中");
        }
        
        if ($flag == 1) {
            $admin = GroupAdminService::one($tgid);
            if ($admin) {
                return handle_response([], "admin");
            }
        }

        $admin = auth()->user();
        
        CheatSpecialService::create($tgid, $admin["id"], $reason);
        
        return handle_response([], "加入成功");
    }

    public function store()
    {
        $admin = auth()->user();

        return $this->form()->store([
            "admin_id" => $admin["id"],
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }
}
<?php


namespace App\Admin\Controllers;

use App\DataModels\Bullhorn;
use App\Http\Controllers\Controller;
use App\Jobs\SendBullhorn;
use App\Models\LogSend;
use App\Service\GroupService;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class BullhornController extends Controller
{
    protected $title = '广播';

    public function index(Content $content)
    {
        Admin::disablePjax();
        return $content
            ->title($this->title)
            ->description(' ')
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append(Bullhorn::index());
                });
            })
            ->row(function (Row $row) {
                $row->column(12, function (Column $column) {
                    $column->append($this->grid());
                });
            });
    }

    protected function grid()
    {
        $grid = new Grid(new LogSend());
        $grid->model()
            ->orderBy('created_at', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('tgid', "tgid");
                $filter->equal('username', "用户名");
            });
        });

        $grid->column('id', __('id'));
        $grid->column('title', __('群名'));
        $grid->column('info', __('信息'))->limit(20);
        $grid->column('status', __('状态'))->display(function ($status) {
            if ($status == 1) {
                return "<span class='label label-default'>操作中</span>";
            } elseif ($status == 2) {
                return "<span class='label label-success'>成功</span>";
            } elseif ($status == 3) {
                return "<span class='label label-danger'>失败</span>";
            }
        })->filter([
            1 => '操作中',
            2 => '成功',
            3 => '失败',
        ]);
        $grid->column('created_at', __('创建时间'))->sortable();

        $grid->disableActions();

        $grid->paginate(10);

        return $grid;
    }

    public function add()
    {
        $parameters = request()->all();

        if (is_wrong_data($parameters, "info")) {
            return handle_response([], "error");
        }

        $groups = GroupService::get();
        foreach ($groups as $group) {
            SendBullhorn::dispatch($group["chat_id"], $parameters["info"], $group);
        }

        return handle_response([], "success");
    }
}
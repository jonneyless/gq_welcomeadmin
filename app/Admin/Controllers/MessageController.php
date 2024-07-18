<?php


namespace App\Admin\Controllers;

use App\Models\Msg;
use App\Service\UserService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Grid;

class MessageController extends AdminController
{
    protected $title = 'tg信息';
    protected $description = [
        'index' => " ",
        "show" => " ",
    ];

    public function grid()
    {
        $grid = new Grid(new Msg());
        $grid->model()
            ->orderBy('id', 'desc');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();

            $filter->column(1 / 2, function ($filter) {
                $filter->equal('chat_id', "群tgid");
                $filter->equal('user_id', "用户tgid");
                $filter->equal('message_id', "信息tgid");
                $filter->like('info', "信息");
            });
        });

        $grid->column('chat_id', __('群tgid'));
        $grid->column('message_id', __('信息tgid'));
        $grid->column('user_id', __('发送人'))->display(function ($user_id) {
            $user = UserService::one([
                "tg_id" => $user_id,
            ]);
            $text = "";
            if ($user) {
                return $user["fullname"];
            }
            return $text;
        });
        $grid->column('info', __('信息'))->limit(20);
        $grid->column('created_at', __('发送时间'));

        $grid->disableCreateButton();
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions();

        $grid->paginate(10);

        return $grid;
    }
}
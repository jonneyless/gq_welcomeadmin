<?php

namespace App\Admin\Controllers;

use App\Models\CheatCoin;
use App\Models\KeywordReply;
use App\Service\LogOperationService;
use App\Service\RedisService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Facades\Log;

class KeywordReplyController extends AdminController
{
    protected $title = '关键词回复';
    protected $description = [
        'index' => ' '
    ];

    protected function grid()
    {
        $grid = new Grid(new KeywordReply());
        $grid->model();

        $grid->column('id', __('id'));
        $grid->column('keyword', __('关键字'));

        $grid->disableCreateButton(false);
        $grid->disableFilter(false);
        $grid->disableTools(false);
        $grid->disableActions(false);
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView(true);
            $actions->disableEdit(false);
            $actions->disableDelete(false);
        });

        $grid->paginate(10);

        return $grid;
    }

    protected function form()
    {
        $form = new Form(new KeywordReply());

        $form->checkbox('type', __('类型'))->options([1 => '专群', 2 => '公群'])->rules('required');
        $form->checkbox('sender_type', __('发送人类型'))->options([1 => '官方人员', 2 => '群管理', 3 => '用户'])->rules('required');
        $form->text('keyword', __('关键字'))->rules('required', [
            'required' => '关键字不能为空',
        ]);

        $form->file('file', __('媒体内容'))->uniqueName();

        $form->table('replies', __('文字内容'), function ($table) {
            $table->text('text', __('文字'));
        });

        $form->table('buttons', __('按钮组'), function ($table) {
            $table->text('text', __('文字'));
            $table->text('url', __('链接'));
        });

        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableCreatingCheck();
            $footer->disableEditingCheck();
        });

        $form->saved(function (Form $form) {
            $file = public_path('uploads') . '/' . $form->model()->file;

            $fileType = 0;
            $mime = mime_content_type($file);
            if (substr($mime, 0, 5) == 'image') {
                $fileType = 1;
            } else if (substr($mime, 0, 5) == 'video') {
                $fileType = 2;
            }

            $form->model()->file_mime = $mime;
            $form->model()->file_type = $fileType;
            $form->model()->save();

            RedisService::delKeywordReplies();
        });

        return $form;
    }
}
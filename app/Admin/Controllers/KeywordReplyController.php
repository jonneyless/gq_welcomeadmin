<?php

namespace App\Admin\Controllers;

use App\Models\KeywordReply;
use App\Service\RedisService;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

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

        $form->table('replies', __('消息'), function ($table) {
            $table->file('file', __('媒体'))->uniqueName();
            $table->text('text', __('文字'));
            $table->text('buttonText', __('按钮文字'));
            $table->text('buttonUrl', __('按钮链接'));
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
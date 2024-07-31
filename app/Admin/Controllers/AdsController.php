<?php

namespace App\Admin\Controllers;

use App\Models\Ads;
use App\Models\AdsBidding;
use App\Models\AdsKeywords;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class AdsController extends AdminController
{
    protected $title = '搜索广告';

    protected $description = [
        'index' => ' ',
    ];

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $request = \request();

        $data = $request->all();

        $data['keywords'] = array_filter($data['keywords']);

        $ads = Ads::query()->where('id', $id)->first();
        $ads->custom_tg_id = $data['custom_tg_id'];
        $ads->position = $data['position'];
        $ads->name = $data['name'];
        $ads->url = $data['url'];
        $ads->begin_at = strtotime($data['begin_at']);
        $ads->end_at = strtotime($data['end_at']);
        $ads->keywords = join(',', $data['keywords']);
        $ads->updated_at = time();
        $ads->save();

        AdsBidding::query()->where('ads_id', '=', $id)->delete();

        foreach ($data['keywords'] as $word) {
            $keyword = AdsKeywords::query()->where('name', $word)->first();
            if (!$keyword) {
                $keyword = new AdsKeywords();
                $keyword->name = $word;
                $keyword->created_at = time();
                $keyword->updated_at = time();
                $keyword->save();
            }

            $bidding = new AdsBidding();
            $bidding->ads_id = $ads->id;
            $bidding->keyword_id = $keyword->id;
            $bidding->trigger_count = 0;
            $bidding->begin_at = strtotime($data['begin_at']);
            $bidding->end_at = strtotime($data['end_at']);
            $bidding->created_at = time();
            $bidding->updated_at = time();
            $bidding->save();
        }

        if ($request->ajax() && !$request->pjax()) {
            return response()->json([
                'status' => true,
                'message' => trans('admin.update_succeeded'),
                'display' => $ads->toArray(),
            ]);
        }

        admin_toastr(trans('admin.update_succeeded'));

        return redirect(config('admin.route.prefix') . '/ads');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        $request = \request();

        $data = $request->all();

        $ads = new Ads();
        $ads->admin_id = auth()->id();
        $ads->custom_tg_id = $data['custom_tg_id'];
        $ads->position = $data['position'];
        $ads->name = $data['name'];
        $ads->url = $data['url'];
        $ads->begin_at = strtotime($data['begin_at']);
        $ads->end_at = strtotime($data['end_at']);
        $ads->keywords = join(',', $data['keywords']);
        $ads->created_at = time();
        $ads->updated_at = time();
        $ads->save();

        foreach ($data['keywords'] as $word) {
            $keyword = AdsKeywords::query()->where('name', $word)->first();
            if (!$keyword) {
                $keyword = new AdsKeywords();
                $keyword->name = $word;
                $keyword->created_at = time();
                $keyword->updated_at = time();
                $keyword->save();
            }

            $bidding = new AdsBidding();
            $bidding->ads_id = $ads->id;
            $bidding->keyword_id = $keyword->id;
            $bidding->trigger_count = 0;
            $bidding->begin_at = strtotime($data['begin_at']);
            $bidding->end_at = strtotime($data['end_at']);
            $bidding->created_at = time();
            $bidding->updated_at = time();
            $bidding->save();
        }

        if ($request->ajax() && !$request->pjax()) {
            return response()->json([
                'status' => true,
                'message' => trans('admin.save_succeeded'),
                'display' => $ads->toArray(),
            ]);
        }

        admin_toastr(trans('admin.save_succeeded'));

        return redirect(config('admin.route.prefix') . '/ads');
    }

    protected function grid()
    {
        $grid = new Grid(new Ads());

        $grid->column('id', 'ID');
        $grid->column('custom_tg_id', '客户飞机号');
        $grid->column('position', '广告位置');
        $grid->column('name', '广告文字');
        $grid->column('url', '广告跳转地址');
        $grid->column('begin_at', '开始时间');
        $grid->column('end_at', '结束时间');

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
        $form = new Form(new Ads());

        $form->text('custom_tg_id', '客户飞机号');
        $form->select('position', '广告位置')->options([1 => '顶部广告位', 2 => '底部推荐位']);
        $form->text('name', '广告文字');
        $form->text('url', '广告跳转地址');
        $form->datetimeRange('begin_at', 'end_at', '生效时间');
        $form->tags('keywords', '关键词');

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
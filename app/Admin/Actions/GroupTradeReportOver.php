<?php

namespace App\Admin\Actions;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use App\Service\CheatSpecialService;


class GroupTradeReportOver extends BatchAction
{
    public $name = '官方完成';

    public function handle(Collection $collection)
    {
        foreach ($collection as $model) {
            $model->status = 12;
            $model->save();
        }

        return $this->response()->success('操作成功')->refresh();
    }

    public function dialog()
    {
        $this->confirm('确定？');
    }
}
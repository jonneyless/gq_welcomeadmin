<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\TousuTypeLeixing;


class LogDanbaoYuefeiExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'danbao.xlsx';

    protected $columns = [];
    
    public function map($obj): array
    {
        // dd($obj);
        
        return [
            $this->get_type($obj->type),
            $obj->title,
            $obj->uid,
            $obj->money,
            $this->get_currency($obj->currency),
            $obj->month,
            $obj->created_at_jinbian,
        ];
    }
    
    public function get_type($type)
    {
        if ($type == 1) {
            return "上押扣除";
        } elseif ($type == 2) {
            return "下押扣除";
        } elseif ($type == 3) {
            return "日常缴纳";
        }
    }
    
    public function get_currency($currency)
    {
        if ($currency == 1) {
            return "usdt";
        } elseif ($currency == 2) {
            return "美金";
        } elseif ($currency == 3) {
            return "汇旺u";
        }
    }
}
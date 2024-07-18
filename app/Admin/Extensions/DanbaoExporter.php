<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\ExcelExporter;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Service\GroupBusinessService;


class DanbaoExporter extends ExcelExporter implements WithMapping
{
    protected $fileName = 'danbao.xlsx';

    protected $columns = [];
    
    public function map($obj): array
    {
        // dd($obj);
        
        return [
            $obj->title,
            $obj->num,
            $obj->info_creator,
            $obj->info_jiaoyiyuan,
            $obj->info_boss,
            $obj->info_yewuyuan,
            $this->get_business_detail_type($obj->business_detail_type),
            $obj->yuefei,
            $obj->yuefei_day,
            $obj->remark,
            $this->get_tuoguan($obj->tuoguan),
            $this->get_status($obj->status),
            $obj->created_at,
            $obj->ended_at,
        ];
    }
    
    public function get_business_detail_type($business_detail_type)
    {
        return GroupBusinessService::one_id($business_detail_type);
    }
    
    public function get_tuoguan($tuoguan)
    {
        if ($tuoguan == 1) {
            return "托管";
        } else {
            return "否";
        }
    }
    
    public function get_status($status)
    {
        if ($status == 1) {
            return "开启";
        } else {
            return "关闭";
        }
    }
}
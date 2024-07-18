<?php

namespace App\Service;

use App\Models\GroupBusiness;

class GroupBusinessService
{
    public static function one_id($id)
    {
        $obj = GroupBusiness::query()
            ->where("id", $id)
            ->first();
            
        return $obj ? $obj["name"] : "待定（默认）";
    }
    
    public static function one_p($p_id)
    {
        $obj = GroupBusiness::query()
            ->where("id", $p_id)
            ->first();
            
        return $obj ? $obj["name"] : "无";
    }
    
    public static function get_p()
    {
        $objs = GroupBusiness::query()
            ->where("p_id", "-1")
            ->get();
        
        $arr = [];
        
        foreach ($objs as $obj) {
            $arr[$obj["id"]] = $obj["name"];
        }
        
        return $arr;
    }
    
    public static function one($business_detail_type)
    {
        // return static::one($business_detail_type);   
        
        if ($business_detail_type == 100) {
            return "卡接一道回u";
        } elseif ($business_detail_type == 101) {
            return "码接回u";
        } elseif ($business_detail_type == 102) {
            return "卡接二道回u";
        } elseif ($business_detail_type == 204) {
            return "海外直通车";
        } elseif ($business_detail_type == 103) {
            return "限额划扣";
        } 
        
        elseif ($business_detail_type == 200) {
            return "支付宝微信";
        } elseif ($business_detail_type == 201) {
            return "现存承兑";
        } 
        
        // elseif ($business_detail_type == 202) {
        //     return "pos和备付金";
        // } 
        
        elseif ($business_detail_type == 203) {
            return "多种资金";
        } elseif ($business_detail_type == 210) {
            return "抖音快手等核销";
        } elseif ($business_detail_type == 211) {
            return "实物回U";
        } 
        
        elseif ($business_detail_type == 800) {
            return "白资";
        } 
        elseif ($business_detail_type == 805) {
            return "二道保时";
        } 
        
        elseif ($business_detail_type == 802) {
            return "二道混料";
        } elseif ($business_detail_type == 803) {
            return "一道放料";
        } elseif ($business_detail_type == 804) {
            return "其他资金";
        } 
        elseif ($business_detail_type == 300) {
            return "卡商中介";
        } elseif ($business_detail_type == 301) {
            return "固话手机口";
        } elseif ($business_detail_type == 302) {
            return "app跑分";
        } elseif ($business_detail_type == 303) {
            return "招车队";
        } elseif ($business_detail_type == 304) {
            return "贴卡片";
        } elseif ($business_detail_type == 310) {
            return "快递代发";
        } elseif ($business_detail_type == 305) {
            return "租号";
        } 
        
        elseif ($business_detail_type == 400) {
            return "查档";
        } elseif ($business_detail_type == 401) {
            return "飞机会员";
        } elseif ($business_detail_type == 402) {
            return "票务酒店";
        } elseif ($business_detail_type == 403) {
            return "设计美工";
        } elseif ($business_detail_type == 404) {
            return "搭建开发";
        } elseif ($business_detail_type == 405) {
            return "接码代实名";
        } 
        
        elseif ($business_detail_type == 500) {
            return "卖各种号";
        } elseif ($business_detail_type == 503) {
            return "买卖手机卡";
        } elseif ($business_detail_type == 501) {
            return "卖数据";
        } elseif ($business_detail_type == 502) {
            return "能量trx";
        } elseif ($business_detail_type == 504) {
            return "收粉引流";
        } elseif ($business_detail_type == 505) {
            return "烟酒奢侈品";
        }

        elseif ($business_detail_type == 600) {
            return "其他类";
        } elseif ($business_detail_type == 601) {
            return "外围";
        } elseif ($business_detail_type == 603) {
            return "AI变脸";
        } elseif ($business_detail_type == 604) {
            return "抖音代发";
        } elseif ($business_detail_type == 602) {
            return "服务器等等";
        }
        elseif ($business_detail_type == 700) {
            return "vip小公群";
        } elseif ($business_detail_type == 701) {
            return "VIP代收类";
        } elseif ($business_detail_type == 702) {
            return "VIP承兑类";
        } elseif ($business_detail_type == 703) {
            return "VIP收u代付类";
        }
        
        elseif ($business_detail_type == 1000) {
            return "资源群";
        } 
        
        elseif ($business_detail_type == 2) {
            return "公群2交易群";
        } 
        
        else {
            return "待定（默认）";
        }
    }
    
    public static function all()
    {
        $objs = GroupBusiness::query()
            ->where("p_id", ">", 0)
            ->get();
        $arr = [];
        
        $arr[1] = "待定（默认）";
        $arr[2] = "公群2交易群";
        
        foreach ($objs as $obj) {
            $arr[$obj["id"]] = $obj["name"];
        }
        
        $arr[1000] = "资源群";
        
        return $arr;
    }
    
    public static function show()
    {
        $objs = GroupBusiness::query()
            ->where("p_id", "-1")
            ->get();
        $parents = [];
        
        $parents = [
            [
                "id" => 1,
                "name" => "待定（默认）",
                "childs" => [],
            ],
        ];
        
        foreach ($objs as $k => $obj) {
            $childs_temp = GroupBusiness::query()
                ->where("p_id", $obj["id"])
                ->orderBy("id", "asc")
                ->get(); 
            
            $childs = [];
            foreach ($childs_temp as $item) {
                array_push($childs, [
                    "id" => $item["id"],
                    "name" => $item["name"],
                ]);
            }
            
            array_push($parents, [
                "id" => $obj["id"],
                "name" => $obj["name"],
                "childs" => $childs,
            ]);
        }
    
        array_push($parents, [
            "id" => 1000,
            "name" => "资源群",
            "childs" => [],
        ]);
        array_push($parents, [
            "id" => 2,
            "name" => "公群2交易群",
            "childs" => [],
        ]);
        
        return $parents;
    }
}
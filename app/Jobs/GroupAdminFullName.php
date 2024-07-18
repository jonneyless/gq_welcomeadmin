<?php

namespace App\Jobs;

use App\Service\GroupAdminService;
use App\Service\GroupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GroupAdminFullName implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $group_id;
    protected $groupNameList = [
        1 => ['type' => 1, 'name' => '审计'],
        2 => ['type' => 2, 'name' => '交易员主号'],
        3 => ['type' => 3, 'name' => '交易员备用号'],
        4 => ['type' => 4, 'name' => '巡视员'],
        5 => ['type' => 5, 'name' => '群管'],
        6 => ['type' => 6, 'name' => '备用公群机器人'],
        7 => ['type' => 7, 'name' => '公群小助手'],
        8 => ['type' => 8, 'name' => '建群号'],
        9 => ['type' => 9, 'name' => '巡查'],
    ];


    public function __construct($group_id)
    {
        $this->group_id = $group_id;
    }

    public function handle()
    {
        $sendMsgList = $this->groupAdminFullNameHandle();
        //推送tg预警数据
        $this->sendTgMessage($sendMsgList);
    }

    /**
     * 推送消息给tg
     */
    private function sendTgMessage($sendTgArr)
    {
        $this->sendTgMessageHandle($sendTgArr['lack']);
        $this->sendTgMessageHandle($sendTgArr['redundant']);
    }

    private function sendTgMessageHandle($tgArr)
    {
        if(!empty($tgArr))
        {
            foreach($tgArr as $tg)
            {
                SendMessage::dispatch($tg['chat_id'], $tg['msg']);
            }
        }
    }

    /**
     * 获取数据库群集合数据并处理过滤出
     * 1-- 配置类型下缺少某些类型管理员
     * 2-- 配置类型下多余某些类型管理员
     * @return array[]
     */
    private function groupAdminFullNameHandle(): array
    {
        $groupAdmin = GroupAdminService::get(['group_id' => $this->group_id]);
        $groupAdmin = !empty($groupAdmin) && count($groupAdmin) > 0 ? json_decode(json_encode($groupAdmin), true) : [];

        $rtn = [
            'lack'      => [],
            'redundant' => [],
        ];
        if(!empty($groupAdmin))
        {
            // 频道id分组数据
            $groupList      = [];
            foreach ($groupAdmin as $group)
            {

                if(!array_key_exists($group['chat_id'], $groupList))
                {
                    $groupList[$group['chat_id']][] = $group;
                }else{

                    $push = true;
                    if(is_null($group['fullname']))
                    {
                        $fullName = array_column($groupList[$group['chat_id']], 'fullname');
                        if(in_array($group['fullname'], $fullName))
                        {
                            $push = false;
                        }
                    }

                    if($push === true)
                    {
                        array_push($groupList[$group['chat_id']], $group);
                    }
                }
            }

            //匹配系统配置管理数据聚合
            $result = [
                'succ'  => [],
                'error' => [],
            ];
            foreach ($groupList as $chatId => $groupVal)
            {

                $result['succ'][$chatId]  = [];
                $result['error'][$chatId] = [];
                foreach ($groupVal as $group)
                {
                    //查找名字是否匹配
                    $type = $this->matchFullName($group['fullname']);
                    if(!empty($type))
                    {
                        if(!in_array($type, $result['succ'][$chatId]))
                        {
                            array_push($result['succ'][$chatId], $type);
                        }else{
                            array_push($result['error'][$chatId], $type);
                        }
                    }
                }
            }

            //根据频道id获取对应的频道数据
            $chatIds   = array_keys($groupList);
            $group     = GroupService::get(['chat_ids' => $chatIds]);
            $groupList = !empty($group) ? array_column(json_decode(json_encode($group), true), 'title', 'chat_id') : [];

            //缺少数据聚合
            $types = array_keys($this->groupNameList);
            foreach ($result['succ'] as $chatId => $typeList)
            {
                $diffType = array_diff($types, $typeList);
                $groupMsg = isset($groupList[$chatId]) ? " {$groupList[$chatId]}, 群id {$chatId} 信息为：" : '';
                if(!empty($diffType))
                {
                    foreach ($diffType as $val)
                    {
                        array_push($rtn['lack'], [
                            'chat_id' => $chatId,
                            'msg'     => $groupMsg . "缺少管理类型：".$this->groupNameList[$val]['name'] ?? ''
                        ]);
                    }
                }
            }

            //多余数据聚合
            foreach ($result['error'] as $chatId => $typeList)
            {
                if(!empty($typeList))
                {
                    $typeList = array_count_values($typeList);
                    $groupMsg = isset($groupList[$chatId]) ? " {$groupList[$chatId]}, 群id {$chatId} 信息为：" : '';
                    foreach ($typeList as $typeId => $count)
                    {
                        array_push($rtn['redundant'], [
                            'chat_id' => $chatId,
                            'msg'     => $groupMsg . "管理：".$this->groupNameList[$typeId]['name']."过多，多余数量为：".$count
                        ]);

                    }
                }
            }

        }

        return $rtn;
    }

    /**
     * 根据昵称匹配对应类型的管理员
     * @param $fullName
     * @return int|mixed
     */
    private function matchFullName($fullName)
    {
        $type = 0;
        if(!empty($fullName))
        {
            foreach ($this->groupNameList as $group)
            {

                if(!empty(strstr($fullName, $group['name'])))
                {
                    $type = $group['type'];
                }
            }
        }


        return $type;
    }
}

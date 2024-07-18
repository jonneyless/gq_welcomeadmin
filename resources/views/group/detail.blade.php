<style>
    .pl50 {
        padding-left: 50px;
    }

    #startTime1, #endTime1, #startTime2, #endTime2 {
        display: inline-block;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        color: #555;
        background-color: #fff;
        border: 1px solid #ccc;
        outline: none;
    }

    table th, table td {
        text-align: center;
        font-weight: normal !important;
    }

    .admin_user_tg_id {
        display: inline-block;
        width: 70%;
        margin-left: 10px;
    }

    .admin td {
        border: 1px solid #999999 !important;
    }

    .notice, .setAdmin{
        padding: 20px;
    }

    .notice textarea {
        height: 130px;
    }

    .notice .space {
        display: inline-block;
        width: 150px;
    }

    .radio {
        display: inline-block;
    }

    .pointer {
        cursor: pointer;
    }
    
    input[type=radio] {
        cursor: pointer;
    }
    
    .other_permission {
        color: gray;
    }
    
    .fa-sort, .cursor{
        cursor: pointer;
    }
    
    .search_approve_fullname {
        display: inline-block;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        color: #555;
        background-color: #fff;
        border: 1px solid #ccc;
        outline: none;
    }
</style>
<!--<link rel="stylesheet" href="{{ asset('vendor/datatable/css/dataTables.bootstrap.css') }}">-->
<!--<link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">-->
<input name="chat_id" type="hidden" value="{{ $group["chat_id"] }}">
<input name="title" type="hidden" value="{{ $group["title"] }}">
<input name="welcome_info" type="hidden" value="{{ $group["welcome_info"] }}">
<input name="description" type="hidden" value="{{ $group["description"] }}">
<input name="rules" type="hidden" value="{{ $group["rules"] }}">
<input type="hidden" name="page" value="1" class="page">
<input type="hidden" name="page_len" value="10" class="page_len">
<input name="approve_sort" type="hidden" value="1">
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#test1" data-toggle="tab" class="test1">基础信息</a></li>
                <li><a href="#test2" data-toggle="tab" class="test2">自动发言</a></li>
                <li><a href="#test3" data-toggle="tab" class="test3">入群申请</a></li>
                <li><a href="#test4" data-toggle="tab" class="test4">操作日志</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="test1" style="padding-left: 10px;">
                    <div class="row" style="margin-bottom: -15px;">
                        <div class="col-sm-5">
                            <dl>
                                <dd>chat_id：{{ $group["chat_id"] }}</dd>
                                <br/>
                                <dd>群名：{{ $group["title"] }}
                                    <button type="button" class="btn btn-success btn-sm edit_title">编辑</button>
                                </dd>
                                <dd>群简介：</dd>
                                <dd class="pl50">
                                    {!! $group["description"] !!}
                                    <button type="button" class="btn btn-success btn-sm edit_desc">编辑</button>
                                </dd>
                                <hr/>
                                <dd>群欢迎语：</dd>
                                <dd class="pl50">
                                    {!! $group["welcome_info"] !!}
                                    <button type="button" class="btn btn-success btn-sm edit_info">编辑</button>
                                </dd>
                                <br/>
                                <dd>
                                    群类型：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="r1" class="flag" @if($group["flag"] == 1) checked
                                                   @endif value="1">
                                            待定
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r1" class="flag" @if($group["flag"] == 2) checked
                                                   @endif value="2">
                                            真公群
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r1" class="flag" @if($group["flag"] == 4) checked
                                                   @endif value="4">
                                            游戏群
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r1" class="flag" @if($group["flag"] == 3) checked
                                                   @endif value="3">
                                            骗子群
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    真公群类型：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="rb1" class="business_type" @if($group["business_type"] == 10) checked
                                                   @endif value="10">
                                            代付代收群
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="rb1" class="business_type" @if($group["business_type"] != 10) checked
                                                   @endif value="1">
                                            其他
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    真公群业务类型：
                                    <div class="form-group" style="display: inline-block;width: 300px;">
                                        <select class="form-control business_detail_type" style="width: 100%;">
                                            <option value="1" @if($group["business_detail_type"] == 1) selected @endif>待定（默认）</option>
                                            <option disabled>代收类</option>
                                            <option value="100" @if($group["business_detail_type"] == 100) selected @endif>-卡接一道回u</option>
                                            <option value="101" @if($group["business_detail_type"] == 101) selected @endif>-码接回u</option>
                                            <option value="102" @if($group["business_detail_type"] == 102) selected @endif>-卡接二道回u</option>
                                            <option disabled>承兑类</option>
                                            <option value="200" @if($group["business_detail_type"] == 200) selected @endif>-支付宝微信</option>
                                            <option value="201" @if($group["business_detail_type"] == 201) selected @endif>-现存承兑</option>
                                            <option value="202" @if($group["business_detail_type"] == 202) selected @endif>-pos和备付金</option>
                                            <option value="203" @if($group["business_detail_type"] == 203) selected @endif>-多种资金</option>
                                            <option value="204" @if($group["business_detail_type"] == 204) selected @endif>-海外直通车</option>
                                            <option disabled>收u代付类</option>
                                            <option value="800" @if($group["business_detail_type"] == 800) selected @endif>-保7天以上</option>
                                            <option value="801" @if($group["business_detail_type"] == 801) selected @endif>-保当天</option>
                                            <option value="802" @if($group["business_detail_type"] == 802) selected @endif>-二道混料</option>
                                            <option value="803" @if($group["business_detail_type"] == 803) selected @endif>-一道放料</option>
                                            <option value="804" @if($group["business_detail_type"] == 804) selected @endif>-其他资金</option>
                                            <option disabled>合作类</option>
                                            <option value="300" @if($group["business_detail_type"] == 300) selected @endif>-卡商中介</option>
                                            <option value="301" @if($group["business_detail_type"] == 301) selected @endif>-固话手机口</option>
                                            <option value="302" @if($group["business_detail_type"] == 302) selected @endif>-app跑分</option>
                                            <option value="303" @if($group["business_detail_type"] == 303) selected @endif>-招车队</option>
                                            <option value="304" @if($group["business_detail_type"] == 304) selected @endif>-贴卡片</option>
                                            <option disabled>服务类</option>
                                            <option value="400" @if($group["business_detail_type"] == 400) selected @endif>-查档</option>
                                            <option value="401" @if($group["business_detail_type"] == 401) selected @endif>-飞机会员</option>
                                            <option value="402" @if($group["business_detail_type"] == 402) selected @endif>-票务酒店</option>
                                            <option value="403" @if($group["business_detail_type"] == 403) selected @endif>-设计美工</option>
                                            <option value="404" @if($group["business_detail_type"] == 404) selected @endif>-搭建开发</option>
                                            <option disabled>买卖类</option>
                                            <option value="500" @if($group["business_detail_type"] == 500) selected @endif>-QQ/微信/手机卡</option>
                                            <option value="501" @if($group["business_detail_type"] == 501) selected @endif>-卖数据</option>
                                            <option value="502" @if($group["business_detail_type"] == 502) selected @endif>-能量trx</option>
                                            <option value="600" @if($group["business_detail_type"] == 600) selected @endif>其他类</option>
                                            <option value="700" @if($group["business_detail_type"] == 700) selected @endif>vip小公群</option>
                                            
                                            <option value="1000" @if($group["business_detail_type"] == 1000) selected @endif>资源群</option>
                                        </select>
                                    </div>
                                </dd>
                                <dd>
                                    报备报警比例(0-1)：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>{{ $group["trade_rate"] }}</label>
                                        <button type="button" class="btn btn-success btn-sm trade_rate">编辑
                                        </button>
                                    </div>
                                </dd>
                                <dd>
                                    在群状态：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="rm5" class="status_in" @if($group["status_in"] == 1) checked
                                                   @endif value="1">
                                            在
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="rm55" class="status_in" @if($group["status_in"] == 2) checked
                                                   @endif value="2">
                                            不在
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="rm55" class="status_in" @if($group["status_in"] == 3) checked
                                                   @endif value="3">
                                            待判断
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    押金状态：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="rm" class="statusMoney" @if($group["status_money"] == 3) checked
                                                   @endif value="3">
                                            待定
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="rm" class="statusMoney" @if($group["status_money"] == 1) checked
                                                   @endif value="1">
                                            已上押
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="rm" class="statusMoney" @if($group["status_money"] == 2) checked
                                                   @endif value="2">
                                            已退押
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    入群限制：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="r3" class="people_limit"
                                                   @if($group["people_limit"] == 1) checked
                                                   @endif value="1">
                                            进群自动禁言
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r3" class="people_limit"
                                                   @if($group["people_limit"] == 3) checked
                                                   @endif value="3">
                                            进群验证
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r3" class="people_limit"
                                                   @if($group["people_limit"] == 2) checked
                                                   @endif value="2">
                                            关闭
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    欢迎语：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="r4" class="welcome_status"
                                                   @if($group["welcome_status"] == 1) checked
                                                   @endif value="1">
                                            开启
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r4" class="welcome_status"
                                                   @if($group["welcome_status"] == 2) checked
                                                   @endif value="2">
                                            关闭
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    欢迎语频率(秒)：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>{{ $group["limit_one_time"] }}</label>
                                        <button type="button" class="btn btn-success btn-sm limit_one_time">编辑
                                        </button>
                                    </div>
                                </dd>
                                    <dd>
                                    群编号：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>{{ $group["group_num"] }}</label>
                                        <button type="button" class="btn btn-success btn-sm group_num">编辑
                                        </button>
                                    </div>
                                </dd>
                                <dd>
                                    群交易类型：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="r2" class="trade_type"
                                                   @if($group["trade_type"] == 1) checked
                                                   @endif value="1">
                                            待定
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r2" class="trade_type"
                                                   @if($group["trade_type"] == 2) checked
                                                   @endif value="2">
                                            代收群
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r2" class="trade_type"
                                                   @if($group["trade_type"] == 3) checked
                                                   @endif value="3">
                                            代付群
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r2" class="trade_type"
                                                   @if($group["trade_type"] == 4) checked
                                                   @endif value="4">
                                            支付宝
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r2" class="trade_type"
                                                   @if($group["trade_type"] == 9) checked
                                                   @endif value="9">
                                            全部
                                        </label>
                                    </div>
                                </dd>
                                <dd class="pl50" style="color: grey;">
                                    <p>代收群: 非管理员发送信息包含银行卡号会被删除</p>
                                    <p>代付群: 非管理员发送信息包含虚拟币地址会被删除</p>
                                    <p>支付宝: 非管理员发送信息包含手机号或邮箱会被删除</p>
                                    <p>全部: 非管理员发送信息包含银行卡号或虚拟币地址或手机号或邮箱会被删除</p>
                                </dd>
                                <dd>
                                    通知用户信息更改状态：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="r99" class="send_user_change"
                                                   @if($group["send_user_change"] == 1) checked
                                                   @endif value="1">
                                            开启
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r99" class="send_user_change"
                                                   @if($group["send_user_change"] == 2) checked
                                                   @endif value="2">
                                            关闭
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    根据是否关注@gongqiu，进行禁言操作：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="r99" class="status_follow"
                                                   @if($group["status_follow"] == 1) checked
                                                   @endif value="1">
                                            开启
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r99" class="status_follow"
                                                   @if($group["status_follow"] == 2) checked
                                                   @endif value="2">
                                            关闭
                                        </label>
                                    </div>
                                </dd>
                                <dd>
                                    陷阱模式：
                                    <div class="form-group" style="display: inline-block;">
                                        <label>
                                            <input type="radio" name="r91" class="xianjing_status"
                                                   @if($group["xianjing_status"] == 1) checked
                                                   @endif value="1">
                                            开启
                                        </label>
                                        &nbsp;&nbsp;&nbsp;
                                        <label>
                                            <input type="radio" name="r91" class="xianjing_status"
                                                   @if($group["xianjing_status"] == 2) checked
                                                   @endif value="2">
                                            关闭
                                        </label>
                                    </div>
                                </dd>
                                <dd style="padding-top: 5px;">
                                    <button class="btn btn-danger btn-sm leave">机器人退群</button>&nbsp;&nbsp;
                                    <button class="btn btn-info btn-sm kickAll">踢出群组内所有非管理员用户</button>&nbsp;&nbsp;
                                    <button class="btn btn-primary btn-sm unban">移除该群所有黑名单</button>
                                </dd>
                            </dl>
                        </div>
                        <div class="col-sm-7">
                            <p style="font-size: 16px; margin-bottom: 5px;">审核进群：</p>
                            <dl style="margin-left: 40px; font-size: 16px;">
                                <dd>1. 名字含中文且手动通过两个(非游戏群)公群的入群申请的用户自动审核通过
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_one"
                                               class="status_approve status_approve_one"
                                               @if($group["status_approve_one"] == 1) checked
                                               @endif value="1">
                                        开启
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_one"
                                               class="status_approve status_approve_one"
                                               @if($group["status_approve_one"] != 1) checked
                                               @endif value="2">
                                        关闭
                                    </label>
                                </dd>
                                <dd>2. 名字含中文的自动审核通过
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_two"
                                               class="status_approve status_approve_two"
                                               @if($group["status_approve_two"] == 1) checked
                                               @endif value="1">
                                        开启
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_two"
                                               class="status_approve status_approve_two"
                                               @if($group["status_approve_two"] != 1) checked
                                               @endif value="2">
                                        关闭
                                    </label>
                                </dd>
                                <dd>3. 名字不含中文的自动拒绝
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_three"
                                               class="status_approve status_approve_three"
                                               @if($group["status_approve_three"] == 1) checked
                                               @endif value="1">
                                        开启
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_three"
                                               class="status_approve status_approve_three"
                                               @if($group["status_approve_three"] != 1) checked
                                               @endif value="2">
                                        关闭
                                    </label>
                                </dd>
                                <dd>4. 黑名单里面的用户自动拒绝
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_four"
                                               class="status_approve status_approve_four"
                                               @if($group["status_approve_four"] == 1) checked
                                               @endif value="1">
                                        开启
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_four"
                                               class="status_approve status_approve_four"
                                               @if($group["status_approve_four"] != 1) checked
                                               @endif value="2">
                                        关闭
                                    </label>
                                </dd>
                                <dd>5. 白名单, 官方账号自动通过
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_five"
                                               class="status_approve status_approve_five"
                                               @if($group["status_approve_five"] == 1) checked
                                               @endif value="1">
                                        开启
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_five"
                                               class="status_approve status_approve_five"
                                               @if($group["status_approve_five"] != 1) checked
                                               @endif value="2">
                                        关闭
                                    </label>
                                </dd>
                                <dd>6. VIP群和高级用户对接群用户自动审核通过
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_vip"
                                               class="status_approve status_approve_vip"
                                               @if($group["status_approve_vip"] == 1) checked
                                               @endif value="1">
                                        开启
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="radio" name="status_approve_vip"
                                               class="status_approve status_approve_vip"
                                               @if($group["status_approve_vip"] != 1) checked
                                               @endif value="2">
                                        关闭
                                    </label>
                                </dd>
                                <dd style="margin-top: 5px;">
                                    <button class="btn btn-info btn-sm rejectAll">全部拒绝</button>
                                </dd>
                            </dl>
                            <hr/>
                            <dd style="padding-right: 50px;">
                                {!! $group["rules"] !!}
                                <button type="button" class="btn btn-success btn-sm edit_rules">编辑</button>
                            </dd>
                            <hr/>
                            @if(count($reports) > 0)
                                <div>
                                    <p>押金：{{ $money_yajin }}</p>
                                    <p>交易完成：{{ $money_over }}</p>
                                    <p>交易中：{{ $money_in }}</p>
                                    <p>剩下可报备金额：{{ $money_sub }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <hr/>
                    <div class="row" style="margin-bottom: -15px;">
                         <div class="col-sm-12">
                            <p>群管理：</p>
                            <table class="table table-bordered" style="width: 60%;">
                                <tr>
                                    <th>用户名</th>
                                    <th>tg_id</th>
                                    <th>昵称</th>
                                    <th>头衔</th>
                                    <th>——</th>
                                    <th>——</th>
                                    <th>——</th>
                                </tr>
                                @foreach($admins as $admin)
                                    <tr>
                                        <td>
                                            @if(!$admin["username"] || trim($admin["username"]) == trim($admin["firstname"] . " ". $admin["lastname"]))
                                                <span style="color: grey;">无用户名</span>
                                                &nbsp;
                                            @else
                                                <span>{{ "@" . $admin["username"] }}</span>
                                                &nbsp;
                                            @endif
                                        </td>
                                        <td>{{ $admin["user_id"] }}</td>
                                        <td>
                                            <span>{{ trim($admin["firstname"] . " ". $admin["lastname"]) }}</span>
                                        </td>
                                        <td>
                                            @if ($admin["custom_title"])
                                                <span>{{ trim($admin["custom_title"]) }}</span>
                                            @else
                                                <span>无</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" user_id="{{ $admin['user_id'] }}" class="btn btn-default btn-sm setUserTitle">设置头衔</button>
                                        </td>
                                        <td>
                                            <button type="button" user_id="{{ $admin['user_id'] }}" class="btn btn-default btn-sm edit_admin" admin_user_tg_id="{{ $admin['user_id'] }}">编辑权限</button>
                                        </td>
                                        <td>
                                            @if ($admin["status"] == "creator")
                                                <span style="color: red; font-weight: bold;">群主</span>
                                            @else
                                                <i class="fa fa-remove pointer remove_admin" user_id="{{ $admin['user_id'] }}">移除管理</i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <dl>
                                <dd class="pl50">
                                    <button type="button" class="btn btn-primary btn-sm set_admin"
                                            style="margin-top: 5px;">新增
                                    </button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" class="btn btn-success btn-sm flush_admin"
                                            style="margin-top: 5px;">刷新
                                    </button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" class="btn btn-danger btn-sm set_official_admin"
                                            style="margin-top: 5px;">新增官方账号管理员
                                    </button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <button type="button" class="btn btn-primary btn-sm kick_one_user"
                                            style="margin-top: 5px;">指定踢出
                                    </button>
                                </dd>
                            </dl>
                         </div>
                    </div>
                    <hr/>
                    <div class="row" style="margin-bottom: -15px;">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <span>真公群整点报时</span>
                                &nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="hour_notice_status"
                                               id="hour_notice_status" value="1"
                                               @if($group["hour_notice_status"] == 1)
                                                   checked
                                                @endif
                                        >
                                        开启
                                    </label>
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="hour_notice_status"
                                               id="hour_notice_status" value="2"
                                               @if($group["hour_notice_status"] == 2)
                                                   checked
                                                @endif
                                        >
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <span>非营业时间自动锁群（点击关闭，会打开该群的群禁言）</span>
                                &nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="switch_group_status"
                                               id="switch_group_status" value="1"
                                               @if($group["switch_group_status"] == 1)
                                                   checked
                                                @endif
                                        >
                                        开启
                                    </label>
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="switch_group_status"
                                               id="switch_group_status" value="2"
                                               @if($group["switch_group_status"] == 2)
                                                   checked
                                                @endif
                                        >
                                        关闭
                                    </label>
                                </div>
                                <div class="form-group" style="margin-left: 40px;">
                                    <span>开始营业时间：</span>
                                    <input type="text" id="startTime" name="startTime" autocomplete="off" size="25"
                                           class="startTime" value="{{ $group['started_at'] }}">
                                    &nbsp;&nbsp;&nbsp;
                                    <span>结束营业时间：</span>
                                    <input type="text" id="endTime" name="endTime" autocomplete="off" size="25"
                                           class="endTime" value="{{ $group['ended_at'] }}">
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="button" class="btn btn-info btn-sm submitTime" value="确定">
                                </div>
                            </div>
                            <div class="form-group">
                                <span>真公群进群提示</span>
                                &nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="welcome_true_status"
                                               id="welcome_true_status" value="1"
                                               @if($group["welcome_true_status"] == 1)
                                                   checked
                                                @endif
                                        >
                                        开启
                                    </label>
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="welcome_true_status"
                                               id="welcome_true_status" value="2"
                                               @if($group["welcome_true_status"] == 2)
                                                   checked
                                                @endif
                                        >
                                        关闭
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <span>骗子群进群提示</span>
                                &nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="welcome_false_status"
                                               id="welcome_false_status" value="1"
                                               @if($group["welcome_false_status"] == 1)
                                                   checked
                                                @endif
                                        >
                                        开启
                                    </label>
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="radio">
                                    <label>
                                        <input class="key" type="radio" name="welcome_false_status"
                                               id="welcome_false_status" value="2"
                                               @if($group["welcome_false_status"] == 2)
                                                   checked
                                                @endif
                                        >
                                        关闭
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="test2" style="padding-left: 10px;">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box-body">
                                <div role="form">
                                    <div class="form-group">
                                        <button class="btn btn-info btn-sm add2">新增</button>
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover dataTable" id="dataTable2"
                                       style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>信息</th>
                                        <th>发言频率（秒）</th>
                                        <th>是否置顶</th>
                                        <th>上次执行时间</th>
                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane" id="test3" style="padding-left: 10px;">
                    <div class="box-body">
                        <div class="form-group">
                            <input type="text" name="search_approve_fullname" autocomplete="off" size="25" class="search_approve_fullname">
                            <input type="submit" class="btn btn-info btn-sm submit_search_approve_fullname" search_type="5" value="昵称搜索">
                        </div>
                        <div class="mailbox-controls" style="margin-bottom: 10px;">
                            <button type="button" class="btn btn-default btn-sm checkbox-toggle toggle-single">
                                <i class="fa fa-square-o fa-single"></i>
                            </button>
                            &nbsp;&nbsp;&nbsp;
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm declineAndKick">拒绝+踢出</button>
                                <button type="button" class="btn btn-default btn-sm declineAndKickAndCheat">
                                    拒绝+踢出+黑名单
                                </button>
                                <button type="button" class="btn btn-default btn-sm clearAllapprove">
                                    清空本群申请列表
                                </button>
                                <button type="button" class="btn btn-default btn-sm refreshApprove" style="margin-left:20px;">
                                    刷新列表
                                </button>
                            </div>
                        </div>
                        <!--<p>踢出功能：会踢出这个用户在所有真公群(排除游戏群)</p>-->
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover">
                                <tbody class="approves"></tbody>
                            </table>
                            <div style="margin-top: 10px;display: none;" class="page_view">
                                <div class="pull-left">
                                    从 <span class="start_num">0</span> 到 <span class="end_num">0</span> ，总共 <span
                                            class="approve_count">0</span> 条
                                </div>
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm prev">
                                            上一页
                                        </button>
                                        <button type="button" class="btn btn-default btn-sm next">
                                            下一页
                                        </button>
                                    </div>
                                </div>
                                <label class="control-label pull-right" style="margin-right: 10px; font-weight: 100;">
                                    <small>显示</small>
                                    &nbsp;
                                    <select class="input-sm grid-per-pager page_len" name="per-page">
                                        <option value="10" class="page_len_10" selected>10</option>
                                        <option value="50" class="page_len_50">50</option>
                                        <option value="100" class="page_len_100">100</option>
                                        <option value="200" class="page_len_200">200</option>
                                    </select>
                                    &nbsp;
                                    <small>条</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="tab-pane" id="test4" style="padding-left: 10px;">
                    <div class="box-body">
                        <div role="form">
                            <div class="form-group">
                                <span>开始时间：</span>
                                <input type="text" id="startTime4" name="startTime4" autocomplete="off" size="35"
                                       style="display: inline-block;" class="startTime">
                                &nbsp;&nbsp;&nbsp;
                                <span>结束时间：</span>
                                <input type="text" id="endTime4" name="endTime4" autocomplete="off" size="35"
                                       style="display: inline-block;" class="endTime">
                                &nbsp;&nbsp;&nbsp;
                                <input type="button" class="btn btn-info btn-sm submit4" value="查询">
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered table-hover dataTable" id="dataTable4"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>修改时间</th>
                                    <th>修改人用户名</th>
                                    <th>修改记录内容</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('vendor/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/layer-v3.3.0/layer/layer.js') }}"></script>
<script>
    $(function () {
        $("#startTime").datetimepicker({
            format: 'H:m:s',
            locale: 'zh-cn'
        });
        $("#endTime").datetimepicker({
            format: 'H:m:s',
            locale: 'zh-cn'
        });
        
        $("#startTime4").datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'zh-cn'
        });
        $("#endTime4").datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'zh-cn'
        });

        function loadNotice() {
            $('#dataTable2').DataTable({
                "paging": true,
                "pageLength": 20,
                "lengthChange": false,
                "processing": false,
                "searching": false,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "deferRender": false,
                "serverSide": true,
                "destroy": true,
                // "order": [[0, "desc"]],
                "pagingType": "simple",
                "ajax": {
                    "method": "post",
                    "url": "{{adminUrl('group/notice')}}",
                    "data": function (d) {
                        return $.extend({}, d, {
                            "_token": "{{csrf_token()}}",
                            "chat_id": $("input[name='chat_id']").val(),
                        });
                    }
                },
                language: {
                    emptyTable: "没有数据可以显示",
                    infoEmpty: "没有数据可以显示",
                    info: "从 _START_ 到 _END_ ，总共 _TOTAL_ 条",
                    paginate: {
                        previous: "上页&nbsp;",
                        next: "&nbsp;下页"
                    }
                },
                "columns": [
                    {"data": "id", "width": "10%"},
                    {"data": "msg"},
                    {"data": "space", "width": "10%"},
                    {"data": "flag", "width": "10%"},
                    {"data": "last_noticed", "width": "10%"},
                    {"data": "id", "width": "10%"},
                ],
                "createdRow": function (row, data, index) {
                    console.log(data);
                    if (data["flag"] === 2) {
                        $("td", row).eq(3).empty().html("<span style='color: grey;'>否</span>");
                    } else {
                        $("td", row).eq(3).empty().html("<span style='color: green;'>是</span>");
                    }

                    $("td", row).eq(5).empty().html("<span notice_id='" + data["id"] + "' class='delete' style='cursor: pointer;'>删除</span>");
                },
            });

            $('#dataTable2').on("draw.dt", function () {
                $("tr").each(function () {
                    $(this).find(".delete").click(function () {
                        let notice_id = $(this).attr("notice_id");

                        let load = layer.load();

                        $.ajax({
                            type: "post",
                            data: {
                                "_token": '{{ csrf_token() }}',
                                "id": notice_id,
                            },
                            "url": "{{adminUrl('group/notice/delete')}}",
                            success: function () {
                                layer.close(load);

                                layer.msg("操作成功", {time: 500}, function () {
                                    loadNotice();
                                });
                            },
                            error: function (data) {
                                layer.msg(data["responseJSON"]["message"]);
                                layer.close(load);
                            }
                        });
                    });
                });
            });

            $('#dataTable3').DataTable({
                "paging": true,
                "pageLength": 20,
                "lengthChange": false,
                "processing": false,
                "searching": false,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "deferRender": false,
                "serverSide": true,
                "destroy": true,
                // "order": [[0, "desc"]],
                "pagingType": "simple",
                "ajax": {
                    "method": "post",
                    "url": "{{adminUrl('group/approve')}}",
                    "data": function (d) {
                        return $.extend({}, d, {
                            "_token": "{{csrf_token()}}",
                            "chat_id": $("input[name='chat_id']").val(),
                        });
                    }
                },
                language: {
                    emptyTable: "没有数据可以显示",
                    infoEmpty: "没有数据可以显示",
                    info: "从 _START_ 到 _END_ ，总共 _TOTAL_ 条",
                    paginate: {
                        previous: "上页&nbsp;",
                        next: "&nbsp;下页"
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "fullname"},
                    {"data": "user_tg_id"},
                    {"data": "status"},
                    {"data": "created_at"},
                ],
                "createdRow": function (row, data, index) {
                    if (data["status"] === 2) {
                        $("td", row).eq(3).empty().html("<span style='color: grey;'>等待中</span>");
                    } else if (data["status"] === 1) {
                        $("td", row).eq(3).empty().html("<span style='color: green;'>审核通过</span>");
                    } else if (data["status"] === 3) {
                        $("td", row).eq(3).empty().html("<span style='color: red;'>已拒绝</span>");
                    }
                },
            });
        }

        loadNotice();

        $(".add2").click(function () {
            let layer1 = layer.open({
                type: 1,
                area: ['620px', '400px'],
                title: "自动发言",
                content: '<div class="notice"><div class="form-group"><label>内容</label><textarea class="form-control" name="msg"></textarea></div>' +
                    '<div class="form-group"><label>发言频率（秒）</label><input type="text" class="form-control space" name="space"></div>' +
                    '<div class="form-group"><label>是否置顶（仅发送的第一条信息置顶）：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="r38" class="pin_flag" value="1" checked>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" name="r38" class="pin_flag" value="2">&nbsp;&nbsp;否</label></div>' +
                    '<div class="form-group" style="text-align: center;"><button class="btn btn-info btn-sm submit">确认</button></div></div>'
            });

            $("body").off('click', '.notice .submit').on("click", ".notice .submit", function () {
                let msg = $("textarea[name=msg]").val();
                let space = $("input[name=space]").val();
                let pin_flag = $("input[name=r38]:checked").val();
                
                console.log(pin_flag);

                if (!msg) {
                    layer.msg("内容不能为空");
                    return;
                }
                if (!space) {
                    layer.msg("发言频率不能为空");
                    return;
                }

                if (space <= 0) {
                    layer.msg("发言频率大于等于1秒");
                    return;
                }

                let load = layer.load();

                $.ajax({
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "msg": msg,
                        "space": space,
                        "flag": pin_flag,
                    },
                    "url": "{{adminUrl('group/notice/add')}}",
                    success: function () {
                        layer.close(load);
                        layer.close(layer1);

                        layer.msg("操作成功", {time: 500}, function () {
                            loadNotice();
                        });
                    },
                    error: function (data) {
                        layer.msg(data["responseJSON"]["message"]);
                        layer.close(load);
                    }
                });
            })
        });

        $(".kickAll").click(function () {
            let layer1 = layer.confirm("踢出群组内所有非管理员用户？", function () {

                let layer2 = layer.load();

                $.ajax({
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id": $("input[name='chat_id']").val(),
                    },
                    "url": "{{adminUrl('group/kickAll')}}",
                    success: function () {
                        layer.close(layer2);
                        layer.close(layer1);

                        layer.msg("操作中，请等待...");
                    },
                    error: function (data) {
                        layer.close(layer2);
                        layer.close(layer1);

                        layer.msg(data["responseJSON"]["message"]);
                    }
                });
            });
        });

        $(".unban").click(function () {
            let layer1 = layer.confirm("移除该群所有黑名单？", function () {

                let layer2 = layer.load();

                $.ajax({
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id": $("input[name='chat_id']").val(),
                    },
                    "url": "{{adminUrl('group/unban')}}",
                    success: function () {
                        layer.close(layer2);
                        layer.close(layer1);

                        layer.msg("操作中，请等待...");
                    },
                    error: function (data) {
                        layer.close(layer2);
                        layer.close(layer1);

                        layer.msg(data["responseJSON"]["message"]);
                    }
                });
            });
        });

        $(".flag").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "flag": val,
                },
                "url": "{{adminUrl('group/changeFlag')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        $(".business_type").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "type": val,
                },
                "url": "{{adminUrl('group/changeBusinessType')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        $(".statusMoney").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "flag": val,
                },
                "url": "{{adminUrl('group/changeStatusMoney')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        $(".status_in").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "val": val,
                },
                "url": "{{adminUrl('group/changeStatusIn')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });

        $(".flush_admin").click(function () {
            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                },
                "url": "{{adminUrl('group/flushAdmin')}}",
                success: function (data) {
                    layer.close(layer1);

                    layer.msg(data["message"]);
                    
                    setTimeout(function(){
                        document.location.reload();
                    }, 1000);
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });

        $(".trade_type").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "type": val,
                },
                "url": "{{adminUrl('group/changeTradeType')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });

        $(".send_user_change").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "val": val,
                },
                "url": "{{adminUrl('group/changeSendUserChange')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });

        $(".status_follow").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "val": val,
                },
                "url": "{{adminUrl('group/changeStatusFollow')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        $(".xianjing_status").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "val": val,
                },
                "url": "{{adminUrl('group/changeStatusXianjing')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });

        $(".people_limit").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "type": val,
                },
                "url": "{{adminUrl('group/changePeopleLimit')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });

        $(".welcome_status").click(function () {
            let val = $(this).val();

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "type": val,
                },
                "url": "{{adminUrl('group/changeWelcomeStatus')}}",
                success: function () {
                    layer.close(layer1);

                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });

        $(".limit_one_time").click(function () {
            layer.prompt({title: "欢迎语频率（单位：秒）"}, function (value, index, elem) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    "url": "{{adminUrl('group/changeLimitOneTime')}}",
                    type: "post",
                    data: {
                        _token: '{{csrf_token()}}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "type": value,
                    },
                    success: function (data) {
                        layer.close(index_load);

                        layer.msg("操作成功");

                        document.location.reload();
                    },
                    error: function (data) {
                        layer.close(index_load);

                        layer.msg(data["responseJSON"]["message"]);
                    }
                });
            });
        });

        $(".group_num").click(function () {
            layer.prompt({title: "编号"}, function (value, index, elem) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    "url": "{{adminUrl('group/changeNum')}}",
                    type: "post",
                    data: {
                        _token: '{{csrf_token()}}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "val": value,
                    },
                    success: function (data) {
                        layer.close(index_load);

                        layer.msg("操作成功");

                        document.location.reload();
                    },
                    error: function (data) {
                        layer.close(index_load);

                        layer.msg(data["responseJSON"]["message"]);
                    }
                });
            });
        });

        $(".setUserTitle").click(function () {
            let user_id = $(this).attr("user_id");
            
            layer.prompt({title: "设置管理员头衔"}, function (value, index, elem) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    "url": "{{adminUrl('group/setUserTitle')}}",
                    type: "post",
                    data: {
                        _token: '{{csrf_token()}}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "user_id" : user_id,
                        "custom_title": value,
                    },
                    success: function (data) {
                        layer.close(index_load);

                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(index_load);

                        layer.msg(data["responseJSON"]["message"]);
                    }
                });
            });
        });

        $(".edit_title").click(function () {
            let index_prompt = layer.prompt({
                title: "修改群名",
                formType: 2,
                value: $("input[name=title]").val(),
                area: ['500px', '200px']
            }, function (pass, index) {
                layer.close(index_prompt);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    url: "{{adminUrl('group/changeTitle')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        "chat_id": $("input[name='chat_id']").val(),
                        title: pass
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    }
                });
            });
        });

        $(".edit_rules").click(function () {
            let index_prompt = layer.prompt({
                title: "修改群规",
                maxlength: 99999999,
                formType: 2,
                value: $("input[name=rules]").val(),
                area: ['800px', '300px']
            }, function (pass, index) {
                layer.close(index_prompt);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    url: "{{adminUrl('group/changeRules')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        "chat_id": $("input[name='chat_id']").val(),
                        rules: pass
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    }
                });
            });
        });

        $(".edit_desc").click(function () {
            let index_prompt = layer.prompt({
                title: "修改群简介",
                formType: 2,
                value: $("input[name=description]").val(),
                area: ['500px', '200px']
            }, function (pass, index) {
                layer.close(index_prompt);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    url: "{{adminUrl('group/changeDesc')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        "chat_id": $("input[name='chat_id']").val(),
                        "description": pass
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    }
                });
            });
        });

        $(".key").click(function () {
            let key = $(this).attr("name");
            let val = $(this).val();

            let load = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "key": key,
                    "val": val,
                },
                "url": "{{adminUrl('group/change')}}",
                success: function (data) {
                    layer.msg(data["message"]);
                    layer.close(load);
                },
                error: function (data) {
                    layer.msg(data["responseJSON"]["message"]);
                    layer.close(load);
                }
            });
        });

        $(".submitTime").click(function () {
            let startTime = $("#startTime").val();
            let endTime = $("#endTime").val();

            let load = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "startTime": startTime,
                    "endTime": endTime,
                },
                "url": "{{adminUrl('group/setTime')}}",
                success: function (data) {
                    layer.msg(data["message"]);
                    layer.close(load);
                },
                error: function (data) {
                    layer.msg(data["responseJSON"]["message"]);
                    layer.close(load);
                }
            });
        });

        $(".edit_info").click(function () {
            let index_prompt = layer.prompt({
                title: "修改欢迎语",
                formType: 2,
                value: $("input[name=welcome_info]").val(),
                area: ['500px', '200px']
            }, function (pass, index) {
                layer.close(index_prompt);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    url: "{{adminUrl('group/changeInfo')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        "chat_id": $("input[name='chat_id']").val(),
                        info: pass
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        $.admin.reload();
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);

                        $.admin.reload();
                    }
                });
            });
        });

        $(".set_official_admin").click(function() {
            layer.prompt({title: "新增官方账号管理员"}, function (value, index, elem) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    "url": "{{adminUrl('group/setOfficialAdmin')}}",
                    type: "post",
                    data: {
                        _token: '{{csrf_token()}}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "user_id": value,
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);
    
                        setTimeout(function() {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);
    
                        setTimeout(function() {
                            $.admin.reload();
                        }, 5000);
                    }
                });
            });
        });

        $(".kick_one_user").click(function() {
            layer.prompt({title: "踢出指定用户"}, function (value, index, elem) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    "url": "{{adminUrl('group/kickOneUser')}}",
                    type: "post",
                    data: {
                        _token: '{{csrf_token()}}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "user_id": value,
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);
    
                        setTimeout(function() {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);
    
                        setTimeout(function() {
                            $.admin.reload();
                        }, 5000);
                    }
                });
            });
        });

        $(".set_admin").click(function () {
            let layer1 = layer.open({
                type: 1,
                area: ['400px', '490px'],
                title: "新增管理",
                content: '<div class="setAdmin">' +
                    '<div class="form-group"><label>用户tgid</label><input type="text" class="form-control admin_user_tg_id" name="admin_user_tg_id"></div>' +
                        
                    '<div class="form-group other_permission" style="display: none;"><label>管理群组：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_chat" class="can_manage_chat" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_chat" class="can_manage_chat" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group other_permission"><label>修改群信息：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_change_info" class="can_change_info" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_change_info" class="can_change_info" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group other_permission"><label>管理媒体资源：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_voice_chats" class="can_manage_voice_chats" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_voice_chats" class="can_manage_voice_chats" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group other_permission"><label>新增管理：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_promote_members" class="can_promote_members" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_promote_members" class="can_promote_members" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group" style="margin-top: 5px;"><label>封禁用户：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_restrict_members" class="can_restrict_members" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_restrict_members" class="can_restrict_members" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group"><label>删除信息：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_delete_messages" class="can_delete_messages" value="1" checked>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_delete_messages" class="can_delete_messages" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group"><label>置顶信息：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_pin_messages" class="can_pin_messages" value="1" checked>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_pin_messages" class="can_pin_messages" value="2">&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group"><label>生成邀请链接：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_invite_users" class="can_invite_users" value="1" checked>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_invite_users" class="can_invite_users" value="2">&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group" style="text-align: center;"><button class="btn btn-info btn-sm submitSure">确认</button></div></div>'
            });
            
            $("body").off('click', '.setAdmin .submitSure').on("click", ".setAdmin .submitSure", function () {
                let admin_user_tg_id = $(".admin_user_tg_id").val();
                let can_manage_chat = $("input[name=can_manage_chat]:checked").val();
                let can_change_info = $("input[name=can_change_info]:checked").val();
                let can_pin_messages = $("input[name=can_pin_messages]:checked").val();
                let can_delete_messages = $("input[name=can_delete_messages]:checked").val();
                let can_restrict_members = $("input[name=can_restrict_members]:checked").val();
                let can_invite_users = $("input[name=can_invite_users]:checked").val();
                let can_manage_voice_chats = $("input[name=can_manage_voice_chats]:checked").val();
                let can_promote_members = $("input[name=can_promote_members]:checked").val();
                
                let load = layer.load();

                $.ajax({
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "user_id": admin_user_tg_id,
                        "can_manage_chat": can_manage_chat,
                        "can_change_info": can_change_info,
                        "can_pin_messages": can_pin_messages,
                        "can_delete_messages": can_delete_messages,
                        "can_restrict_members": can_restrict_members,
                        "can_invite_users": can_invite_users,
                        "can_manage_voice_chats": can_manage_voice_chats,
                        "can_promote_members": can_promote_members,
                    },
                    "url": "{{adminUrl('group/setAdmin')}}",
                    success: function (data) {
                        layer.close(load);
                        layer.close(layer1);

                        layer.msg(data["message"], {time: 2000});
                    },
                    error: function (data) {
                        layer.msg(data["responseJSON"]["message"]);
                        layer.close(load);
                    }
                });
            })
        });

        $(".edit_admin").click(function () {
            let admin_user_tg_id = $(this).attr("admin_user_tg_id");
            
            let layer1 = layer.open({
                type: 1,
                area: ['400px', '490px'],
                title: "编辑管理",
                content: '<div class="setAdmin">' +
                    '<div class="form-group"><label>用户tgid</label><input disabled type="text" class="form-control admin_user_tg_id" name="admin_user_tg_id" value='+ admin_user_tg_id +'></div>' +

                    '<div class="form-group other_permission" style="display: none;"><label>管理群组：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_chat" class="can_manage_chat" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_chat" class="can_manage_chat" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group other_permission"><label>修改群信息：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_change_info" class="can_change_info" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_change_info" class="can_change_info" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group other_permission"><label>管理媒体资源：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_voice_chats" class="can_manage_voice_chats" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_manage_voice_chats" class="can_manage_voice_chats" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group other_permission"><label>新增管理：</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_promote_members" class="can_promote_members" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input disabled type="radio" name="can_promote_members" class="can_promote_members" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group" style="margin-top: 5px;"><label>封禁用户：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_restrict_members" class="can_restrict_members" value="1">&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_restrict_members" class="can_restrict_members" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group"><label>删除信息：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_delete_messages" class="can_delete_messages" value="1" checked>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_delete_messages" class="can_delete_messages" value="2" checked>&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group"><label>置顶信息：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_pin_messages" class="can_pin_messages" value="1" checked>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_pin_messages" class="can_pin_messages" value="2">&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group"><label>生成邀请链接：</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_invite_users" class="can_invite_users" value="1" checked>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;<label><input type="radio" name="can_invite_users" class="can_invite_users" value="2">&nbsp;&nbsp;否</label></div>' +
                    
                    '<div class="form-group" style="text-align: center;"><button class="btn btn-info btn-sm submitSure">确认</button></div></div>'
            });
            
            $("body").off('click', '.setAdmin .submitSure').on("click", ".setAdmin .submitSure", function () {
                let can_manage_chat = $("input[name=can_manage_chat]:checked").val();
                let can_change_info = $("input[name=can_change_info]:checked").val();
                let can_pin_messages = $("input[name=can_pin_messages]:checked").val();
                let can_delete_messages = $("input[name=can_delete_messages]:checked").val();
                let can_restrict_members = $("input[name=can_restrict_members]:checked").val();
                let can_invite_users = $("input[name=can_invite_users]:checked").val();
                let can_manage_voice_chats = $("input[name=can_manage_voice_chats]:checked").val();
                let can_promote_members = $("input[name=can_promote_members]:checked").val();
                
                let load = layer.load();

                $.ajax({
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "user_id": admin_user_tg_id,
                        "can_manage_chat": can_manage_chat,
                        "can_change_info": can_change_info,
                        "can_pin_messages": can_pin_messages,
                        "can_delete_messages": can_delete_messages,
                        "can_restrict_members": can_restrict_members,
                        "can_invite_users": can_invite_users,
                        "can_manage_voice_chats": can_manage_voice_chats,
                        "can_promote_members": can_promote_members,
                    },
                    "url": "{{adminUrl('group/setAdmin')}}",
                    success: function (data) {
                        layer.close(load);
                        layer.close(layer1);

                        layer.msg(data["message"], {time: 2000});
                    },
                    error: function (data) {
                        layer.msg(data["responseJSON"]["message"]);
                        layer.close(load);
                    }
                });
            })
        });

        $(".remove_admin").click(function () {
            let user_id = $(this).attr("user_id");
            
            let layer1 = layer.confirm("确定移除该管理？", function () {
            
                let index_load = layer.load();
    
                $.ajax({
                    url: "{{adminUrl('group/removeAdmin')}}",
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "user_id": user_id,
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);
    
                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data.message);
    
                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    }
                });
            });
        });

        $(".leave").click(function () {
            let layer1 = layer.confirm("公群机器人退出该群？", function () {

                let layer2 = layer.load();

                $.ajax({
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "chat_id": $("input[name='chat_id']").val(),
                    },
                    "url": "{{adminUrl('group/leave')}}",
                    success: function (data) {
                        layer.close(layer2);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    },
                    error: function (data) {
                        layer.close(layer2);
                        layer.msg(data.message);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 5000);
                    }
                });
            });
        });

        $(".status_approve").click(function () {
            let key = $(this).attr("name");
            let val = $(this).val();

            let load = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "key": key,
                    "val": val,
                },
                "url": "{{adminUrl('group/changeStatusApprove')}}",
                success: function (data) {
                    layer.msg(data["message"]);
                    layer.close(load);
                },
                error: function (data) {
                    layer.msg(data["responseJSON"]["message"]);
                    layer.close(load);
                }
            });
        });

        $(".rejectAll").click(function () {
            let load = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                },
                "url": "{{adminUrl('group/rejectAllApprove')}}",
                success: function (data) {
                    layer.msg(data["message"]);
                    layer.close(load);
                },
                error: function (data) {
                    layer.msg(data["responseJSON"]["message"]);
                    layer.close(load);
                }
            });
        });
        
        let page_input = $("input[name=page]");
        let page_len_input = $("input[name=page_len]");
        let approves_input = $(".approves");

        function initToggle() {
            $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');

            $(".toggle-single").attr("flag", 2);
        }

        function getCheckedApproves() {
            let items = $(".approves input[type='checkbox']:checked");

            let users = [];
            for (let i = 0; i < items.length; i++) {
                let user = {
                    "user_tg_id": items[i].getAttribute("user_tg_id"),
                    "status": items[i].getAttribute("status"),
                }
                users.push(user);
            }

            return users;
        }

        function loadApprovesBack() {
            initToggle();
            $(".approves").empty().append('<tr><td>加载中...</td></tr>');

            let page = page_input.val();
            let page_len = page_len_input.val();
            
            console.log("aaaa");
            console.log(page);
            console.log(page_len);
            console.log("aaaa");

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "group_tg_id": $("input[name='chat_id']").val(),
                    "page": page,
                    "page_len": page_len,
                    "sort": $("input[name='approve_sort']").val(),
                    "fullname": $("input[name='search_approve_fullname']").val(),
                },
                url: '{{ adminUrl("group/approves")}}',
                success: function (result) {
                    approves_input.empty().append("<tr class='gray'><td></td><td>申请人 <i class='fa fa-fw fa-sort data_sort'></i></td><td>状态</td><td>申请时间</td></tr>");

                    let count = result["data"]["count"];
                    let data = result["data"]["data"];
                    let sort = result["data"]["sort"];
                    
                    if (sort == 2) {
                        approves_input.empty().append("<tr class='gray'><td></td><td>申请人 <i class='fa fa-fw fa-sort-asc data_sort'></i></td><td>状态</td><td>申请时间</td></tr>");
                    } else if(sort == 3) {
                        approves_input.empty().append("<tr class='gray'><td></td><td>申请人 <i class='fa fa-fw fa-sort-desc data_sort'></i></td><td>状态</td><td>申请时间</td></tr>");
                    }

                    console.log(result);

                    $(".approve_count").empty().html(count);
                    $(".start_num").empty().html((page - 1) * page_len + 1);
                    $(".end_num").empty().html(page * page_len);

                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            let item = data[i];
                            let status = item["status"];
       
                            let status_text = "";
                            if (status === 2) {
                                status_text = "<span style='color: grey;'>等待中</span>";
                            } else if (status === 1) {
                                status_text = "<span style='color: green;'>审核通过</span>";
                            } else if (status === 3) {
                                status_text = "<span style='color: red;'>已拒绝</span>";
                            } else {
                                status_text = status;
                            }
                            
                            approves_input.append('<tr><td style="width: 3%;"><input class="approve_checkbox_flag" type="checkbox" status="'+ status +'" user_tg_id="' + item["user_tg_id"] + '"></td><td>' + item['fullname'] + ' ' + item["user_tg_id"] + '</td><td>' + status_text + '</td><td>' + item["created_at"] + '</td></tr>');

                        }
                        $(".page_view").show();
                    } else {
                        approves_input.empty().append('<tr><td>没有数据可以显示</td></tr>');
                    }
                },
            });
        }
        
        function loadApproves() {
            initToggle();
            $(".approves").empty().append('<tr><td>加载中...</td></tr>');

            let page = page_input.val();
            let page_len = page_len_input.val();
            
            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "page": page,
                    "page_len": page_len,
                    "group_tg_id": $("input[name='chat_id']").val(),
                    "sort": $("input[name='approve_sort']").val(),
                    "fullname": $("input[name='search_approve_fullname']").val(),
                },
                url: '{{ adminUrl("log/approve/data")}}',
                success: function (result) {
                    approves_input.empty().append("<tr class='gray'><td></td><td>群名</td><td>tgid</td><td>用户名</td><td>用户昵称 <i class='fa fa-fw fa-sort data_sort'></i></td><td>申请时间</td><td>进群时间</td><td>操作人</td><td>申请状态</td><td>当前状态</td><td>操作</td></tr>");
                    
                    
                    // approves_input.empty().append("<tr class='gray'><td></td><td>申请人 <i class='fa fa-fw fa-sort data_sort'></i></td><td>状态</td><td>申请时间</td></tr>");

                    let count = result["data"]["count"];
                    let data = result["data"]["data"];
                    let sort = result["data"]["sort"];
                    
                    if (sort == 2  || sort == "2") {
                        approves_input.empty().append("<tr class='gray'><td></td><td>群名</td><td>tgid</td><td>用户名</td><td>用户昵称 <i class='fa fa-fw fa-sort-asc data_sort'></i></td><td>申请时间</td><td>进群时间</td><td>操作人</td><td>申请状态</td><td>当前状态</td><td>操作</td></tr>");
                    } else if(sort == 3 || sort == "3") {
                        approves_input.empty().append("<tr class='gray'><td></td><td>群名</td><td>tgid</td><td>用户名</td><td>用户昵称 <i class='fa fa-fw fa-sort-desc data_sort'></td><td>申请时间</td><td>进群时间</td><td>操作人</td><td>申请状态</td><td>当前状态</td><td>操作</td></tr>");
                    }
                    
                    $(".approve_count").empty().html(count);
                    $(".start_num").empty().html((page - 1) * page_len + 1);
                    $(".end_num").empty().html(page * page_len);

                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            let item = data[i];
                            let status = item["status"];
                            let inStatus = item["inStatus"];
       
                            let status_text = "";
                            if (status === 2) {
                                status_text = "<span style='color: grey;'>等待中</span>";
                            } else if (status === 1) {
                                status_text = "<span style='color: green;'>审核通过</span>";
                            } else if (status === 3) {
                                status_text = "<span style='color: red;'>已拒绝</span>";
                            } else {
                                status_text = status;
                            }
                            
                            let status_in_text = "<span class='black'>在群</span>";
                            if (inStatus == 2) {
                                status_in_text = "<span class='red'>已离群</span>";
                            } else if (inStatus == 3) {
                                status_in_text = "<span class='red'>已离群</span>";
                            } else if (inStatus == 4) {
                                status_in_text = "<span class='orange'>在群禁言</span>";
                            } else if (inStatus == 9) {
                                status_in_text = "<span style='color: grey;'>未进群</span>";
                            }
                            
                            let ope_text = "<span class='cursor approve' data_id='"+ item["id"] +"' group_tg_id='" + item["group_tg_id"] + "' user_tg_id='" + item["user_tg_id"] + "'>通过</span>，<span class='cursor reject' data_id='"+ item["id"] +"' group_tg_id='" + item["group_tg_id"] + "' user_tg_id='" + item["user_tg_id"] + "'>拒绝</span>，<span class='cursor kick' group_tg_id='" + item["group_tg_id"] + "' user_tg_id='" + item["user_tg_id"] + "'>踢出</span>，<span class='cursor addCheat' group_tg_id='" + item["group_tg_id"] + "' user_tg_id='" + item["user_tg_id"] + "'>黑名单</span>";

                            approves_input.append('<tr><td style="width: 3%;"><input class="approve_checkbox_flag" type="checkbox" status="'+ status +'" group_tg_id="' + item["group_tg_id"] + '" user_tg_id="' + item["user_tg_id"] + '"></td><td>' + item['title'] + '</td><td>' + item['user_tg_id'] + '</td><td>' + item["username"] + '</td><td>' + item["fullname"] + '</td><td>' + item["created_at"] + '</td><td>' + item["updated_at"] + '</td><td>' + item["admin_id"] + '</td><td>' + status_text + '</td><td>' + status_in_text + '</td><td>' + ope_text + '</td></tr>');

                        }
                        $(".page_view").show();
                    } else {
                        approves_input.empty().append('<tr><td>没有数据可以显示</td></tr>');
                    }
                },
            });
        }

        $(".toggle-single").click(function () {
            let clicks = false;
            if ($(this).attr("flag") == "1") {
                clicks = true;
            }

            if (clicks) {
                // 取消勾选
                $(".approves input[type='checkbox']").iCheck("uncheck");
                $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(this).attr("flag", 2);
            } else {
                // 勾选, 另一个取消勾选
                $(".approves input[type='checkbox']").iCheck("check");
                $(".fa-single").removeClass("fa-square-o").addClass('fa-check-square-o');
                $(this).attr("flag", 1);
            }
        });

        loadApproves();

        $(".prev").click(function () {
            let current_page = page_input.val();
            current_page = parseInt(current_page);

            let page = current_page - 1;
            if (page < 1) {
                page = 1;
            }

            page_input.val(page);

            loadApproves()
        });

        $(".next").click(function () {
            let current_page = page_input.val();
            current_page = parseInt(current_page);

            page_input.val(current_page + 1);

            loadApproves()
        });

        $(".page_len").change(function () {
            let page_len = $(this).val();

            page_len_input.val(page_len);

            loadApproves()
        });

        $(".approves").on("click", ".approve", function() {
            // let group_tg_id = $(this).attr("group_tg_id");
            // let user_tg_id = $(this).attr("user_tg_id");
            let data_id = $(this).attr("data_id");

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "data_id": data_id,
                },
                "url": "{{adminUrl('log/approve/agree')}}",
                success: function (data) {
                    layer.close(layer1);

                    layer.msg(data["message"]);
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        $(".approves").on("click", ".reject", function() {
            let data_id = $(this).attr("data_id");

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "data_id": data_id,
                },
                "url": "{{adminUrl('log/approve/reject')}}",
                success: function (data) {
                    layer.close(layer1);

                    layer.msg(data["message"]);
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        $(".approves").on("click", ".kick", function() {
            let group_tg_id = $(this).attr("group_tg_id");
            let user_tg_id = $(this).attr("user_tg_id");
            let data_id = $(this).attr("data_id");

            let layer1 = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "group_tg_id": group_tg_id,
                    "user_tg_id": user_tg_id,
                },
                "url": "{{adminUrl('log/approve/kick')}}",
                success: function (data) {
                    layer.close(layer1);

                    layer.msg(data["message"]);
                },
                error: function (data) {
                    layer.close(layer1);

                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        $(".approves").on("click", ".addCheat", function() {
            let user_tg_id = $(this).attr("user_tg_id");
            
            layer.prompt({title: '请输入加入担保黑名单原因', formType: 0}, function (pass, index) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    type: "post",
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "user_tg_id": user_tg_id,
                        "reason": pass,  
                    },
                    "url": "{{adminUrl('log/approve/addCheat')}}",
                    success: function (result) {
                        layer.close(index_load);
                        layer.msg(result["message"]);
                    },
                });
            });
        });
        
        function prepareData(users) {
            return {
                "_token": "{{csrf_token()}}",
                "users": users,
            }
        }
        
        function getCheckedApproves() {
            let items = $(".approves input[type='checkbox']:checked");

            let users = [];
            for (let i = 0; i < items.length; i++) {
                let user = {
                    "group_tg_id": items[i].getAttribute("group_tg_id"),
                    "user_tg_id": items[i].getAttribute("user_tg_id"),
                }
                users.push(user);
            }

            return users;
        }
        
        $(".declineAndKick").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedApproves();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);

            $.ajax({
                url: '{{ adminUrl("log/approve/declineAndKick")}}',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });
        
        $(".declineAndKickAndCheat").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedApproves();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);
            
            layer.prompt({title: '请输入加入黑名单原因', formType: 0}, function (pass, index) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                data["reason"] = pass;
                
                console.log(data);

                $.ajax({
                    url: '{{ adminUrl("log/approve/declineAndKickAndCheat")}}',
                    type: "post",
                    data: data,
                    success: function (result) {
                        layer.close(index_load);
                        layer.msg(result["message"]);
                    },
                });
            });
        });

        $(".clearAllapprove").click(function () {
            let index_load = layer.load(0, {shade: false});

            let data = {
                "_token": "{{csrf_token()}}",
                "group_tg_id": $("input[name='chat_id']").val(),
            };

            $.ajax({
                url: '{{ adminUrl("group/approve/clearAllapprove")}}',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });
        
        $(".approves").on("click", ".data_sort", function() {
            if ($(this).hasClass("fa-sort-asc")) {
                $(this).removeClass("fa-sort");
                $(this).removeClass("fa-sort-asc");
                $(this).addClass("fa-sort-desc");
                
                $("input[name=approve_sort]").val(3);
                
                loadApproves();
                return;
            }
            if ($(this).hasClass("fa-sort-desc")) {
                $(this).removeClass("fa-sort");
                $(this).removeClass("fa-sort-desc");
                $(this).addClass("fa-sort-asc");
                
                $("input[name=approve_sort]").val(2);
                loadApproves();
                return;
            }
            if ($(this).hasClass("fa-sort")) {
                $(this).removeClass("fa-sort");
                $(this).removeClass("fa-sort-desc");
                $(this).addClass("fa-sort-asc");
                
                $("input[name=approve_sort]").val(2);
                loadApproves();
                return;
            } 
        });
        
        $(".refreshApprove").click(function() {
            loadApproves();
        });
        
        $(".submit_search_approve_fullname").click(function() {
            loadApproves();
        });
        
        $(".business_detail_type").change(function () {
            let val = $(this).val();
            
            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "chat_id": $("input[name='chat_id']").val(),
                    "type": val,
                },
                "url": "{{adminUrl('group/changeBusinessDetailType')}}",
                success: function () {
                    layer.msg("操作成功");
                },
                error: function (data) {
                    layer.msg(data["responseJSON"]["message"]);
                }
            });
        });
        
        let dataTable4 = $('#dataTable4').DataTable({
            "paging": true,
            "pageLength": 10,
            "lengthChange": false,
            "processing": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "deferRender": false,
            "serverSide": true,
            "destroy": true,
            "pagingType": "simple",
            "ajax": {
                "method": "post",
                "url": "{{adminUrl('log/group')}}",
                "data": function (d) {
                    return $.extend({}, d, {
                        "_token": "{{csrf_token()}}",
                        "group_tg_id": $("input[name='chat_id']").val(),
                        "startTime": $("#startTime4").val(),
                        "endTime": $("#endTime4").val()
                    });
                }
            },
            language: {
                emptyTable: "没有数据可以显示",
                infoEmpty: "没有数据可以显示",
                info: "从 _START_ 到 _END_ ，总共 _TOTAL_ 条",
                paginate: {
                    previous: "上页&nbsp;",
                    next: "&nbsp;下页"
                }
            },
            "columns": [
                {"data": "created_at"},
                {"data": "admin_id"},
                {"data": "info"},
            ],
        });

        $(".submit4").click(function () {
            dataTable4.ajax.reload();
        });
        
        
        $(".trade_rate").click(function () {
            layer.prompt({title: "报备报警比例"}, function (value, index, elem) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    "url": "{{adminUrl('group/changeTradeRate')}}",
                    type: "post",
                    data: {
                        _token: '{{csrf_token()}}',
                        "chat_id": $("input[name='chat_id']").val(),
                        "val": value,
                    },
                    success: function (data) {
                        layer.close(index_load);

                        layer.msg("操作成功");

                        document.location.reload();
                    },
                    error: function (data) {
                        layer.close(index_load);

                        layer.msg(data["responseJSON"]["message"]);
                    }
                });
            });
        });
    });
</script>
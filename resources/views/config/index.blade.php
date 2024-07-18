<style>
    #test1 {
        padding-top: 20px;
        padding-left: 20px;
    }

    .radio {
        display: inline-block;
    }

    .bold {
        font-weight: bold !important;
    }
    
    .limit {
        font-weight: bold !important;
        cursor: pointer;
    }
</style>
<link rel="stylesheet" href="{{ asset('vendor/datatable/css/dataTables.bootstrap.css') }}">
<input name="replyKey" type="hidden" value="{{ $replyKey }}">
<input name="replyVal" type="hidden" value="{{ $replyVal }}">
<div class="box box-primary">
    <div class="box-body" id="test1">
        <div role="form">
            <!--<div class="form-group">-->
            <!--    <span>真公群整点报时</span>-->
            <!--    &nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="hour_notice_status" id="hour_notice_status" value="1"-->
            <!--                   @if($hour_notice_status == 1)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            开启-->
            <!--        </label>-->
            <!--    </div>-->
            <!--    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="hour_notice_status" id="hour_notice_status" value="2"-->
            <!--                   @if($hour_notice_status == 2)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            关闭-->
            <!--        </label>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span>非营业时间自动锁群</span>-->
            <!--    &nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="switch_group_status" id="switch_group_status" value="1"-->
            <!--                   @if($switch_group_status == 1)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            开启-->
            <!--        </label>-->
            <!--    </div>-->
            <!--    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="switch_group_status" id="switch_group_status" value="2"-->
            <!--                   @if($switch_group_status == 2)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            关闭-->
            <!--        </label>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span>真公群进群提示</span>-->
            <!--    &nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="welcome_true_status" id="welcome_true_status" value="1"-->
            <!--                   @if($welcome_true_status == 1)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            开启-->
            <!--        </label>-->
            <!--    </div>-->
            <!--    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="welcome_true_status" id="welcome_true_status" value="2"-->
            <!--                   @if($welcome_true_status == 2)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            关闭-->
            <!--        </label>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span>骗子群进群提示</span>-->
            <!--    &nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="welcome_false_status" id="welcome_false_status" value="1"-->
            <!--                   @if($welcome_false_status == 1)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            开启-->
            <!--        </label>-->
            <!--    </div>-->
            <!--    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
            <!--    <div class="radio">-->
            <!--        <label>-->
            <!--            <input class="key" type="radio" name="welcome_false_status" id="welcome_false_status" value="2"-->
            <!--                   @if($welcome_false_status == 2)-->
            <!--                       checked-->
            <!--                    @endif-->
            <!--            >-->
            <!--            关闭-->
            <!--        </label>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<hr>-->
            <!--<p><span class="bold">[非游戏群][单个群]</span> {{ $limit_time }} 秒内发送 {{ $limit_num }} 条信息自动禁言-->
            <!--</p>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span> 自动禁言 时间限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $limit_time }}秒</span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary limit_time" val="{{ $limit_time }}">修改</button>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span> 自动禁言 频率限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $limit_num }}条 </span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary limit_num" val="{{ $limit_num }}">修改</button>-->
            <!--</div>-->
            <!--<hr>-->
            <!--<p><span class="bold">[非游戏群]</span> {{ $limit_all_time }} 秒内在 {{ $limit_all_group_num }}-->
            <!--    个群发送信息，删除这几条发言并禁言所有所在群，{{ $limit_cancel_restrict }}天后自动解除禁言</p>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span> 时间限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $limit_all_time }}秒</span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary limit_all_time" val="{{ $limit_all_time }}">修改-->
            <!--    </button>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span> 群个数限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $limit_all_group_num }}个 </span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary limit_all_group_num"-->
            <!--            val="{{ $limit_all_group_num }}">修改-->
            <!--    </button>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span> 自动解除禁言：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $limit_cancel_restrict }}天 </span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary limit_cancel_restrict"-->
            <!--            val="{{ $limit_cancel_restrict }}">修改-->
            <!--    </button>-->
            <!--</div>-->
            <!--<hr>-->
            <!--<p>-->
            <!--    1个字符 = 一个英文字母，一个中文，一个标点符号-->
            <!--</p>-->
            <!--<div class="form-group">-->
            <!--    <span>发言长度限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $limit_text_len }}个字符 </span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary limit_text_len"-->
            <!--            val="{{ $limit_text_len }}">修改-->
            <!--    </button>-->
            <!--</div>-->
            <!--@if ($reply_config_text)-->
            <!--    <hr>-->
            <!--    <p><span class="bold">代付代收群 </span>自动回复</p>-->
            <!--    <div class="form-group">-->
            <!--        <div>-->
            <!--            <span class="bold">关键词：</span>-->
            <!--            <span>-->
            <!--                @foreach($reply_config_text["keyy"] as $key)-->
            <!--                    {{ $key }}&nbsp;&nbsp;-->
            <!--                @endforeach-->
            <!--            </span>-->
            <!--            &nbsp;&nbsp;-->
            <!--            <button type="button" class="btn btn-sm btn-primary setReplyKey">修改</button>-->
            <!--        </div>-->
            <!--        <div>-->
            <!--            <span class="bold">回复内容：</span>-->
            <!--            <span>-->
            <!--                {{ $reply_config_text["val"] }}-->
            <!--            </span>-->
            <!--            <button type="button" class="btn btn-sm btn-primary setReplyVal">修改</button>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--@endif-->
            <!--<hr>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span>(发送图片)时间限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $photo_limit_time }}分钟 </span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary photo_limit_time"-->
            <!--            val="{{ $photo_limit_time }}">修改-->
            <!--    </button>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span>(发送图片)群类型数目限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $photo_limit_type_num }}个 </span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary photo_limit_type_num"-->
            <!--            val="{{ $photo_limit_type_num }}">修改-->
            <!--    </button>-->
            <!--</div>-->
            <!--<div class="form-group">-->
            <!--    <span><span class="bold"></span>(发送图片)最近进群天数限制：</span>-->
            <!--    &nbsp;-->
            <!--    <span>{{ $photo_limit_day }}天 </span>-->
            <!--    &nbsp;-->
            <!--    <button type="button" class="btn btn-sm btn-primary photo_limit_day"-->
            <!--            val="{{ $photo_limit_day }}">修改-->
            <!--    </button>-->
            <!--</div>-->
            <!--<hr>-->
            <div class="form-group">
                <span>进群时间 <span class="limit" keyy='one_day'>{{ $limits["one_day"] }}</span> 天内的用户，<span class="limit" keyy='one_minute'>{{ $limits["one_minute"] }}</span> 分钟内在 <span class="limit" keyy='one_type'>{{ $limits["one_type"] }}</span> 种以上类型的群发送信息</span>
                <br/>
                <span>进群时间 <span class="limit" keyy='two_day'>{{ $limits["two_day"] }}</span> 天内的用户，<span class="limit" keyy='two_minute'>{{ $limits["two_minute"] }}</span> 分钟内在 <span class="limit" keyy='two_type'>{{ $limits["two_type"] }}</span> 种或以上类型的群发送了 <span class="limit" keyy='two_num'>{{ $limits["two_num"] }}</span> 以上包含@的信息</span>
                <br/>
                <span>进群时间 <span class="limit" keyy='three_day'>{{ $limits["three_day"] }}</span> 天内的用户，<span class="limit" keyy='three_minute'>{{ $limits["three_minute"] }}</span> 分钟内在 <span class="limit" keyy='three_type'>{{ $limits["three_type"] }}</span> 种或以上类型的群发送了 <span class="limit" keyy='three_num'>{{ $limits["three_num"] }}</span> 条以上信息且昵称为全英文</span>
                <br/>
                <span>进群时间 <span class="limit" keyy='four_day'>{{ $limits["four_day"] }}</span> 天内的用户，<span class="limit" keyy='four_minute'>{{ $limits["four_minute"] }}</span> 分钟内在 <span class="limit" keyy='four_type'>{{ $limits["four_type"] }}</span> 种或以上类型的群发送了 <span class="limit" keyy='four_num'>{{ $limits["four_num"] }}</span> 以上包含@的信息且昵称为全英文</span>
            </div>
            <hr>
            <div class="form-group">
                <span>陷阱模式</span>
                &nbsp;&nbsp;&nbsp;
                <div class="radio">
                    <label>
                        <input class="key_xianjing" type="radio" name="xianjing_status" id="xianjing_status" value="1"
                               @if($xianjing_status == 1)
                                   checked
                                @endif
                        >
                        开启
                    </label>
                </div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="radio">
                    <label>
                        <input class="key_xianjing" type="radio" name="xianjing_status" id="xianjing_status" value="2"
                               @if($xianjing_status == 2)
                                   checked
                                @endif
                        >
                        关闭
                    </label>
                </div>
            </div>
            <div class="form-group">
                <span><span class="bold"></span>陷阱模式时间范围（单位：小时）：</span>
                &nbsp;
                <span>{{ $xianjing_time }}小时</span>
                &nbsp;
                <button type="button" class="btn btn-sm btn-primary xianjing_time" val="{{ $xianjing_time }}">修改</button>
            </div>
            <div class="form-group">
                <span><span class="bold"></span>陷阱模式群数：</span>
                &nbsp;
                <span>{{ $xianjing_num }} 个</span>
                &nbsp;
                <button type="button" class="btn btn-sm btn-primary xianjing_num" val="{{ $xianjing_num }}">修改</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('vendor/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/layer-v3.3.0/layer/layer.js') }}"></script>
<script>
    $(function () {
        $(".key_xianjing").click(function () {
            let key = $(this).attr("name");
            let val = $(this).val();

            let load = layer.load();

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "key": key,
                    "val": val,
                },
                "url": "{{adminUrl('config/change')}}",
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
        
        $(".xianjing_time").click(function () {
            layer.prompt({title: '陷阱模式时间范围（单位：小时）'}, function (pass, index) {
                layer.close(index);

                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    url: "{{adminUrl('config/change')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        "key": "xianjing_time",
                        "val": pass,
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data["message"], {time: 200});

                        document.location.reload();
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data["message"], {time: 200});
                    }
                });
            });
        });
        
        $(".xianjing_num").click(function () {
            layer.prompt({title: '陷阱模式群数'}, function (pass, index) {
                layer.close(index);

                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    url: "{{adminUrl('config/change')}}",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                        "key": "xianjing_num",
                        "val": pass,
                    },
                    success: function (data) {
                        layer.close(index_load);
                        layer.msg(data["message"], {time: 200});

                        document.location.reload();
                    },
                    error: function (data) {
                        layer.close(index_load);
                        layer.msg(data["message"], {time: 200});
                    }
                });
            });
        });
    });
</script>
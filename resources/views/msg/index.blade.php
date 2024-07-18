<style>
    .search_text {
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

    .table {
        margin-bottom: 0 !important;
    }

    .notice textarea {
        height: 130px;
    }
</style>
<link rel="stylesheet" href="{{ asset('vendor/datatable/css/dataTables.bootstrap.css') }}">
<input type="hidden" name="page" value="1" class="page">
<input type="hidden" name="page_len" value="10" class="page_len">
<div class="box box-primary">
    <div class="box-body" id="test1">
        <div role="form">
            <div class="form-group">
                <span>聊天内容：</span>
                <!--<input type="text" name="search_text" autocomplete="off" size="100" class="search_text">-->
                <textarea class="form-control" rows="2" id="search_text" placeholder="Enter ..." style="display: inline-block;width: 50%;"></textarea>
                &nbsp;&nbsp;&nbsp;
                @if(auth()->user()->isRole('kefu'))
                    <div class="checkbox" style="display: inline-block;">
                        <label>
                            <input type="checkbox" name="except_game" checked="true" disabled="true"> 排除游戏群
                        </label>
                    </div>
                    &nbsp;&nbsp;&nbsp;
                @else
                    <div class="checkbox" style="display: inline-block;">
                        <label>
                            <input type="checkbox" name="except_game"> 排除游戏群
                        </label>
                    </div>
                    &nbsp;&nbsp;&nbsp;
                @endif
                <select class="form-control" style="display: inline-block!important;width:250px;"
                        name="langType">
                    <option value="999">全部</option>
                    <!--<option value="1">用户昵称仅中文</option>-->
                    <option value="2">用户昵称仅英文，数字</option>
                </select>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" class="btn btn-info btn-sm search_submit" value="查询">
            </div>
        </div>
        <div class="mailbox-controls" style="margin-bottom: 10px;">
            @if(!auth()->user()->isRole('kefu'))
                <button type="button" class="btn btn-default btn-sm checkbox-toggle toggle-all" flag="2">
                    <i class="fa fa-square-o fa-all"></i>
                    <span>全部</span>
                </button>
                &nbsp;&nbsp;&nbsp;
            @endif
            <button type="button" class="btn btn-default btn-sm checkbox-toggle toggle-single" flag="2">
                <i class="fa fa-square-o fa-single"></i>
            </button>
            &nbsp;&nbsp;&nbsp;
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm delete">删除发言内容</button>
                <button type="button" class="btn btn-default btn-sm kick">踢出用户</button>
                <button type="button" class="btn btn-default btn-sm restrict">禁言</button>
                <button type="button" class="btn btn-default btn-sm addCheat">加入骗子库</button>
            </div>
        </div>
        <div class="table-responsive mailbox-messages">
            <table class="table table-hover">
                <tbody class="msgs"></tbody>
            </table>
            <div style="margin-top: 10px;display: none;" class="page_view">
                <div class="pull-left">
                    从 <span class="start_num">0</span> 到 <span class="end_num">0</span> ，总共 <span
                            class="user_count">0</span> 条
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
                        <option value="500" class="page_len_500">500</option>
                    </select>
                    &nbsp;
                    <small>条</small>
                </label>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('vendor/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/layer-v3.3.0/layer/layer.js') }}"></script>
<script>
    $(function () {
        let search_text_input = $("#search_text");
        let page_input = $("input[name=page]");
        let page_len_input = $("input[name=page_len]");
        let msgs_input = $(".msgs");

        function initToggle() {
            $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
            $(".fa-all").removeClass("fa-check-square-o").addClass('fa-square-o');

            $(".toggle-single").attr("flag", 2);
            $(".toggle-all").attr("flag", 2);
        }

        function getCheckedMsgs() {
            let items = $(".msgs input[type='checkbox']:checked");

            let msgs = [];
            for (let i = 0; i < items.length; i++) {
                let msg = {
                    "group_tg_id": items[i].getAttribute("group_tg_id"),
                    "user_tg_id": items[i].getAttribute("user_tg_id"),
                    "message_tg_id": items[i].getAttribute("message_tg_id"),
                }
                msgs.push(msg);
            }

            return msgs;
        }

        function loadData() {
            initToggle()
            $(".msgs").empty().append('<tr><td>加载中...</td></tr>');

            let search_text = search_text_input.val();
            let page = page_input.val();
            let page_len = page_len_input.val();
            
            console.log(search_text);

            let except_game = 2;
            if ($("input[name='except_game']").prop("checked") == true) {
                except_game = 1;
            }

            $.ajax({
                type: "post",
                data: {
                    "_token": '{{ csrf_token() }}',
                    "search_text": search_text,
                    "except_game": except_game,
                    "page": page,
                    "page_len": page_len,
                    "langType": $("select[name=langType]").val(),
                },
                url: '{{ adminUrl("msg/search")}}',
                success: function (result) {
                    msgs_input.empty().append("<tr class='gray'><td></td><td>用户</td><td>群名</td><td>发言内容</td><td>时间</td><td>状态</td></tr>");

                    let count = result["data"]["count"];
                    let data = result["data"]["data"];

                    $(".user_count").empty().html(count);
                    $(".start_num").empty().html((page - 1) * page_len + 1);
                    $(".end_num").empty().html(page * page_len);

                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            let item = data[i];

                            let status = item["status"];
                            let status_text = "<span class='black'>正常</span>";
                            if (status == 2) {
                                status_text = "<span class='red'>已删除</span>";
                            }
                            
                            let userInfo = "";
                            userInfo = item["user_tg_id"] + " " + item["user_fullname"];

                            msgs_input.append('<tr><td style="width: 3%;"><input class="user_checkbox_flag" type="checkbox" group_tg_id="' + item["group_tg_id"] + '" user_tg_id="' + item["user_tg_id"] + '" message_tg_id="' + item["message_tg_id"] + '"></td><td>' + userInfo + '</td><td>' + item['title'] + '</td><td>' + item["info"] + '</td><td>' + item["created_at"] + '</td><td>' + status_text + '</td></tr>');
                        }
                        $(".page_view").show();
                    } else {
                        msgs_input.empty().append('<tr><td>没有数据可以显示</td></tr>');
                    }
                },
            });
        }

        // fa-check-square-o 勾选状态
        // fa-square-o 空白状态

        $(".toggle-single").click(function () {
            let clicks = false;
            if ($(this).attr("flag") == "1") {
                clicks = true;
            }

            if (clicks) {
                // 取消勾选
                $(".msgs input[type='checkbox']").iCheck("uncheck");
                $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(this).attr("flag", 2);
            } else {
                // 勾选, 另一个取消勾选
                $(".msgs input[type='checkbox']").iCheck("check");
                $(".fa-single").removeClass("fa-square-o").addClass('fa-check-square-o');
                $(this).attr("flag", 1);

                $(".fa-all").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(".toggle-all").attr("flag", 2);
            }
        });

        $(".toggle-all").click(function () {
            let clicks = false;
            if ($(this).attr("flag") == "1") {
                clicks = true;
            }

            if (clicks) {
                // 取消勾选
                $(".msgs input[type='checkbox']").iCheck("uncheck");
                $(".fa-all").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(this).attr("flag", 2);
            } else {
                // 勾选, 另一个取消勾选
                $(".msgs input[type='checkbox']").iCheck("check");
                $(".fa-all").removeClass("fa-square-o").addClass('fa-check-square-o');
                $(this).attr("flag", 1);

                $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(".toggle-single").attr("flag", 2);
            }
        });

        $(".search_submit").click(function () {
            loadData();
        });

        $(".prev").click(function () {
            let current_page = page_input.val();
            current_page = parseInt(current_page);

            let page = current_page - 1;
            if (page < 1) {
                page = 1;
            }

            page_input.val(page);

            loadData();
        });

        $(".next").click(function () {
            let current_page = page_input.val();
            current_page = parseInt(current_page);

            page_input.val(current_page + 1);

            loadData();
        });

        $(".page_len").change(function () {
            let page_len = $(this).val();

            page_len_input.val(page_len);

            loadData();
        });

        function prepareData(msgs) {
            let search_text = search_text_input.val();
            let except_game = 2;
            if ($("input[name='except_game']").prop("checked") == true) {
                except_game = 1;
            }

            let data = {
                "_token": "{{csrf_token()}}",
                "search_text": search_text,
                "except_game": except_game,
                "langType": $("select[name=langType]").val(),
            }

            if ($(".toggle-all").attr("flag") == "1") {
                data["all"] = 1;
            } else {
                data["all"] = 2;
                data["msgs"] = msgs;
            }

            return data;
        }

        $(".delete").click(function () {
            let index_load = layer.load(0, {shade: false});

            let msgs = getCheckedMsgs();
            if (msgs.length < 1) {
                layer.close(index_load);
                layer.msg("信息不能为空");
                return;
            }

            let data = prepareData(msgs);

            $.ajax({
                url: '{{ adminUrl("msg/delete")}}',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg("操作中，请等待...");
                },
            });
        });

        $(".kick").click(function () {
            let index_load = layer.load(0, {shade: false});

            let msgs = getCheckedMsgs();
            if (msgs.length < 1) {
                layer.close(index_load);
                layer.msg("信息不能为空");
                return;
            }

            let data = prepareData(msgs);

            $.ajax({
                url: '{{ adminUrl("msg/kick")}}',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg("操作中，请等待...");
                },
            });
        });

        $(".restrict").click(function () {
            layer.prompt({title: '禁言天数，仅允许输入数字', formType: 0}, function (pass, index) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                let day = pass;
                if (day <= 0) {
                    layer.close(index_load);
                    layer.msg("禁言天数必须为合法数字");
                    return;
                }

                let msgs = getCheckedMsgs();
                if (msgs.length < 1) {
                    layer.close(index_load);
                    layer.msg("信息不能为空");
                    return;
                }

                let data = prepareData(msgs);
                data["day"] = day;

                $.ajax({
                    url: '{{ adminUrl("msg/restrict")}}',
                    type: "post",
                    data: data,
                    success: function (result) {
                        layer.close(index_load);
                        layer.msg("操作中，请等待...");
                    },
                });
            });
        });

        $(".addCheat").click(function () {
            let msgs = getCheckedMsgs();
            if (msgs.length < 1) {
                layer.msg("信息不能为空");
                return;
            }

            layer.prompt({title: '请输入加入骗子库原因', formType: 0}, function (pass, index) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                let data = prepareData(msgs);
                data["reason"] = pass;
                
                $.ajax({
                    url: '{{ adminUrl("msg/addCheat")}}',
                    type: "post",
                    data: data,
                    success: function (result) {
                        layer.close(index_load);
                        layer.msg(result["message"]);
                    },
                });
            });
        });
    });
</script>

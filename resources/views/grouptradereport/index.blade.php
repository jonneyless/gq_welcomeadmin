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

    .ops .btn {
        margin-right: 5px;
    }
</style>
<link rel="stylesheet" href="{{ asset('vendor/datatable/css/dataTables.bootstrap.css') }}">
<input type="hidden" name="page" value="1" class="page">
<input type="hidden" name="page_len" value="10" class="page_len">
<div class="box box-primary">
    <div class="box-body">
        <div role="form">
            <div class="form-group">
                <span>群名：</span>
                <input type="text" name="title" autocomplete="off" size="60" class="search_text">
                &nbsp;&nbsp;&nbsp;
                <span>群tgid：</span>
                <input type="text" name="group_tg_id" autocomplete="off" size="60" class="search_text">
                &nbsp;&nbsp;&nbsp;
                <span>报备状态：</span>
                <div class="form-group" style="display: inline-block;width: 200px;">
                    <select class="form-control types" style="width: 100%;">
                        <option value="-1"></option>
                        <option value="1">已确定(已发送在群里)</option>
                        <option value="2">待定</option>
                        <option value="3">管理取消</option>
                        <option value="3">管理取消</option>
                        <option value="4">客户取消</option>
                        <option value="5">客户已经确认</option>
                        <option value="6">官方取消</option>
                        <option value="9">发送群里失败</option>
                        <option value="10">管理已完成</option>
                        <option value="11">客户已完成</option>
                        <option value="12">官方已完成</option>
                        <option value="13">报备后取消中</option>
                        <option value="14">报备后成功取消</option>
                    </select>
                </div>
                <br/><br/>
                <span>开始时间：</span>
                <input type="text" id="startTime4" name="startTime4" autocomplete="off" size="35"
                       style="display: inline-block;" class="startTime">
                &nbsp;&nbsp;&nbsp;
                <span>结束时间：</span>
                <input type="text" id="endTime4" name="endTime4" autocomplete="off" size="35"
                       style="display: inline-block;" class="endTime">
                <br/><br/>
                &nbsp;
                <input type="submit" class="btn btn-info btn-sm submit" value="搜索">
                &nbsp;
                <input type="submit" class="btn btn-primary btn-sm add" value="新增">
            </div>
        </div>
        <div class="mailbox-controls" style="margin-bottom: 10px;">
            <button type="button" class="btn btn-default btn-sm checkbox-toggle toggle-single" flag="2">
                <i class="fa fa-square-o fa-single"></i>
            </button>
            &nbsp;&nbsp;&nbsp;
            <div class="btn-group ops">
                <button type="button" class="btn btn-default btn-sm del">删除</button>
                <button type="button" class="btn btn-default btn-sm over">官方完成</button>
                <button type="button" class="btn btn-default btn-sm change">转移</button>
            </div>
        </div>
        <div class="table-responsive mailbox-messages">
            <table class="table table-hover">
                <tbody class="users"></tbody>
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
        $("#startTime4").datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'zh-cn'
        });
        $("#endTime4").datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'zh-cn'
        });
        
        let page_input = $("input[name=page]");
        let page_len_input = $("input[name=page_len]");
        let users_input = $(".users");
        
        function initToggle() {
            $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
            $(".toggle-single").attr("flag", 2);
        }

        function getCheckedUsers() {
            let items = $(".users input[type='checkbox']:checked");

            let users = [];
            for (let i = 0; i < items.length; i++) {
                users.push(items[i].getAttribute("data_id"));
            }
        
            return users;
        }

        function get_search_data() {
            return {
                "title": $("input[name=title]").val(),
                "group_tg_id": $("input[name=group_tg_id]").val(),
                "type": $(".types").val(),
                "startTime": $("#startTime4").val(),
                "endTime": $("#endTime4").val(),
            }
        }

        function loadUsers() {
            initToggle();
            $(".users").empty().append('<tr><td>加载中...</td></tr>');

            let page = page_input.val();
            let page_len = page_len_input.val();

            let search_data = get_search_data();
            search_data["_token"] = '{{ csrf_token() }}';
            search_data["page"] = page;
            search_data["page_len"] = page_len;

            $.ajax({
                type: "post",
                data: search_data,
                url: '{{ adminUrl("grouptradereport/data")}}',
                success: function (result) {
                    users_input.empty().append("<tr class='gray'><td></td><td>群tgid</td><td>群名</td><td>报备管理</td><td>客户</td><td>报备金额u</td><td>编号</td><td>状态</td><td>天数</td><td>创建时间</td></tr>");

                    let count = result["data"]["count"];
                    let data = result["data"]["data"];

                    $(".user_count").empty().html(count);
                    $(".start_num").empty().html((page - 1) * page_len + 1);
                    $(".end_num").empty().html(page * page_len);

                    if (data.length > 0) {
                        for (let i = 0; i < data.length; i++) {
                            let item = data[i];
                            
                            users_input.append('<tr><td style="width: 3%;"><input class="user_checkbox_flag" type="checkbox" data_id="' + item["id"] + '"></td><td>' + item["group_tg_id"] + '</td><td>' + item['title'] + '</td><td>' + item['admin_info'] + '</td><td>' + item["user_info"] + '</td><td>' + item['money'] + '</td><td>' + item['uid'] + '</td><td>' + item['status'] + '</td><td>' + item['day'] + '</td><td>' + item['created_at'] + '</td></tr>');

                        }
                        $(".page_view").show();
                    } else {
                        users_input.empty().append('<tr><td>没有数据可以显示</td></tr>');
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
                $(".users input[type='checkbox']").iCheck("uncheck");
                $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(this).attr("flag", 2);
            } else {
                // 勾选, 另一个取消勾选
                $(".users input[type='checkbox']").iCheck("check");
                $(".fa-single").removeClass("fa-square-o").addClass('fa-check-square-o');
                $(this).attr("flag", 1);
            }
        });

        $(".submit").click(function () {
            loadUsers();
        });

        $(".prev").click(function () {
            let current_page = page_input.val();
            current_page = parseInt(current_page);

            let page = current_page - 1;
            if (page < 1) {
                page = 1;
            }

            page_input.val(page);

            loadUsers();
        });

        $(".next").click(function () {
            let current_page = page_input.val();
            current_page = parseInt(current_page);

            page_input.val(current_page + 1);

            loadUsers();
        });

        $(".page_len").change(function () {
            let page_len = $(this).val();

            page_len_input.val(page_len);

            loadUsers();
        });

        function prepareData(users) {
            let search_data = get_search_data();
            search_data["ids"] = users;
            search_data["_token"] = "{{csrf_token()}}";

            return search_data;
        }

        $(".del").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("数据不能为空");
                return;
            }

            let data = prepareData(users);
            
            $.ajax({
                url: '{{ adminUrl("grouptradereport/del")}}',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });
        
        $(".over").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("数据不能为空");
                return;
            }

            let data = prepareData(users);
            
            $.ajax({
                url: '{{ adminUrl("grouptradereport/over")}}',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });
        
        $(".change").click(function () {
            // let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("数据不能为空");
                return;
            }

            let data = prepareData(users);
            
            layer.prompt({title: '请输入新公群tgid', formType: 0}, function (pass, index) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                data["new_group_tg_id"] = pass;
                
                $.ajax({
                    url: '{{ adminUrl("grouptradereport/change")}}',
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

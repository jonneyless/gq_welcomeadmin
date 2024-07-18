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
<link rel="stylesheet" href="<?php echo e(asset('vendor/datatable/css/dataTables.bootstrap.css')); ?>">
<input type="hidden" name="page" value="1" class="page">
<input type="hidden" name="page_len" value="500" class="page_len">
<input type="hidden" name="search_type" value="1" class="search_type">
<div class="box box-primary">
    <div class="box-body">
        <div role="form">
            <div class="form-group">
                <span>搜索：</span>
                <input type="text" name="search_text" autocomplete="off" size="25" class="search_text">
                &nbsp;&nbsp;&nbsp;
                <?php if(auth()->user()->isRole('yunying')): ?>
                    <div class="checkbox" style="display: inline-block;">
                        <label>
                            <input type="checkbox" name="except_game" checked="true" disabled="true"> 排除游戏群
                        </label>
                    </div>
                    &nbsp;&nbsp;&nbsp;
                <?php else: ?>
                    <div class="checkbox" style="display: inline-block;">
                        <label>
                            <input type="checkbox" name="except_game"> 排除游戏群
                        </label>
                    </div>
                    &nbsp;&nbsp;&nbsp;
                <?php endif; ?>
                <input type="submit" class="btn btn-info btn-sm submit_search" search_type="1" value="名字模糊查询">
                &nbsp;
                <input type="submit" class="btn btn-info btn-sm submit_search" search_type="2" value="用户名模糊查询">
                &nbsp;
                <input type="submit" class="btn btn-info btn-sm submit_search" search_type="3" value="精准查询">
                &nbsp;
                <input type="submit" class="btn btn-info btn-sm submit_search" search_type="4" value="用户名查询">
                &nbsp;
                <input type="submit" class="btn btn-info btn-sm submit_search" search_type="5" value="用户id查询">
            </div>
        </div>
        <div class="mailbox-controls" style="margin-bottom: 10px;">
            <?php if(!auth()->user()->isRole('yunying')): ?>
                <button type="button" class="btn btn-default btn-sm checkbox-toggle toggle-all" flag="2">
                    <i class="fa fa-square-o fa-all"></i>
                    <span>全部</span>
                </button>
                &nbsp;&nbsp;&nbsp;
            <?php endif; ?>
            <button type="button" class="btn btn-default btn-sm checkbox-toggle toggle-single" flag="2">
                <i class="fa fa-square-o fa-single"></i>
            </button>
            &nbsp;&nbsp;&nbsp;
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm kick">踢出用户</button>
                <button type="button" class="btn btn-default btn-sm restrict">禁言用户</button>
                <button type="button" class="btn btn-default btn-sm deleteAndRestrict">删除全部发言并禁言</button>
                <button type="button" class="btn btn-default btn-sm cancelRestrict">解开禁言</button>
                <button type="button" class="btn btn-default btn-sm deleteAndKick">删除全部发言并踢出</button>
                <button type="button" class="btn btn-default btn-sm unban">移除黑名单</button>
                <button type="button" class="btn btn-default btn-sm unbanall">移除所有黑名单</button>
                <button type="button" class="btn btn-default btn-sm addCheat">加入骗子库</button>
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
                        <option value="10" class="page_len_10">10</option>
                        <option value="50" class="page_len_50">50</option>
                        <option value="100" class="page_len_100">100</option>
                        <option value="200" class="page_len_200">200</option>
                        <option value="500" class="page_len_500" selected>500</option>
                    </select>
                    &nbsp;
                    <small>条</small>
                </label>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(asset('vendor/datatable/js/jquery.dataTables.js')); ?>"></script>
<script src="<?php echo e(asset('vendor/layer-v3.3.0/layer/layer.js')); ?>"></script>
<script>
    $(function () {
        let search_type_input = $("input[name=search_type]");
        let search_text_input = $("input[name=search_text]");
        let page_input = $("input[name=page]");
        let page_len_input = $("input[name=page_len]");
        let users_input = $(".users");

        function initToggle() {
            $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
            $(".fa-all").removeClass("fa-check-square-o").addClass('fa-square-o');

            $(".toggle-single").attr("flag", 2);
            $(".toggle-all").attr("flag", 2);
        }

        function getCheckedUsers() {
            let items = $(".users input[type='checkbox']:checked");

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

        function loadUsers() {
            initToggle();
            $(".users").empty().append('<tr><td>加载中...</td></tr>');

            let search_type = search_type_input.val();
            let search_text = search_text_input.val();
            let page = page_input.val();
            let page_len = page_len_input.val();

            let except_game = 2;
            if ($("input[name='except_game']").prop("checked") == true) {
                except_game = 1;
            }

            $.ajax({
                type: "post",
                data: {
                    "_token": '<?php echo e(csrf_token()); ?>',
                    "search_type": search_type,
                    "search_text": search_text,
                    "except_game": except_game,
                    "page": page,
                    "page_len": page_len,
                },
                url: '<?php echo e(adminUrl("user/search")); ?>',
                success: function (result) {
                    users_input.empty().append("<tr class='gray'><td></td><td>用户tgId</td><td>昵称</td><td>用户名</td><td>群名</td><td>管理</td><td>状态</td></tr>");

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
                                status_text = "<span class='gray'>已退群</span>";
                            } else if (status == 3) {
                                status_text = "<span class='red'>已踢出，封禁中</span>";
                            } else if (status == 4) {
                                status_text = "<span class='orange'>禁言中</span>";
                            }
                            
                            let admin_text = "<span></span>";
                            if (item["is_admin"] == 1) {
                                admin_text = "<span class='black' style='font-weight:bold!important;'>是</span>";
                            }

                            users_input.append('<tr><td style="width: 3%;"><input class="user_checkbox_flag" type="checkbox" group_tg_id="' + item["group_tg_id"] + '" user_tg_id="' + item["user_tg_id"] + '"></td><td>' + item["user_tg_id"] + '</td><td>' + item['fullname'] + '</td><td>' + item["username"] + '</td><td>' + item["title"] + '</td><td>'+ admin_text +'</td><td>' + status_text + '</td></tr>');

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
                $(".users input[type='checkbox']").iCheck("uncheck");
                $(".fa-all").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(this).attr("flag", 2);
            } else {
                // 勾选, 另一个取消勾选
                $(".users input[type='checkbox']").iCheck("check");
                $(".fa-all").removeClass("fa-square-o").addClass('fa-check-square-o');
                $(this).attr("flag", 1);

                $(".fa-single").removeClass("fa-check-square-o").addClass('fa-square-o');
                $(".toggle-single").attr("flag", 2);
            }
        });

        $(".submit_search").click(function () {
            let search_type = $(this).attr("search_type");
            search_type_input.val(search_type);

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
            let search_type = search_type_input.val();
            let search_text = search_text_input.val();
            let except_game = 2;
            if ($("input[name='except_game']").prop("checked") == true) {
                except_game = 1;
            }

            let data = {
                "_token": "<?php echo e(csrf_token()); ?>",
                "search_type": search_type,
                "search_text": search_text,
                "except_game": except_game,
            }

            if ($(".toggle-all").attr("flag") == "1") {
                data["all"] = 1;
            } else {
                data["all"] = 2;
                data["users"] = users;
            }

            return data;
        }

        $(".kick").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);

            $.ajax({
                url: '<?php echo e(adminUrl("user/kick")); ?>',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
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

                let users = getCheckedUsers();
                if (users.length < 1) {
                    layer.close(index_load);
                    layer.msg("用户不能为空");
                    return;
                }

                let data = prepareData(users);
                data["day"] = day;

                $.ajax({
                    url: '<?php echo e(adminUrl("user/restrict")); ?>',
                    type: "post",
                    data: data,
                    success: function (result) {
                        layer.close(index_load);
                        layer.msg(result["message"]);
                    },
                });
            });
        });

        $(".deleteAndRestrict").click(function () {
            layer.prompt({title: '禁言天数，仅允许输入数字', formType: 0}, function (pass, index) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                let day = pass;
                if (day <= 0) {
                    layer.close(index_load);
                    layer.msg("禁言天数必须为合法数字");
                    return;
                }

                let users = getCheckedUsers();
                if (users.length < 1) {
                    layer.close(index_load);
                    layer.msg("用户不能为空");
                    return;
                }

                let data = prepareData(users);
                data["day"] = day;

                $.ajax({
                    url: '<?php echo e(adminUrl("user/deleteAndRestrict")); ?>',
                    type: "post",
                    data: data,
                    success: function (result) {
                        layer.close(index_load);
                        layer.msg(result["message"]);
                    },
                });
            });
        });

        $(".cancelRestrict").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);

            $.ajax({
                url: '<?php echo e(adminUrl("user/cancelRestrict")); ?>',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });

        $(".deleteAndKick").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);

            $.ajax({
                url: '<?php echo e(adminUrl("user/deleteAndKick")); ?>',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });

        $(".unban").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);

            $.ajax({
                url: '<?php echo e(adminUrl("user/unban")); ?>',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });

        $(".unbanall").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);

            $.ajax({
                url: '<?php echo e(adminUrl("user/unbanall")); ?>',
                type: "post",
                data: data,
                success: function (result) {
                    layer.close(index_load);
                    layer.msg(result["message"]);
                },
            });
        });

        $(".addCheat").click(function () {
            let index_load = layer.load(0, {shade: false});

            let users = getCheckedUsers();
            if (users.length < 1) {
                layer.close(index_load);
                layer.msg("用户不能为空");
                return;
            }

            let data = prepareData(users);

            layer.prompt({title: '请输入加入骗子库原因', formType: 0}, function (pass, index) {
                layer.close(index);
                let index_load = layer.load(0, {shade: false});

                data["reason"] = pass;
                
                console.log(data);

                // $.ajax({
                //     url: '<?php echo e(adminUrl("user/addCheat")); ?>',
                //     type: "post",
                //     data: data,
                //     success: function (result) {
                //         layer.close(index_load);
                //         layer.msg(result["message"]);
                //     },
                // });
            });
        });
    });
</script>

<style>
    table th, table td {
        text-align: center;
        font-weight: normal !important;
    }

    .table {
        margin-bottom: 0 !important;
    }

    #in {
        display: inline-block;
        height: 34px;
        font-size: 14px;
        color: #555;
        background-color: #fff;
        padding: 6px 12px;
        border: 1px solid #ccc;
        outline: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('vendor/datatable/css/dataTables.bootstrap.css') }}">
<div class="box box-primary">
    <div class="box-body">
        <div class="mailbox-controls pull-left">
            <!--<span style="color: gray;">敏感词库更新后，1分钟内生效</span>-->
            <!--<br>-->
            <span style="color: gray;margin-top: 5px;">禁言等级：</span>
            <div style="padding-left: 20px;margin-bottom: 10px;">
                <span style="color: gray;">一级 删除该条发言</span>
                <br>
                <span style="color: gray;">二级 删除该条发言并踢出群组，并加入骗子库</span>
                <br>
                <span style="color: gray;">三级 删除该条发言并永久禁言</span>
            </div>
        </div>
        <div class="mailbox-controls pull-right">
            <button type="button" class="btn btn-info btn-sm add">新增</button>
        </div>
        <table class="table table-bordered table-hover dataTable" id="dataTable" style="width: 100%;">
            <thead>
            <tr>
                <th>敏感词</th>
                <th>等级</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script src="{{ asset('vendor/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/layer-v3.3.0/layer/layer.js') }}"></script>
<script>
    $(function () {
        let dataTable = $('#dataTable').DataTable({
            "paging": false,
            "pageLength": 999,
            "lengthChange": false,
            "processing": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "deferRender": false,
            "serverSide": false,
            "destroy": true,
            "ajax": {
                "method": "post",
                "url": "{{adminUrl('word/data')}}",
                "data": function (d) {
                    return $.extend({}, d, {
                        "_token": "{{csrf_token()}}",
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
                {"data": "name"},
                {"data": "level"},
                {"data": "id"},
            ],
            "createdRow": function (row, data, index) {

                let id = data["id"];
                let level = data["level"];
                let radioName = "level" + id;
                let levelInfo = "";
                if (level == 1) {
                    levelInfo = "一级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='1' word_id='" + id + "' checked>&nbsp;&nbsp;&nbsp;二级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='4' word_id='" + id + "'>&nbsp;&nbsp;&nbsp;三级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='2' word_id='" + id + "'>";
                } else if (level == 4) {
                    levelInfo = "一级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='1' word_id='" + id + "'>&nbsp;&nbsp;&nbsp;二级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='4' word_id='" + id + "' checked>&nbsp;&nbsp;&nbsp;三级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='2' word_id='" + id + "'>";
                } else if (level == 2) {
                    levelInfo = "一级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='1' word_id='" + id + "'>&nbsp;&nbsp;&nbsp;二级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='4' word_id='" + id + "'>&nbsp;&nbsp;&nbsp;三级 &nbsp;<input class='level' type='radio' name='" + radioName + "' level='2' word_id='" + id + "' checked>";
                }
                $("td", row).eq(1).empty().html(levelInfo);

                $("td", row).eq(2).empty().html("<span style='color: gray;cursor: pointer;' class='delete' word_id='" + data['id'] + "'>删除</span>");
            },
        });

        $('#dataTable').on("draw.dt", function () {
            $("tr").each(function () {
                $(this).find(".level").click(function () {
                    let index_load = layer.load(0, {shade: false});

                    let word_id = $(this).attr("word_id");
                    let level = $(this).attr("level");
                    $.ajax({
                        url: "{{adminUrl('word/change/level')}}",
                        type: "post",
                        data: {
                            _token: '{{csrf_token()}}',
                            "id": word_id,
                            "level": level,
                        },
                        success: function (data) {
                            layer.close(index_load);
                            layer.msg(data["message"], {time: 2000});

                            document.location.reload();
                        },
                        error: function (data) {
                            layer.close(index_load);
                            layer.msg(data["message"], {time: 2000});
                        }
                    });
                });

                $(this).find(".delete").click(function () {
                    let index_load = layer.load(0, {shade: false});

                    let word_id = $(this).attr("word_id");
                    $.ajax({
                        url: "{{adminUrl('word/delete')}}",
                        type: "post",
                        data: {
                            _token: '{{csrf_token()}}',
                            "id": word_id,
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

        $(".add").click(function () {
            let layer1 = layer.open({
                title: "添加敏感词",
                type: 1,
                area: ['370px', '195px'], //宽高
                content: "<div style='text-align: center;' class='panel_add'><div style='margin-top: 15px;margin-bottom: 10px;'>敏感词：<input type='text' id='in' class='word_data'></div>" +
                    "<div style='margin-top: 10px;margin-bottom: 10px;'>禁言等级：一级 &nbsp;<input type='radio' name='word_level' value='1' checked>&nbsp;&nbsp;&nbsp;\n" +
                    // "二级 &nbsp;<input type='radio' name='word_level' value='2'>&nbsp;&nbsp;&nbsp;\n" +

                    "二级 &nbsp;<input type='radio' name='word_level' value='4'>&nbsp;&nbsp;&nbsp;" +
                    "三级 &nbsp;<input type='radio' name='word_level' value='2'></div>" +
                    "<button type='button' class='btn btn-info btn-sm sure'>确认</button></div>",
            });

            $("body").on("click", ".sure", function () {
                let word_data = $(".panel_add .word_data").val();
                let word_level = $("input[name='word_level']:checked").val();

                if (!word_data) {
                    layer.msg("敏感词不能为空");
                    return;
                }
                if (!word_level) {
                    layer.msg("敏感词等级不能为空");
                    return;
                }

                $.ajax({
                    url: "{{adminUrl('word/add')}}",
                    type: "post",
                    data: {
                        _token: '{{csrf_token()}}',
                        "name": word_data,
                        "level": word_level,
                        
                    },
                    success: function (data) {
                        layer.close(layer1);
                        layer.msg(data["message"], {time: 200000});

                        // document.location.reload();
                    },
                    error: function (data) {
                        layer.close(layer1);
                        layer.msg(data["message"], {time: 200000});
                    }
                });
            });
        });
    });
</script>

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
            <span style="color: gray;margin-top: 5px;">用户<span style="color: red; font-weight: bold;">搜索关键字</span>中包含限制词，则取消操作</span>
        </div>
        <div class="mailbox-controls pull-right">
            <button type="button" class="btn btn-info btn-sm add">新增</button>
        </div>
        <table class="table table-bordered table-hover dataTable" id="dataTable" style="width: 100%;">
            <thead>
            <tr>
                <th>限制词</th>
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
        "method": "post", "url": "{{adminUrl('word/data')}}", "data": function (d) {
          return $.extend({}, d, {
            "_token": "{{csrf_token()}}", "type": 11,
          });
        }
      },
      language: {
        emptyTable: "没有数据可以显示",
        infoEmpty: "没有数据可以显示",
        info: "从 _START_ 到 _END_ ，总共 _TOTAL_ 条",
        paginate: {
          previous: "上页&nbsp;", next: "&nbsp;下页"
        }
      },
      "columns": [{"data": "name"}, {"data": "id"},],
      "createdRow": function (row, data, index) {
        let id = data["id"];
        $("td", row).eq(1).empty().html("<span style='color: gray;cursor: pointer;' class='delete' word_id='" + data['id'] + "'>删除</span>");
      },
    });
    $('#dataTable').on("draw.dt", function () {
      $("tr").each(function () {
        $(this).find(".delete").click(function () {
          let index_load = layer.load(0, {shade: false});
          let word_id = $(this).attr("word_id");
          $.ajax({
            url: "{{adminUrl('word/delete')}}", type: "post", data: {
              _token: '{{csrf_token()}}', "id": word_id, "type": 11,
            }, success: function (data) {
              layer.close(index_load);
              layer.msg(data["message"], {time: 200});
              document.location.reload();
            }, error: function (data) {
              layer.close(index_load);
              layer.msg(data["message"], {time: 200});
            }
          });
        });
      });
    });
    $(".add").click(function () {
      let layer1 = layer.open({
        title: "添加简介限制词", type: 1, area: ['300px', '195px'], //宽高
        content: "<div style='text-align: center;' class='panel_add'><div style='margin-top: 15px;margin-bottom: 10px;'>限制词：<input type='text' id='in' class='word_data'></div>" + "<button type='button' class='btn btn-info btn-sm sure'>确认</button></div>",
      });
      $("body").on("click", ".sure", function () {
        let word_data = $(".panel_add .word_data").val();
        if (!word_data) {
          layer.msg("限制词不能为空");
          return;
        }
        $.ajax({
          url: "{{adminUrl('word/add')}}", type: "post", data: {
            _token: '{{csrf_token()}}', "name": word_data, "level": 1, "type": 11,
          }, success: function (data) {
            layer.close(layer1);
            layer.msg(data["message"], {time: 200});
            document.location.reload();
          }, error: function (data) {
            layer.close(layer1);
            layer.msg(data["message"], {time: 200});
          }
        });
      });
    });
  });
</script>

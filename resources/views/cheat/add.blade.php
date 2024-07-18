<link rel="stylesheet" href="{{ asset('vendor/datatable/css/dataTables.bootstrap.css') }}">
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">创建</h3>
        <div class="box-tools">
            <div class="btn-group pull-right" style="margin-right: 5px">
                <a href="/E9hzf/cheat" class="btn btn-sm btn-default"
                   title="列表"><i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
            </div>
        </div>
    </div>
    <div action="http://welcome.huionedanbao.com:8639/E9hzf/cheat" method="post"
          class="form-horizontal model-form-64194fe2b9b2d" accept-charset="UTF-8" pjax-container="">
        <div class="box-body">
            <div class="fields-group">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="tgid" class="col-sm-2 asterisk control-label">tgid</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" id="tgid" name="tgid" value="" class="form-control tgid"
                                       placeholder="输入 tgid">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username" class="col-sm-2  control-label">用户名</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" id="username" name="username" value="" class="form-control username"
                                       placeholder="输入 用户名">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="col-sm-2  control-label">firstname</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" id="firstname" name="firstname" value=""
                                       class="form-control firstname" placeholder="输入 firstname">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-sm-2  control-label">lastname</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" id="lastname" name="lastname" value="" class="form-control lastname"
                                       placeholder="输入 lastname">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason" class="col-sm-2  control-label">原因</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                <input type="text" id="reason" name="reason" value="" class="form-control reason"
                                       placeholder="输入 原因">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-primary submit">提交</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('vendor/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('vendor/layer-v3.3.0/layer/layer.js') }}"></script>
<script>
    $(function () {
        $(".submit").click(function () {
            let tgid = $("input[id='tgid']").val();
            let username = $("input[id='username']").val();
            let firstname = $("input[id='firstname']").val();
            let lastname = $("input[id='lastname']").val();
            
            let load = layer.load();

            let data_basic = {
                "tgid": tgid,
                "username": username,
                "firstname": firstname,
                "lastname": lastname,
            }
            
            let data1 = data_basic;
            data1["flag"] = 1;
            data1["_token"] = '{{ csrf_token() }}';
            
            $.ajax({
                type: "post",
                data: data1,
                "url": "{{adminUrl('cheat/save')}}",
                success: function (data) {
                    layer.close(load);
                    
                    let msg = data["message"];
                    
                    if (msg == "admin") {
                        let data2 = data_basic;
                        data2["flag"] = 2;
                        data2["_token"] = '{{ csrf_token() }}';
                        
                        let layer1 = layer.confirm("该用户是管理，确认加入骗子库？", function () {
                            
                            layer.close(layer1);
                            
                            let layer2 = layer.load();
            
                            $.ajax({
                                type: "post",
                                data: data2,
                                "url": "{{adminUrl('cheat/save')}}",
                                success: function (data) {
                                    layer.close(layer2);
                                    
                                    layer.msg(data["message"]);
                                    
                                    setTimeout(function () {
                                        $.admin.reload();
                                    }, 2000);
                                },
                                error: function (data) {
                                    
                                }
                            });
                        });
                    } else {
                        layer.msg(data["message"]);

                        setTimeout(function () {
                            $.admin.reload();
                        }, 2000);
                    }
                },
                error: function (data) {
                    layer.msg(data["responseJSON"]["message"]);
                    layer.close(load);
                }
            });
        });
    });
</script>
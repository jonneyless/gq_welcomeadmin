<div class="box box-primary">
    <div class="box-body">
        <button type="button" class="btn btn-info btn-sm add">新增广播</button>
    </div>
</div>
<script src="<?php echo e(asset('vendor/layer-v3.3.0/layer/layer.js')); ?>"></script>
<script>
    $(function () {
        $(".add").click(function () {
            let index_prompt = layer.prompt({
                title: "新增广播",
                formType: 2,
                area: ['500px', '200px']
            }, function (pass, index) {
                layer.close(index_prompt);
                let index_load = layer.load(0, {shade: false});

                $.ajax({
                    url: "<?php echo e(adminUrl('bullhorn/add')); ?>",
                    type: "post",
                    data: {
                        _token: "<?php echo e(csrf_token()); ?>",
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

                        // $.admin.reload();
                    }
                });
            });
        });
    });
</script>

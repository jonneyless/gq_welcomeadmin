<tfoot>
    <tr>
        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <td class="<?php echo e($column['class']); ?>"><?php echo $column['value']; ?></td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
</tfoot>



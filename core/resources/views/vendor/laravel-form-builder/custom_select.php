<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
    <?php endif; ?>
    <div class="form-line focused">
        <?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
            <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
        <?php endif; ?>

        <?php if ($showField): ?>
            <?php $emptyVal = $options['empty_value'] ? ['' => $options['empty_value']] : null; ?>
            <?= Form::customSelect($name, $options['choices'], $options['selected'], $options['attr'],isset($options['option_attributes']) ? $options['option_attributes'] : array()) ?>
            <?php include 'help_block.php' ?>
        <?php endif; ?>
        <?php include 'errors.php' ?>
    </div>

    <?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>

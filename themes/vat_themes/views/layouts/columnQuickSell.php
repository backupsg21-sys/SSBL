<?php $this->beginContent('//layouts/main'); ?>
<div class="mainWrapper">
    <div class="mainWrapperContent">
        <?php echo $content; ?>
    </div>
</div>
<div class="mainWrapperFooter">
        Copyright &COPY; <?php echo Yii::app()->params->copyrightBy; ?>, Developed By <a href="<?php echo Yii::app()->params->developedByUrl; ?>" target="_blank"><?php echo Yii::app()->params->developedBy; ?></a>
</div>
<?php $this->endContent(); ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="mainWrapper">
    <?php if (!Yii::app()->user->isGuest) { ?>
        <div class="mainWrapperMenu">
            <div class="menuDiv">
                <div class="upperBorder"></div>
                <?php $this->widget('UserMenu'); ?>
                <div class="bottomBorder"></div>
            </div>
        </div>
    <?php } ?>
    <div class="mainWrapperContent">
        <?php if(Yii::app()->user->hasFlash('success')): ?>
            <div class="flash-success">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
        <?php endif; ?>
        <?php if(Yii::app()->user->hasFlash('error')): ?>
            <div class="flash-error">
                <?php echo Yii::app()->user->getFlash('error'); ?>
            </div>
        <?php endif; ?>

        <?php echo $content; ?>
    </div>
</div>
<div class="mainWrapperFooter">
        Copyright &COPY; <?php echo Yii::app()->params->copyrightBy; ?>, Developed By <a href="<?php echo Yii::app()->params->developedByUrl; ?>" target="_blank"><?php echo Yii::app()->params->developedBy; ?></a>
</div>
<?php $this->endContent(); ?>


<style>
    .flash-success, flash-error {
        -moz-animation: cssAnimation 0s ease-in 4s forwards;
        -webkit-animation: cssAnimation 0s ease-in 4s forwards;
        -o-animation: cssAnimation 0s ease-in 4s forwards;
        animation: cssAnimation 0s ease-in 4s forwards;
        -webkit-animation-fill-mode: forwards;
        animation-fill-mode: forwards;

        color: #fff !important;
        padding: 2px !important;
        border: none !important;
    }
    @keyframes cssAnimation {
        to {
            width:0;
            height:0;
            overflow:hidden;
        }
    }
    @-webkit-keyframes cssAnimation {
        to {
            width:0;
            height:0;
            visibility:hidden;
        }
    }
    .flash-success {
        background-color: #4949f7 !important;
    }
    .flash-error {
        background-color: red !important;
    }
</style>

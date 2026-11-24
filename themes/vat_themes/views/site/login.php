<link href='https://fonts.googleapis.com/css?family=Athiti' rel='stylesheet'>


<div class="row-fluid">
    <div class="login-head">
	
        <img class="logo-content" style="height: 80px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo.png"/>
		<br/>
	<br/>
        <div style="text-align: center; color: #f3aa00; font-size: 25px; font-family: 'Athiti'; font-weight:bold;">VAT ACCOUNTING SOFTWARE </div>
    </div>
</div>
<div class="row-fluid box login-panel">
    <div class="span12">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>
        <?php echo $form->errorSummary($model); ?>
        <div class="row-fluid margin-top-10px">
            <div class="span12" style="height:185px;">
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $form->textField($model, 'username', array('label' => false, "class" => "span12", "placeholder" => "Username")); ?>
                    </div>
                </div>
                <div class="row-fluid margin-top-10px">
                    <div class="span12">
                        <?php echo $form->passwordField($model, 'password', array('label' => false, 'value' => '', "class" => "span12", "placeholder" => "Password")); ?>
                    </div>
                </div>

                <div class="row-fluid margin-top-bottom-10px">
                    <div class="span12">
                        <?php echo CHtml::submitButton('Login', array('class'=>'span12 btn-submit')); ?>
                    </div>
                     
                     <div class="btn-reg">
                         <?php echo CHtml::link('Registration', array('user/registration')); ?>
                        <?php //echo CHtml::submitButton('Registration', array('class'=>'span12 btn-submit')); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>

    </div>
</div>

		<div class="login-head">
		<div style="text-align:center;font-size: 13px;">Developed By</div>
			<div style="text-align:center"><img class="logo-content" style="height: 50px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/MIT-Logo.jpg"/></div>
		</div>

<style>
    .btn-reg{
        background: #3498DB;
        border: 0;
        padding: 12px 0;
        font-size: 16px;
        width: 100%;
        text-align: center ;
        margin-top: 6px;
    }
    .btn-reg a {
          color: #fff;
          text-decoration: none;
    }

</style>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="language" content="en" />
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/images/favicon.gif" />

	<!-- Latest compiled and minified CSS -->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
</head>
<body>
	
</body>
</html>


<?php $this->beginContent(Rights::module()->appLayout); ?>

<div id="rights" class="">
	<div class="row">
		<div class="col-md-12">
			<div id="content">

				<?php $this->renderPartial('/_flash'); ?>

				<?php if( $this->id!=='install' ): ?>
					<div id="menu">
						<?php $this->renderPartial('/_menu'); ?>
					</div>
				<?php endif; ?>


				<?php echo $content; ?>

			</div><!-- content -->
		</div>
	</div>

</div>

<?php $this->endContent(); ?>
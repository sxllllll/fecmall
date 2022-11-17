<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>

<?php
$jsOptions = [
	# js config 1
	[
		'options' => [
			'position' =>  'POS_END',
		//	'condition'=> 'lt IE 9',
		],
		'js'	=>[
            'js/jquery-3.1.1.min.js',
            //'js/js.js',
            'js/lazyload.min.js',
		],
	],
];


# css config
$cssOptions = [
	# css config 1.
	[
		'css'	=>[
			'css/base.css',
            'css/category.css',
		],
	],
];
\Yii::$service->page->asset->jsOptions 	= \yii\helpers\ArrayHelper::merge($jsOptions, \Yii::$service->page->asset->jsOptions);
\Yii::$service->page->asset->cssOptions = \yii\helpers\ArrayHelper::merge($cssOptions, \Yii::$service->page->asset->cssOptions);				
\Yii::$service->page->asset->register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
<?= Yii::$service->page->widget->render('base/head',$this); ?>
</head>
<body>
<?= Yii::$service->page->widget->render('base/beforeContent',$this); ?>
<?php $this->beginBody() ?>
    <div class="page">
        <?= $content; ?>
        
    </div>
    <?= Yii::$service->page->widget->render('base/trace',$this); ?>
<?php $this->endBody() ?>
<?= Yii::$service->page->widget->render('base/afterContent',$this); ?>
</body>
</html>
<?php $this->endPage() ?>

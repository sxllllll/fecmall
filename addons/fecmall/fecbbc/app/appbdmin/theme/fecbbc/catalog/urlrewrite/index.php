<?php
/**
 * FecMall file.
 *
 * @link http://www.fecmall.com/
 * @copyright Copyright (c) 2016 FecMall Software LLC
 * @license http://www.fecmall.com/license
 */
use fec\helpers\CRequest;
/**
 * @author Terry Zhao <2358269014@qq.com>
 * @since 1.0
 */
?>
<form id="pagerForm" method="post" action="<?= \fec\helpers\CUrl::getCurrentUrl();  ?>">
	<?=  CRequest::getCsrfInputHtml();  ?>
	<?=  $pagerForm;  ?>
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?= \fec\helpers\CUrl::getCurrentUrl();  ?>" method="post">
		<?php echo CRequest::getCsrfInputHtml();  ?>
		<div class="searchBar">
			<?php  echo $searchBar; ?>
		</div>
	</form>
</div>
<div class="pageContent url-rewrite">
	
	<div class="panelBar">
		<?= $toolBar; ?>
	</div>
	<table class="table" width="100%" layoutH="138">
		<?= $thead; ?>
		<tbody>
			<?= $tbody; ?>
		</tbody>
	</table>
	
</div>

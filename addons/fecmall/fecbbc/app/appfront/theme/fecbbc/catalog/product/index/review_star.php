<?php $star = round($parentThis['reviw_rate_star_average']) ?>
<div class="rating d-inline-block mb-15">
    <?php for($i=1; $i<=5; $i++): ?>
        <?php if ($i <= $star): ?>
            <i class="fa fa-star active"></i>
        <?php else: ?>
            <i class="fa fa-star"></i>
        <?php endif; ?>
    <?php endfor; ?>
</div>
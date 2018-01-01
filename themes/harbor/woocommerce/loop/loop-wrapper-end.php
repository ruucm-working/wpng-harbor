<?php
$is_sidebar = is_active_sidebar('woocommerce_shop');
?>
    </div>
    <?php if ($is_sidebar) : ?>
        <div class="col-sm-4 col-lg-3">
            <div class="wc-shop-sidebar">
                <?php dynamic_sidebar('woocommerce_shop') ?>
            </div>
        </div>
    <?php endif ?>
</div>

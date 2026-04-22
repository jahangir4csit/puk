<?php

/**
 * Partial template for content in page.php
 *
 * @package redapple
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>
<main>
    <div class="r_puk_container">
        <?php
	the_content();
	?>
    </div>
</main>
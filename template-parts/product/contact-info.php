<?php
/**
 * Template part for displaying contact info section
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    return;
}
?>

<section class=" light-distribution-main contact-info">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-box">
                    <h3>Info</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-2 col-lg-2"></div>
            <div class="col-sm-12 col-md-10 col-lg-10">


                <div class="inforzioni-box">
                    <p>Hai bisogno di ricevere maggiori informazioni su questo prodotto?</p>
                    <a href="#">
                        <div class="text">Richiesta informazioni</div>
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 42 42"
                                fill="none">
                                <path
                                    d="M3.5 21C3.5 11.3348 11.3347 3.5 21 3.5C30.6652 3.5 38.5 11.3348 38.5 21C38.5 30.6652 30.6652 38.5 21 38.5C17.9812 38.5 15.1392 37.7352 12.6586 36.3877L5.45125 38.437C4.29712 38.7651 3.23137 37.6985 3.5595 36.5452L5.60875 29.337C4.21986 26.778 3.49483 23.9116 3.5 21ZM14 17.7188C14 18.3225 14.49 18.8125 15.0937 18.8125H26.9062C27.0499 18.8125 27.1921 18.7842 27.3248 18.7292C27.4575 18.6743 27.5781 18.5937 27.6796 18.4921C27.7812 18.3906 27.8618 18.27 27.9167 18.1373C27.9717 18.0046 28 17.8624 28 17.7188C28 17.5751 27.9717 17.4329 27.9167 17.3002C27.8618 17.1675 27.7812 17.0469 27.6796 16.9454C27.5781 16.8438 27.4575 16.7632 27.3248 16.7083C27.1921 16.6533 27.0499 16.625 26.9062 16.625H15.0937C14.49 16.625 14 17.115 14 17.7188ZM15.0937 23.1875C14.8037 23.1875 14.5255 23.3027 14.3203 23.5079C14.1152 23.713 14 23.9912 14 24.2812C14 24.5713 14.1152 24.8495 14.3203 25.0546C14.5255 25.2598 14.8037 25.375 15.0937 25.375H23.4062C23.6963 25.375 23.9745 25.2598 24.1796 25.0546C24.3848 24.8495 24.5 24.5713 24.5 24.2812C24.5 23.9912 24.3848 23.713 24.1796 23.5079C23.9745 23.3027 23.6963 23.1875 23.4062 23.1875H15.0937Z"
                                    fill="black" />
                            </svg>
                        </div>
                    </a>
                </div>
                <div class="toggle-form-box">
                    <?php echo do_shortcode('[contact-form-7 id="d492052" title="Contact form For Product Details Page"]'); ?>
                </div>



            </div>
        </div>
    </div>
</section>

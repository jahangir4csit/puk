<?php

/**
 * The template for displaying single project
 *
 * @package Puk
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
get_header();

?>

<main class="projects_details_page">
    <!-- projects_details section one start  -->
    <section class="prjct_pg_dtls_1">
        <div class="container">
            
            <div class="blog_sidebar">
                <div class="blog_back_link project_back_link">
                    <a href="<?php echo esc_url(home_url('/')); ?>projects">
                        <span>Back</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M2.98867 11.6712L3.51225 12.1958L7.88751 16.5672L8.91259 15.5171L5.78938 12.3989L20.4138 12.3923L20.4131 10.9346L5.78946 10.9411L8.90916 7.82006L7.88242 6.77092L3.51105 11.1462L2.98867 11.6712Z" fill="black"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="r_pd_title_section">
                <div class="row">
                    <!-- project details section one start -->
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                        <div class="prjct_pg_dtls_1_lft">
                            <h1><?php the_title(); ?></h1>
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12">
                        <div class="prjct_pg_dtls_1_right">
                            <!-- image  -->
                            <div class="prjct_pg_dtls_1_right_img">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('full', ['alt' => get_the_title()]); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="r_pd_content_section">
                <div class="row">
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-12">
                        <div class="prjct_pg_dtls_1_right">

                            <!-- Mid  -->
                            <div class="prjct_pg_dtls_1_right_mid">
                                <!-- Mid Left  -->
                                <div class="prjct_dtls_1_right_midlft">
                                    <ul>
                                        <?php if (get_field('opera')) : ?>
                                            <li>
                                                <span>Opera</span>
                                                <p><?php echo esc_html(get_field('opera')); ?></p>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (get_field('place')) : ?>
                                            <li>
                                                <span>Place</span>
                                                <p><?php echo esc_html(get_field('place')); ?></p>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (get_field('year')) : ?>
                                            <li>
                                                <span>Year</span>
                                                <p><?php echo esc_html(get_field('year')); ?></p>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (get_field('architects')) : ?>
                                            <li>
                                                <span>Architects</span>
                                                <p><?php echo esc_html(get_field('architects')); ?></p>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <!-- Mid Right  -->
                                <div class="prjct_dtls_1_right_midrhgt">

                                    <?php if (get_field('project_description')) : ?>
                                        <?php echo get_field('project_description'); ?>
                                    <?php endif; ?>

                                    <div class="prjct_dtls_1_right_socialmdia">
                                        <?php
                                        $current_url   = urlencode(get_permalink());
                                        $current_title = urlencode(get_the_title());
                                        ?>
                                        <a href="https://www.instagram.com/?url=<?php echo $current_url; ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/circum_instagram.svg" alt="Instagram">
                                        </a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $current_url; ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/circum_instagram-1.svg" alt="Linkedin">
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>" target="_blank" rel="noopener noreferrer">
                                            <img src="https://puk.dominiotest.ch/wp-content/uploads/2025/12/qlementine-icons_facebook-24.svg" alt="Facebook">
                                        </a>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- project details section one end -->

                </div>
            </div>
        </div>
    </section>
    <!-- projects_details section one end  -->


    <!-- Static image gallery section start -->
    <?php $gallery = get_field('project_gallery'); ?>
    <?php if ($gallery) : ?>
    <section class="prjct_pg_dtls_1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="prjct_pg_dtls_1_right_bottom">

                        <?php foreach ($gallery as $image) :
                            $image_url = $image['url'];
                            $image_alt = $image['alt'] ? $image['alt'] : get_the_title();
                        ?>
                            <div class="lightbox_img">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Static image gallery section end -->


    <!-- Image / Video gallery section start -->
    <?php $media_gallery = get_field('image_video_gallery'); ?>
    <?php if ($media_gallery) : ?>
    <section class="prjct_pg_dtls_1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="prjct_pg_dtls_1_right_bottom">

                        <?php foreach ($media_gallery as $item) :

                            /*
                             * NEW field structure:
                             *   $item['media_type']         → 'image' | 'video'  (radio)
                             *   $item['media_video_source'] → 'video_youtube' | 'video_vimeo' | 'video_upload'  (select, only when video)
                             */

                            $media_type    = $item['media_type'];           // 'image' or 'video'
                            $video_source  = $item['media_video_source'];   // populated only when media_type === 'video'

                            /* ── IMAGE ─────────────────────────────────────────────────── */
                            if ($media_type === 'image') :
                                $img = $item['media_image'];
                                if ($img) :
                                    $img_url = esc_url($img['url']);
                                    $img_alt = esc_attr($img['alt'] ? $img['alt'] : get_the_title());
                            ?>
                                <div class="lightbox_img">
                                    <img src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>">
                                </div>
                            <?php
                                endif;

                            /* ── VIDEO ─────────────────────────────────────────────────── */
                            elseif ($media_type === 'video') :

                                $thumb     = $item['media_video_thumbnail'];
                                $thumb_url = $thumb ? esc_url($thumb['url']) : '';
                                $thumb_alt = $thumb ? esc_attr($thumb['alt'] ? $thumb['alt'] : get_the_title()) : '';

                                /* — Local / uploaded video — */
                                if ($video_source === 'video_upload') :
                                    $video_file = $item['media_video_upload'];
                                    if ($video_file && $thumb_url) :
                                        $video_src = esc_url($video_file['url']);
                            ?>
                                <div class="lightbox_img lightbox_video_item"
                                     data-video-type="video_upload"
                                     data-video-src="<?php echo $video_src; ?>">
                                    <img src="<?php echo $thumb_url; ?>" alt="<?php echo $thumb_alt; ?>">
                                    <div class="lightbox_play_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" fill="none">
                                            <circle cx="40" cy="40" r="40" fill="rgba(0,0,0,0.5)"/>
                                            <polygon points="30,20 65,40 30,60" fill="white"/>
                                        </svg>
                                    </div>
                                </div>
                            <?php
                                    endif;

                                /* — YouTube video — */
                                elseif ($video_source === 'video_youtube') :
                                    $video_url = $item['media_video_url'];
                                    if ($video_url && $thumb_url) :
                                        preg_match('/(?:v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $yt_matches);
                                        $embed_url = isset($yt_matches[1])
                                            ? esc_url('https://www.youtube.com/embed/' . $yt_matches[1] . '?autoplay=1')
                                            : '';
                                        if ($embed_url) :
                            ?>
                                <div class="lightbox_img lightbox_video_item"
                                     data-video-type="video_youtube"
                                     data-video-src="<?php echo $embed_url; ?>">
                                    <img src="<?php echo $thumb_url; ?>" alt="<?php echo $thumb_alt; ?>">
                                    <div class="lightbox_play_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" fill="none">
                                            <circle cx="40" cy="40" r="40" fill="rgba(0,0,0,0.5)"/>
                                            <polygon points="30,20 65,40 30,60" fill="white"/>
                                        </svg>
                                    </div>
                                </div>
                            <?php
                                        endif;
                                    endif;

                                /* — Vimeo video — */
                                elseif ($video_source === 'video_vimeo') :
                                    $video_url = $item['media_video_url'];
                                    if ($video_url && $thumb_url) :
                                        preg_match('/vimeo\.com\/(\d+)/', $video_url, $vm_matches);
                                        $embed_url = isset($vm_matches[1])
                                            ? esc_url('https://player.vimeo.com/video/' . $vm_matches[1] . '?autoplay=1')
                                            : '';
                                        if ($embed_url) :
                            ?>
                                <div class="lightbox_img lightbox_video_item"
                                     data-video-type="video_vimeo"
                                     data-video-src="<?php echo $embed_url; ?>">
                                    <img src="<?php echo $thumb_url; ?>" alt="<?php echo $thumb_alt; ?>">
                                    <div class="lightbox_play_icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" fill="none">
                                            <circle cx="40" cy="40" r="40" fill="rgba(0,0,0,0.5)"/>
                                            <polygon points="30,20 65,40 30,60" fill="white"/>
                                        </svg>
                                    </div>
                                </div>
                            <?php
                                        endif;
                                    endif;

                                endif; // end video_source switch
                            endif; // end media_type check

                        endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <!-- Image / Video gallery section end -->

</main>

<?php get_footer(); ?>
<?php
/**
 *  WP projects custom page initially designed for Divi theme and EPOC Layout.
 */
?>

<style id="custom-categories-styles"><?php echo file_get_contents(__DIR__ . '/project-divi-style.css') ?></style>
<!-- Start custom archive page -->
<?php get_header(); ?>
<div id="cnrs-dm-front-loader-icon" style="display: none">
    <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/loader.svg') ?>
</div>
<div id="main-content">
    <div class="entry-content">
        <div class="et-l et-l--post">
            <div class="et_builder_inner_content et_pb_gutters3">
                <div class="et_pb_section et_pb_section_0 et_pb_with_background et_section_regular section_has_divider et_pb_bottom_divider">
                    <div class="et_pb_row et_pb_row_0">
                        <div class="et_pb_column et_pb_column_4_4 et_pb_column_0 et_pb_css_mix_blend_mode_passthrough et-last-child">
                            <div class="et_pb_module et_pb_text et_pb_text_0 et_pb_text_align_left et_pb_bg_layout_dark et_had_animation" style="">
                                <div class="et_pb_text_inner">
                                    <h1>
                                        <span><?php echo __('Projects', 'cnrs-data-manager') ?></span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="et_pb_row et_pb_row_1 et_pb_row_fullwidth et_pb_gutters1">
                        <div class="et_pb_column et_pb_column_4_4 et_pb_column_1  et_pb_css_mix_blend_mode_passthrough et-last-child et_pb_column_empty"></div>
                    </div>
                    <div class="et_pb_bottom_inside_divider" style="background-size: 2684px 271.4px;"></div>
                </div>
                <div class="et_pb_section et_pb_section_1 et_section_regular section_has_divider et_pb_bottom_divider">
                    <div class="et_pb_row et_pb_row_1">
                        <div class="et_pb_column et_pb_column_4_4 et_pb_column_2  et_pb_css_mix_blend_mode_passthrough et-last-child">
                            <div class="et_pb_module et_pb_text et_pb_text_2  et_pb_text_align_left et_pb_bg_layout_light">
                                <div class="et_pb_text_inner">
                                    <h3>EPOC</h3>
                                    <h2><?php echo __('Find all our projects', 'cnrs-data-manager') ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include_once(__DIR__ . '/filters.php') ?>
                    <div class="et_pb_row et_pb_row_3">
                        <div class="et_pb_column et_pb_column_4_4 et_pb_column_4  et_pb_css_mix_blend_mode_passthrough et-last-child">
                            <div class="et_pb_with_border et_pb_module et_pb_blog_0 et_pb_css_mix_blend_mode et_pb_blog_grid_wrapper et_pb_bg_layout_light et_had_animation">
                                <div class="et_pb_blog_grid clearfix ">
                                    <div class="et_pb_salvattore_content cnrs-dm-front-silent-container" data-columns="3">
                                        <?php if (have_posts()): ?>
                                            <?php while (have_posts()) : the_post(); ?>
                                                <?php include(__DIR__ . '/project-article.php') ?>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <p class="cnrs-dm-front-no-result"><?php echo __('Sorry, no posts matched your criteria.', 'cnrs-data-manager') ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php include_once(__DIR__ . '/pagination.php') ?>
                    <div class="et_pb_bottom_inside_divider" style="background-size: 2684px 200px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
<script id="custom-category-script"><?php echo file_get_contents(__DIR__ . '/divi-script.js') ?></script>
<!-- End custom archive page -->

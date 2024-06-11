<?php
$urls = [
    'Offres d\'emploi' => 'http://google.com',
    'Jobs' => 'http://google.com',
    'Stages' => 'http://google.com',
    'Internships' => 'http://google.com',
    'Sujets de Thèse' => 'http://google.com',
    'Thesis Topics' => 'http://google.com'
];
if (in_array($candidatingCatFr, $parents, true) || in_array($candidatingCatEn, $parents, true)): ?>
<div class="et_pb_row et_pb_row_5 et_pb_equal_columns">
    <div class="et_pb_column et_pb_column_3_5 et_pb_column_4  et_pb_css_mix_blend_mode_passthrough">
        <div class="et_pb_module et_pb_text et_pb_text_3  et_pb_text_align_left et_pb_bg_layout_light">
            <div class="et_pb_text_inner">
                <h2><?php echo __('Spontaneous application', 'cnrs-data-manager') ?></h2>
                <p>
                    <span style="font-weight: 400;"><?php echo __('Send us your CV, profile and cover letter directly.', 'cnrs-data-manager') ?></span>
                </p>
            </div>
        </div>
    </div>
    <div class="et_pb_column et_pb_column_2_5 et_pb_column_5  et_pb_css_mix_blend_mode_passthrough et-last-child">
        <div class="et_pb_button_module_wrapper et_pb_button_0_wrapper et_pb_button_alignment_center et_pb_module et_had_animation custom-category-candidate-btn" style="">
            <a class="et_pb_button et_pb_button_0 et_hover_enabled et_pb_bg_layout_light" target="_blank" href="<?php echo $urls[$name] ?>"><?php echo __('I postulate', 'cnrs-data-manager') ?></a>
        </div>
    </div>
</div>
<?php endif; ?>

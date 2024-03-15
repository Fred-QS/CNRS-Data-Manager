<?php if (empty($projects)): ?>
    <div id="cnrs-dm-front-projects-wrapper-no-result" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
        <p class="cnrs-dm-front-no-project"><?php echo __('No projects referenced for this team.', 'cnrs-data-manager') ?></p>
    </div>
<?php else: ?>
    <div id="cnrs-dm-front-projects-wrapper" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
        <?php foreach ($projects as $project): ?>
            <?php
            $image = get_the_post_thumbnail_url($project['id']);
            $image = $image !== false ? $image : '/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg';
            ?>
            <article class="cnrs-dm-front-project-container">
                <a class="cnrs-dm-front-project-image" href="<?php echo $project['url'] ?>" style="background-image: url(<?php echo $image ?>)">
                    <span></span>
                </a>
                <div class="cnrs-dm-front-project-title-container">
                    <a href="<?php echo $project['url'] ?>" class="cnrs-dm-front-project-title"><?php echo $project['title'] ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

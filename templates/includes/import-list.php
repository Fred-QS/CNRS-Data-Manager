<p class="cnrs-dm-label-like"><?php echo __('List of imported projects', 'cnrs-data-manager') ?></p>
<ul id="cnrs-dm-imported-list">
    <?php foreach ($posts as $p): ?>
        <li class="cnrs-dm-imported-item">
            <span class="cnrs-dm-imported-item-image" style="background-image: url(<?php echo $p['image'] ?>)"></span>
            <a href="<?php echo $p['url'] ?>" target="_blank" class="cnrs-dm-imported-item-info">
                <span><?php echo $p['title'] ?><?php echo $p['lang'] !== 'fr' ? ' (' . $p['lang'] . ')' : '' ?></span>
                <i><?php echo $p['excerpt'] ?></i>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

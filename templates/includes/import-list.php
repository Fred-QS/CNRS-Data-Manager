<p class="cnrs-dm-label-like"><?= __('List of imported projects', 'cnrs-data-manager') ?></p>
<ul id="cnrs-dm-imported-list">
    <?php foreach ($posts as $p): ?>
        <li class="cnrs-dm-imported-item">
            <span class="cnrs-dm-imported-item-image" style="background-image: url(<?= $p['image'] ?>)"></span>
            <a href="<?= $p['url'] ?>" target="_blank" class="cnrs-dm-imported-item-info">
                <span><?= $p['title'] ?></span>
                <i><?= $p['excerpt'] ?></i>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
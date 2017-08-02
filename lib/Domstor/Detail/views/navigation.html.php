<p class="prnxt">
    <?php if( $prev ): ?>
    <a class="domstor_link" href="<?= $prev_href ?>">&larr; <span><?= $prev_text ?></span></a>
    <?php endif; ?>

    <?php if( $next ): ?>
    <a class="domstor_link" href="<?= $next_href ?>"><span><?= $next_text ?></span> &rarr;</a>
    <?php endif; ?>
</p>
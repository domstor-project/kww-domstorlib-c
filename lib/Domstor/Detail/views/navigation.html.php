<p class="prnxt">
    <?php if( $prev ): ?>
    <a class="domstor_link" href="<?= $prev_href ?>">&larr; <?= $prev_text ?></a>
    <?php endif; ?>

    <?php if( $next ): ?>
    <a class="domstor_link" href="<?= $next_href ?>"><?= $next_text ?> &rarr;</a>
    <?php endif; ?>
</p>


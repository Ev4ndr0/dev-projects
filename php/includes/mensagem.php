<?php if (isset($msg) and $msg) : ?>
    <div class="<?= $msg['estilo'] ?>">
        <?= $msg['mensagem'] ?>
    </div>
<?php endif; ?>
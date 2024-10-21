<?php // pr($votacao) ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <?php if (isset($usuario)): ?>
        <?php if ($usuario['role'] == 'relator' || $usuario['role'] == 'admin'): ?>
            <?php echo $this->Html->link('Editar', 'edit/' . $votacao['Votacao']['id'], ['class' => 'btn btn-info']); ?>
            <?php echo " "; ?>
            <?php // echo $this->Html->link('TR', '/Resolucaos/view/' . $votacao['Votacao']['resolucao_id']); ?>
            <?php echo $this->Html->link('Exluir', 'delete/' . $votacao['Votacao']['id'], array('confirm' => __('Está seguro que quer excluir este registro'), 'class' => 'btn btn-danger')); ?>
        <?php endif; ?>
    <?php endif; ?>
</nav>

<dl class="row">
    <?php if ($votacao['Votacao']['resultado'] != 'inclusão'): ?>

        <dt class="col-3">
            Texto em votação
        </dt>
        <dd class="col-9">
            <?php echo $votacao['Item']['texto'] ?>
        </dd>

    <?php endif; ?>

    <dt class="col-3">Grupo</dt>
    <dd class="col-9">
        <?php
        echo $this->Html->link($votacao['Votacao']['grupo'], '/Votacaos/index?grupo=' . $votacao['Votacao']['grupo'] . '&evento_id=' . $votacao['Votacao']['evento_id']);
        ?>
    </dd>

    <dt class="col-3">TR</dt>
    <dd class="col-9">
        <?php
        if (isset($votacao['Votacao']['grupo'])):
            echo $this->Html->link($votacao['Votacao']['tr'], '/Items/index?tr=' . $votacao['Votacao']['tr'] . '&evento_id=' . $votacao['Votacao']['evento_id']);
        else:
            echo $votacao['Votacao']['tr'];
        endif;
        ?>
    </dd>

    <dt class="col-3">Item</dt>
    <dd class="col-9"><?php echo $votacao['Votacao']['item']; ?></dd>

    <dt class="col-3">Supressão</dt>
    <dd class="col-9"><?php echo $votacao['Votacao']['tr_suprimida'] == 0 ? 'Não' : 'Sim'; ?></dd>

    <dt class="col-3">Resultado</dt>
    <dd class="col-9"><?php echo $votacao['Votacao']['resultado']; ?></dd>

    <dt class="col-3">Votação</dt>
    <dd class="col-9">
        <?php
        $votos = explode('/', $votacao['Votacao']['votacao']);
        $total = $votos[0] + $votos[1] + $votos[2];
        $terco = $total / 3;
        $minoritaria = ($votos[1] >= $terco ? "minoritária" : '');
        echo "<p>" . $votacao['Votacao']['votacao'] . " || Total " . $total . ' || 1/3 = ' . round($terco) . "  " . "<span class='bg-danger text-white' style='text-transform: uppercase; animation-duration: 3s';>" . $minoritaria . "</span></p>";
        ?>
    </dd>

    <?php if ($votacao['Votacao']['resultado'] == 'modificada' || $votacao['Votacao']['resultado'] == 'inclusão'): ?>
        <dt class="col-3">Modificação ou inclusão</dt>
        <dd class="col-9">
            <?php
            echo $votacao['Votacao']['item_modificada'];
            ?>
        </dd>
    <?php endif; ?>
</dl>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <?php if (isset($usuario)): ?>
        <?php if ($usuario['role'] == 'relator' || $usuario['role'] == 'admin'): ?>
            <?php echo $this->Html->link('Nova votação', '/Items/index?tr=' . $votacao['Votacao']['tr'] . '&evento_id=' . $votacao['Votacao']['evento_id'], ['class' => 'btn btn-info']); ?>
        <?php endif; ?>
    <?php endif; ?>
</nav>

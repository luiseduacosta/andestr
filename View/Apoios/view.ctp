<?php
// pr($apoio['Apoio']['texto']);
?>

<div class="container">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerApoios" aria-controls="navbarTogglerApoios" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav collapse navbar-collapse" id="navbarTogglerApoios">
            <?php if (isset($usuario)): ?>
                <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>

                    <li class='nav-item'>
                        <?php echo $this->Html->link(__('Evento'), ['controller' => 'Eventos', 'action' => 'view', $apoio['Apoio']['evento_id']], ['class' => 'nav-link']); ?>
                    </li>
                    <li class='nav-item'>
                        <?php echo $this->Html->link(__('Listar Textos'), ['action' => 'index', '?' => ['evento_id' => $apoio['Apoio']['evento_id']]], ['class' => 'nav-link']); ?>
                    </li>
                    <li class='nav-item'>
                        <?php echo $this->Html->link(__('Editar Texto'), ['action' => 'edit', $apoio['Apoio']['id']], ['class' => 'nav-link']); ?>
                    </li>
                    <li class='nav-item'>
                        <?php echo $this->Form->postLink(__('Excluir Texto'), ['action' => 'delete', $apoio['Apoio']['id']], ['confirm' => __('Está seguro que quer excluir este registro # %s?', $apoio['Apoio']['id']), 'class' => 'nav-link']); ?>
                    </li>
                    <li class='nav-item'>
                        <?php echo $this->Html->link(__('Novo Texto'), ['action' => 'add', '?' => ['evento_id' => $apoio['Apoio']['evento_id']]], ['class' => 'nav-link']); ?>
                    </li>
                    <li class='nav-item'>
                        <?php echo $this->Html->link(__('Inserir TR item'), ['controller' => 'Items', 'action' => 'add', '?' => ['evento_id' => $apoio['Apoio']['evento_id'], 'apoio_id' => $apoio['Apoio']['id']]], ['class' => 'nav-link']); ?>
                    </li>
                <?php else: ?>
                    <li class='nav-item'>
                        <?php echo $this->Html->link(__('Evento'), ['controller' => 'Eventos', 'action' => 'view', $apoio['Apoio']['evento_id']], ['class' => 'nav-link']); ?>
                    </li>
                    <li class='nav-item'>
                        <?php echo $this->Html->link(__('Listar Textos'), ['action' => 'index', '?' => ['evento_id' => $apoio['Apoio']['id']]], ['class' => 'nav-link']); ?>
                    </li>

                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </nav>

    <h1 class="h3"><?php echo __('Texto de apoio: ' . $apoio['Apoio']['numero_texto']); ?></h1>
    <dl class="row">
        <dt class="col-sm-3"><?php echo __('Id'); ?></dt>
        <dd class="col-sm-9">
            <?php echo h($apoio['Apoio']['id']); ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('Evento'); ?></dt>
        <dd class="col-sm-9">
            <?php echo h($apoio['Evento']['nome']); ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('Caderno'); ?></dt>
        <dd class="col-sm-9">
            <?php echo h($apoio['Apoio']['caderno']); ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('Texto número: '); ?></dt>
        <dd class="col-sm-9">
            <?php echo h($apoio['Apoio']['numero_texto']); ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('Tema'); ?></dt>
        <dd class="col-sm-9">
            <?php echo h($apoio['Apoio']['tema']); ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('GT'); ?></dt>
        <dd class="col-sm-9">
            <?php echo h($apoio['Gt']['sigla']); ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('Titulo'); ?></dt>
        <dd class="col-sm-9">
            <?php echo h($apoio['Apoio']['titulo']); ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('Autor(es)'); ?></dt>
        <dd class="col-sm-9">
            <?php
            echo $this->Text->truncate($apoio['Apoio']['autor'], 200, ['ellipsis' => ' ...', 'exact' => false]);
            ?>
            &nbsp;
        </dd>

        <dt class="col-sm-3"><?php echo __('Texto de apoio'); ?></dt>
        <dd class="col-sm-9">
            <?php
            echo $this->Text->truncate($apoio['Apoio']['texto'], 120, ['ellipsis' => $this->Html->link(' ...', ['action' => 'apoiocompleto', $apoio['Apoio']['id']]), 'exact' => true]);
            ?>
            &nbsp;
        </dd>
    </dl>

    <div class="row">
        <?php if (count($apoio['Item']) > 0): ?>
            <h3><?php echo __('TR: ' . $apoio['Apoio']['numero_texto']); ?></h3>

            <?php foreach ($apoio['Item'] as $c_apoio): ?>
                <?php // pr($c_apoio); ?>
                <dl class="row">
                    <dt class="col-sm-1">
                        <?php echo $this->Html->link($c_apoio['item'], ['controller' => 'Items', 'action' => 'view', $c_apoio['id']]); ?>
                    </dt>
                    <dd class="col-sm-9">
                        <?php echo trim($c_apoio['texto']); ?>
                    </dd>
                </dl>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

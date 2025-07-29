<?php
// pr($apoio);
?>

<div>
<h3><?= $this->Html->link($apoio['Evento']['nome'] . ', ' . $apoio['Evento']['data'] . ', ' . $apoio['Evento']['local'], ['controller' => 'Eventos', 'action' => 'view', $apoio['Evento']['id']]) ?>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto">
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li class="nav-item"><?php echo $this->Html->link(__('Editar este texto'), ['action' => 'edit', $apoio['Apoio']['id']], ['class' => 'nav-link']); ?> </li>
            <li class="nav-item"><?php echo $this->Form->postLink(__('Excluir este texto'), ['action' => 'delete', $apoio['Apoio']['id']], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $apoio['Apoio']['id']), 'class' => 'nav-link']); ?> </li>
        <?php endif; ?>
        <li class="nav-item"><?php echo $this->Html->link(__('Textos de apoio'), ['action' => 'index', '?' => ['evento_id' => $apoio['Evento']['id']]], ['class' => 'nav-link']); ?> </li>
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li class="nav-item"><?php echo $this->Html->link(__('Novo texto de apoio'), ['action' => 'add', '?' => ['evento_id' => $apoio['Evento']['id']]], ['class' => 'nav-link']); ?> </li>
        <?php endif; ?>
        <li class="nav-item"><?php echo $this->Html->link(__('TRs'), ['controller' => 'items', 'action' => 'index', '?' => ['evento_id' => $apoio['Evento']['id']]], ['class' => 'nav-link']); ?> </li>
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li class="nav-item"><?php echo $this->Html->link(__('Inserir items'), ['controller' => 'items', 'action' => 'add', '?' => ['evento_id' => $apoio['Evento']['id'], 'apoio_id' => $apoio['Apoio']['id']]], ['class' => 'nav-link']); ?> </li>
        <?php endif; ?>
    </ul>
</nav>

<div class="apoios view">
    <h2><?php echo __('Texto de Apoio'); ?></h2>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['id']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Caderno'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['caderno']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Texto número: '); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['numero_texto']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Tema'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['tema']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('GT'); ?></dt>
        <dd>
            <?php echo h($apoio['Gt']['sigla']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Titulo'); ?></dt>
        <dd>
            <?php echo strip_tags($apoio['Apoio']['titulo']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Autor(es)'); ?></dt>
        <dd>
            <?php
            echo strip_tags($apoio['Apoio']['autor']);
            ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Texto de apoio'); ?></dt>
        <dd>
            <?php
            echo $apoio['Apoio']['texto'];
            ?>
            &nbsp;
        </dd>
    </dl>

</div>

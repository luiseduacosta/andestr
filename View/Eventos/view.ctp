<?php
// pr($evento);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <ul class='navbar-nav mr-auto'>
        <a class='navbar-brand'><?php echo __('Ações'); ?></a>

        <?php if (isset($usuario) && isset($evento)): ?>
            <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                <li class='nav-item'>
                    <?php echo $this->Html->link(__('Editar'), ['action' => 'edit', $evento['Evento']['id']], ['class' => 'nav-link']); ?>
                </li>
                <li class='nav-item'>
                    <?php echo $this->Form->postLink(__(' Excluir'), ['action' => 'delete', $evento['Evento']['id']], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $evento['Evento']['id']), 'class' => 'nav-link']); ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <li class='nav-item'>
            <?php echo $this->Html->link(__('Eventos'), ['action' => 'index'], ['class' => 'nav-link']); ?>
        </li>

        <?php if (isset($usuario)): ?>
            <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                <li class='nav-item'>
                    <?php echo $this->Html->link(__('Novo evento'), ['action' => 'add'], ['class' => 'nav-link']); ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?php if (isset($evento)): ?>
            <li class='nav-item'>
                <?php echo $this->Html->link(__('Textos de apoio'), ['controller' => 'apoios', 'action' => 'index', '?' => ['evento_id' => $evento['Evento']['id']]], ['class' => 'nav-link']); ?>
            </li>
        <?php endif ?>

        <?php if (isset($usuario) && isset($evento)): ?>
            <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                <li class='nav-item'>
                    <?php echo $this->Html->link(__('Inserir texto de apoio'), ['controller' => 'apoios', 'action' => 'add', '?' => ['evento_id' => $evento['Evento']['id']]], ['class' => 'nav-link']); ?>
                </li>
            <?php endif ?>
        <?php endif ?>

        <?php if (isset($evento)): ?>
            <li class='nav-item'>
                <?php echo $this->Html->link(__('TRs'), ['controller' => 'items', 'action' => 'index', '?' => ['evento_id' => $evento['Evento']['id']]], ['class' => 'nav-link']); ?>
            </li>
        <?php endif ?>

    </ul>
</nav>

<h2 class='h2'><?php echo $evento['Evento']['nome'], ' - ', $evento['Evento']['data'], ' - ', $evento['Evento']['local']; ?></h2>

<div class="r">
    <h2 class='h2'><?php echo __('Textos de apoio'); ?></h2>
    <?php if (!empty($evento['Apoio'])): ?>
        <table cellpadding="0" cellspacing="0" class="table table-hover table-striped table-responsive">
            <thead class='thead-light'>
                <tr>
                    <th><?php echo __('Id'); ?></th>
                    <th><?php echo __('Caderno'); ?></th>
                    <th><?php echo __('Texto'); ?></th>
                    <th><?php echo __('Tema'); ?></th>
                    <th><?php echo __('Gt'); ?></th>
                    <th><?php echo __('Titulo'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evento['Apoio'] as $apoio): ?>
                    <tr>
                        <td><?php echo $apoio['id']; ?></td>
                        <td><?php echo $apoio['caderno']; ?></td>
                        <td><?php echo $apoio['numero_texto']; ?></td>
                        <td><?php echo $apoio['tema']; ?></td>
                        <td><?php echo $apoio['Gt']['sigla']; ?></td>
                        <td><?php echo $this->Html->link($apoio['titulo'], ['controller' => 'apoios', 'action' => 'view', $apoio['id']]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>
<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <ul class='navbar-nav mr-auto'>
        <a class='navbar-brand'><?php echo __('Ações'); ?></a>
        <?php if (isset($usuario)): ?>
            <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                <li class='nav-item'><?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $evento['Evento']['id']), ['class' => 'nav-link']); ?> </li>
                <li class='nav-item'><?php echo $this->Form->postLink(__(' Excluir'), array('action' => 'delete', $evento['Evento']['id']), ['confirm' => __('Are you sure you want to delete # %s?', $evento['Evento']['id']), 'class' => 'nav-link']); ?> </li>
            <?php endif ?>
        <?php endif ?>

        <li class='nav-item'><?php echo $this->Html->link(__('Eventos'), array('action' => 'index'), ['class' => 'nav-link']); ?> </li>

        <?php if (isset($usuario)): ?>
            <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                <li class='nav-item'><?php echo $this->Html->link(__('Novo evento'), array('action' => 'add'), ['class' => 'nav-link']); ?> </li>
            <?php endif ?>
        <?php endif ?>

        <li class='nav-item'><?php echo $this->Html->link(__('Textos de apoio'), array('controller' => 'apoios', 'action' => 'index'), ['class' => 'nav-link']); ?> </li>

        <?php if (isset($usuario)): ?>
            <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                <li class='nav-item'><?php echo $this->Html->link(__('Inserir texto de apoio'), array('controller' => 'apoios', 'action' => 'add'), ['class' => 'nav-link']); ?> </li>
            <?php endif ?>
        <?php endif ?>
    </ul>
</nav>

<h2 class='h2'><?php echo __('Evento'); ?></h2>

<dl class='row'>
    <dt class='col-3'><?php echo __('Id'); ?></dt>
    <dd class='col-9'>
        <?php echo h($evento['Evento']['id']); ?>
        &nbsp;
    </dd>
    <dt class='col-3'><?php echo __('Evento'); ?></dt>
    <dd class='col-9'>
        <?php echo h($evento['Evento']['evento']); ?>
        &nbsp;
    </dd>
    <dt class='col-3'><?php echo __('Ordem'); ?></dt>
    <dd class='col-9'>
        <?php echo h($evento['Evento']['ordem']); ?>
        &nbsp;
    </dd>
    <dt class='col-3'><?php echo __('Data'); ?></dt>
    <dd class='col-9'>
        <?php echo h($evento['Evento']['data']); ?>
        &nbsp;
    </dd>
    <dt class='col-3'><?php echo __('Local'); ?></dt>
    <dd class='col-9'>
        <?php echo h($evento['Evento']['local']); ?>
        &nbsp;
    </dd>
</dl>

<div class="related">
    <h2 class='h2'><?php echo __('Textos para o evento'); ?></h3>
        <?php if (!empty($evento['Apoio'])): ?>
            <table cellpadding = "0" cellspacing = "0" class="table table-hover table-striped table-responsive">
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
                            <td><?php echo $apoio['gt']; ?></td>
                            <td><?php echo $this->Html->link($apoio['titulo'], ['controller' => 'apoios', 'action' => 'view', $apoio['id']]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

</div>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav">
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li class="nav-item"><?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $user['User']['id']], ['confirm' => __('Are you sure you want to delete # %s?', $user['User']['id']), 'class' => 'btn btn-danger']); ?> </li>
            <li class="nav-item"><?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $user['User']['id']), ['class' => 'btn btn-light']); ?> </li>
        <?php endif; ?>
        <li class="nav-item"><?php echo $this->Html->link(__('Listar'), array('action' => 'index'), ['class' => 'btn btn-light']); ?> </li>
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li class="nav-item"><?php echo $this->Html->link(__('Inserir'), array('action' => 'add'), ['class' => 'btn btn-light']); ?> </li>
        <?php endif; ?>
        <li class="nav-item"><?php echo $this->Html->link(__('TRs'), array('controller' => 'items', 'action' => 'index'), ['class' => 'btn btn-light']); ?> </li>
    </ul>
</nav>


<h2 class="h2"><?php echo __('Usuário'); ?></h2>

<dl class="row">

    <dt class="col-3"><?php echo __('Id'); ?></dt>
    <dd class="col-9">
        <?php echo h($user['User']['id']); ?>
        &nbsp;
    </dd>

    <dt class="col-3"><?php echo __('Usuário'); ?></dt>
    <dd class="col-9">
        <?php echo h($user['User']['username']); ?>
        &nbsp;
    </dd>

    <dt class="col-3"><?php echo __('Papel'); ?></dt>
    <dd class="col-9">
        <?php echo h($user['User']['role']); ?>
        &nbsp;
    </dd>

    <dt class="col-3"><?php echo __('Criado'); ?></dt>
    <dd class="col-9">
        <?php echo h($user['User']['created']); ?>
        &nbsp;
    </dd>

    <dt class="col-3"><?php echo __('Modificado'); ?></dt>
    <dd class="col-9">
        <?php echo h($user['User']['modified']); ?>
        &nbsp;
    </dd>
</dl>

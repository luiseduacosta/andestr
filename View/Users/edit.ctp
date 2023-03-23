
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav">
        <li class="nav-item active"><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('User.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('User.id')), 'class' => 'btn btn-danger')); ?></li>
        <li class="nav-item"><?php echo $this->Html->link(__('List Users'), array('action' => 'index'), ['class' => 'btn btn-light']); ?></li>
        <li class="nav-item"><?php echo $this->Html->link(__('List Votacaos'), array('controller' => 'votacaos', 'action' => 'index'), ['class' => 'btn btn-light']); ?> </li>
        <li class="nav-item"><?php echo $this->Html->link(__('New Votacao'), array('controller' => 'votacaos', 'action' => 'add'), ['class' => 'btn btn-light']); ?> </li>
    </ul>
</nav>

<div class="container">
    <?php
    echo $this->Form->create('User', [
        'class' => 'form-horizontal',
        'role' => 'form',
        'inputDefaults' => [
            'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
            'div' => ['class' => 'form-group row'],
            'label' => ['class' => 'col-2 control-label'],
            'between' => "<div class = 'col-10'>",
            'class' => ['form-control'],
            'after' => "</div>",
            'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
        ]
    ]);
    ?>
    <fieldset>
        <legend><?php echo __('Editar usuário'); ?></legend>
        <?php
        echo $this->Form->input('id', ['type' => 'hidden']);
        echo $this->Form->input('username', ['label' => ['text' => 'Usuário', 'class' => 'col-2 control-label']]);
        echo $this->Form->input('password', ['label' => ['text' => 'Senha', 'class' => 'col-2 control-label']]);
        echo $this->Form->input('role', ['label' => ['text' => 'Papel', 'class' => 'col-2 control-label'],
            'options' => ['relator' => 'Relator', 'editor' => 'Editor']
        ]);
        ?>
    </fieldset>
    <?php echo $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']); ?>
    <?php echo $this->Form->end(); ?>
</div>

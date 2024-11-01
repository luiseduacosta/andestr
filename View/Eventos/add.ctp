<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto">
    <a class='navbar-brand'><?php echo __('Ações'); ?></a>
        <li class='nav-item'><?php echo $this->Html->link(__('Listar'), array('action' => 'index'), ['class' => 'nav-link']); ?></li>
        <li class='nav-item'><?php echo $this->Html->link(__('Listar textos de apoio'), array('controller' => 'apoios', 'action' => 'index'), ['class' => 'nav-link']); ?> </li>
        <li class='nav-item'><?php echo $this->Html->link(__('Novo texto de apoio'), array('controller' => 'apoios', 'action' => 'add'), ['class' => 'nav-link']); ?> </li>
    </ul>
</nav>

<div class="form">
    <?php
    echo $this->Form->create('Evento', [
        'class' => 'form-horizontal',
        'role' => 'form',
        'inputDefaults' => [
            'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
            'div' => ['class' => 'form-group row'],
            'label' => ['class' => 'col-2'],
            'between' => "<div class = 'col-8'>",
            'class' => ['form-control'],
            'after' => '</div>',
            'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
        ]
    ]);
    ?>
    <fieldset>
        <legend><?php echo __('Acrescentar um evento'); ?></legend>
        <?php
        echo $this->Form->input('nome', ['label' => ['text' => 'Evento']]);
        echo $this->Form->input('ordem');
        echo $this->Form->input('data');
        echo $this->Form->input('local');
        ?>
    </fieldset>
    <div class='row justify-content-left'>
        <div class='col-auto'>
            <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php
if ($this->Session->check('Auth.User')) {
    $usuario = $this->Session->read('Auth.User');
} else {
    echo 'Visitante.';
    exit;
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto">
        <a class='navbar-brand'><?php echo __('Ações'); ?></a>
        <li class='nav-item'>
            <?php echo $this->Html->link(__('Listar eventos'), array('action' => 'index'), ['class' => 'nav-link']); ?>
        </li>
        <?php
        if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
            ?>
            <li class='nav-item'>
                <?php echo $this->Html->link(__('Novo evento'), array('controller' => 'eventos', 'action' => 'add'), ['class' => 'nav-link']); ?>
            </li>
            <?php
        endif;
        ?>
    </ul>
</nav>

<?php if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')): ?>
    <div class="container">
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
            echo $this->Form->input('ordem', ['value' => $evento['Evento']['ordem'] + 1, 'required']);
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
<?php endif; ?>
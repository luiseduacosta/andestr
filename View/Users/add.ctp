<div class="container">
    <?php
    echo $this->Form->create('User', [
        'class' => 'form-horizontal',
        'role' => 'form',
        'inputDefaults' => [
            'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
            'div' => ['class' => 'form-group row'],
            'label' => ['class' => 'col-2 control-label'],
            'between' => "<div class = 'col-8'>",
            'class' => ['form-control'],
            'after' => "</div>",
            'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
        ]
    ]);
    ?>
    <fieldset>
        <legend><?php echo __('Adicionar usuário'); ?></legend>
        <?php
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

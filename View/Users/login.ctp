<div class="container">
    <?php echo $this->Flash->render('auth'); ?>
    <?php
    echo $this->Form->create('User', [
        'class' => 'form-horizontal',
        'role' => 'form',
        'inputDefaults' => [
            'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
            'div' => ['class' => 'form-group row'],
            'label' => ['class' => 'col-4 control-label'],
            'between' => "<div class = 'col-8'>",
            'class' => ['form-control'],
            'after' => "</div>",
            'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
        ]
    ]);
    ?>
    <fieldset>
        <legend>
            <?php echo __('Ingresse com seu nome de usuário e senha'); ?>
        </legend>
        <?php
        echo $this->Form->input('username', ['label' => ['text' => 'Usuário', 'class' => 'col-2 control-label']]);
        echo $this->Form->input('password', ['label' => ['text' => 'Senha', 'class' => 'col-2 control-label']]);
        ?>
    </fieldset>
    <div class='row justify-content-left'>
        <div class='col-auto'>
            <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?php // pr($usuario); ?>
<?php // pr($this->request->data); ?>
<?php // die(); ?>
<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($this->data);                  ?>

<?php
echo $this->Form->create('Votacao', [
    'class' => 'form-horizontal',
    'role' => 'form',
    'inputDefaults' => [
        'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
        'div' => ['class' => 'form-group row'],
        'label' => ['class' => 'col-4'],
        'between' => "<div class = 'col-8'>",
        'class' => ['form-control'],
        'after' => "</div>",
        'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
    ]
]);
?>
<fieldset>
    <legend><?php echo __('Editar Votação'); ?></legend>
    <?php
    echo $this->Form->input('id', array('type' => 'hidden'));
    if (isset($usuario)):
        if ($usuario['role'] == 'relator'):
            echo $this->Form->input('grupo', ['value' => $usuario['grupo'], 'type' => 'text', 'readonly']);
            echo $this->Form->input('tr', ['label' => ['text' => "TR", 'class' => 'col-4'], 'maxlength' => 2, 'readonly']);

        elseif ($usuario['role'] == 'admin'):

            echo $this->Form->input('grupo', ['maxlength' => "2", 'placeholder' => 'Digite um número']);
            echo $this->Form->input('tr', ['label' => ['text' => "TR", 'class' => 'col-4'], 'maxlength' => 2]);
        endif;
    endif;
    ?>
</fieldset>

<fieldset>
    <legend>Supresão da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_suprimida', array(
        'label' => ['text' => 'Suprimir TR', 'class' => 'col-4'],
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')));
    ?>

    <legend>Votação de cada item da TR</legend>

    <?php
    echo $this->Form->input('item', ['maxlength' => 8]);

    echo $this->Form->input('resultado', [
        'type' => 'select',
        'options' => ['aprovada' => 'Aprovado', 'modificada' => 'Modificado', 'suprimida' => 'Suprimido', 'inclusão' => 'Inclusão de novo item', 'minoritária' => 'Proposta minoritária (1/3)', 'outra' => 'Outra votação em observações']
    ]);
    echo $this->Form->input('votacao', ['label' => ['text' => 'Resultado de votação: favoráveis / contrários / abstenções', 'class' => 'col-4']]);
    // echo $this->Html->link("Verificador de diferenças entre textos", "https://editor.mergely.com/");
    echo $this->Form->input('item_modificada', ['label' => ['text' => 'Digitar o texto modificado, a inclusão do novo item ou o texto minoritário.', 'class' => 'col-4'], 'class' => 'ckeditor']);
    ?>

    <legend>Aprovação da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_aprovada', array(
        'label' => ['text' => 'Votação da TR como um todo. É para aprovar os items que não foram destacados. Selecionar "Aprovada" na caixa de seleção "Resolução" anterior. Colocar a votação no campo "Resultado da votação" deste formulário.', 'class' => 'col-4'],
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')
    ));

    echo $this->Form->input('observacoes', ['label' => ['text' => 'Observações', 'class' => 'col-4'], 'class' => 'ckeditor']);
    ?>
</fieldset>

<div class='row justify-content-left'>
    <div class='col-auto'>
        <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

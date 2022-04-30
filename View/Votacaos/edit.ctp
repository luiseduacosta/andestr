<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($this->data);             ?>

<?php echo $this->Form->create('Votacao'); ?>
<fieldset>
    <legend><?php echo __('Editar Votação'); ?></legend>
    <?php
    echo $this->Form->input('id', array('type' => 'hidden'));
    if (isset($usuario)):
        if ($usuarioAutorizado['role'] === 'relator'):
            echo $this->Form->input('grupo', ['value' => $usuario['grupo'], 'type' => 'text', 'readonly']);
            echo $this->Form->input('tr', ['label' => "TR", 'maxlength' => 2, 'readonly']);

        elseif ($usuarioAutorizado['role'] === 'admin'):

            echo $this->Form->input('grupo', ['maxlength' => "2", 'placeholder' => 'Digite um número']);
            echo $this->Form->input('tr', ['label' => "TR", 'maxlength' => 2]);
        endif;
    endif;
    ?>
</fieldset>

<fieldset>
    <legend>Supresão da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_suprimida', array(
        'label' => 'Suprimir TR',
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')));
    ?>

    <legend>Votação de cada item da TR</legend>

    <?php
    // echo $this->Form->input('numero_item');
    echo $this->Form->input('item', ['maxlength' => 5]);

    echo $this->Form->input('resultado', array(
        'type' => 'select',
        'options' => array('aprovada' => 'Aprovado', 'modificada' => 'Modificado', 'suprimida' => 'Suprimido', 'inclusão' => 'Inclusão de novo item', 'minoritária' => 'Proposta minoritária (1/3)', 'outra' => 'Outra votação em observações')
    ));
    echo $this->Form->input('votacao', array('label' => 'Resultado de votação: favoráveis / contrários / abstenções'));
    echo $this->Form->input('item_modificada', array('label' => 'Digitar o texto modificado, a inclusão do novo item ou o texto minoritário', 'class' => 'ckeditor'));
    // echo $this->Form->input('remitido');
    // echo $this->Form->input('inclusao');
    ?>

    <legend>Aprovação da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_aprovada', array(
        'label' => 'Votação da TR como um todo. É para aprovar os items que não foram destacados. Selecionar "Aprovada" na caixa de seleção "Resolução" anterior. Colocar a votação no campo "Resultado da votação" deste formulário. ',
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')
    ));

    echo $this->Form->input('observacoes', array('label' => 'Observações', 'class' => 'ckeditor'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Salvar')); ?>

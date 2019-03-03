<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($this->data);      ?>

<?php echo $this->Form->create('Votacao'); ?>
<fieldset>
    <legend><?php echo __('Editar Votação'); ?></legend>
    <?php
    echo $this->Form->input('id', array('type' => 'hidden'));
    if (isset($usuario)):
        if ($usuarioAutorizado['role'] === 'relator'):
            echo $this->Form->input('grupo', array('value' => $usuario['grupo'], 'type' => 'hidden'));

        elseif ($usuarioAutorizado['role'] === 'admin'):

            echo $this->Form->input('grupo');
        endif;
        // echo $this->Form->input('grupo');
    endif;
    echo $this->Form->input('tr', array('label' => "TR"));
    echo $this->Form->input('tr_suprimida', array(
        'label' => 'Suprimir TR',
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')));
    ?>
    <legend><?php // echo 'Item: ' . $this->data['Votacao']['item_id'];     ?></legend>
    <?php
    // echo $this->Form->input('numero_item');
    echo $this->Form->input('item');
    
    echo $this->Form->input('resultado', array(
        'type' => 'select',
        'options' => array('aprovada' => 'Aprovado', 'modificada' => 'Modificado', 'suprimida' => 'Suprimido', 'inclusão' => 'Inclusão de novo item', 'minoritária' => 'Proposta minoritária (1/3)', 'outra' => 'Outra votação em observações')
    ));
    echo $this->Form->input('votacao', array('label' => 'Resultado de votação: favoráveis / contrários / abstenções'));
    echo $this->Form->input('item_modificada', array('label' => 'Digitar o texto modificado, a inclusão do novo item ou o texto minoritário'));
    // echo $this->Form->input('remitido');
    // echo $this->Form->input('inclusao');

    echo $this->Form->input('tr_aprovada', array(
        'label' => 'Votação da TR como um todo. É para aprovar os items que não foram destacados. Selecionar "Aprovada" na caixa de seleção "Resolução" anterior. Colocar a votação no campo "Votação" deste formulário. ',
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim'),
        'class' => 'ckeditor'
    ));

    echo $this->Form->input('observacoes', array('label' => 'Observações', 'class' => 'ckeditor'));
    ?>
</fieldset>
<?php echo $this->Form->end(__('Salvar')); ?>

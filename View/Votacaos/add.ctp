<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($this->data);                  ?>
<?php // pr($item);                  ?>

<?php if (isset($item)): ?>
    <dl>
        <dt><?php echo __('Item'); ?></dt>
        <dd><?php echo $item['Item']['item']; ?></dd>

        <dt><?php echo __('Texto'); ?></dt>
        <dd><?php echo $item['Item']['texto']; ?></dd>
    </dl>
<?php endif; ?>

<?php echo $this->Form->create('Votacao'); ?>

<fieldset>    
    <?php if (isset($usuario)): ?>
        <?php if ($usuario['papel'] == 'relator'): ?>
            <legend><?php echo __('Grupo ' . $usuario['grupo'] . '. Inserir votação'); ?></legend>
        <?php else: ?>
            <legend><?php echo __('Inserir votação'); ?></legend>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    echo $this->Form->input('id', array('type' => 'hidden'));

    if (isset($usuario)):
        // pr($usuario);            
        if ($usuario['papel'] == 'relator'):
            echo $this->Form->input('grupo', array('type' => 'hidden', 'value' => $usuario['grupo']));
        elseif ($usuario['papel'] == 'admin'):
            if (isset($grupo)):
                // echo "1 *";
                echo $this->Form->input('grupo', array('label' => 'Grupo. Digite até dois carateres numéricos', 'value' => $grupo));
            else:
                // echo "2 *";
                echo $this->Form->input('grupo', array('label' => 'Grupo. Digite um ou até dois carateres numéricos', 'placeholder' => '0', 'maxlength' => 2));
            endif;
        endif;
    else:
    // echo $this->Form->input('grupo', array('label' => 'Grupo. Digite até dois carateres numéricos'));
    endif;

    if (isset($item)):
        echo $this->Form->input('tr', array('label' => 'TR (digite até dois carateres numéricos)', 'value' => substr($item['Item']['item'], 0, 2)));
    elseif (isset($tr)):
        echo $this->Form->input('tr', array('label' => 'TR (digite até dois carateres numéricos)', 'value' => $tr));
    else:
        echo $this->Form->input('tr', array('label' => 'TR (digite até dois carateres numéricos)', 'placeholder' => '00'));
    endif;
    ?>
            
    <legend>Supresão da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_suprimida', array(
        'label' => 'Votação de supresão da TR como um todo. Selecionar "Suprimida" na caixa de seleção "Resolução". Colocar a votação no campo "Votação" deste formulário.',
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')
    ));
    ?>
    
    <legend>Votação de cada item da TR</legend>
    
    <legend><?php echo 'Item '; ?></legend>
    <?php
    if (isset($item)):
        echo $this->Form->input('item', array('label' => 'Item. Formato nn.nn Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'value' => substr($item['Item']['item'], 0, 5)));
    elseif (isset($tr)):
        echo $this->Form->input('item', array('label' => 'Item. Formato nn.nn. Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'value' => $tr . '.99'));
    else:
        echo $this->Form->input('item', array('label' => 'Item. Formato nn.nn. Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'placeholder' => '00.00'));
    endif;
    echo $this->Form->input('resultado', array(
        'label' => 'Resolução',
        'type' => 'select',
        'empty' => 'Selecione',
        'options' => array('aprovada' => 'Aprovada sem alterações',
            'modificada' => 'Aprovada com modificações',
            'suprimida' => 'Suprimida',
            'inclusão' => 'Inclusão de novo item',
            'minoritária' => 'Proposta minoritária (1/3)',
            'remitida' => 'Remitida para outro tema e/ou TR. Especificar em observações',
            'outra' => 'Outra votação especificar em observações')
    ));
    if (isset($votacao)):
        echo $this->Form->input('votacao', array(
            'label' => 'Votação. Digite nesta ordem: favoráveis / contrários / abstenções',
            'value' => str_replace('-', '/', $votacao)));
    else:
        echo $this->Form->input('votacao', array(
            'label' => 'Votação. Digite nesta ordem: favoráveis / contrários / abstenções',
            'placeholder' => '00/00/00'
        ));
    endif;
    echo $this->Form->input('item_modificada', array('label' => 'Item modificado, novo item, item remetido para outro tema e/ou TR ou item aprovado como minoritário', 'class' => 'ckeditor'));
    ?>

    <legend>Aprovação da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_aprovada', array(
        'label' => 'Votação da TR como um todo. É para aprovar os items que não foram destacados. Selecionar "Aprovada" na caixa de seleção "Resolução" anterior. Colocar a votação no campo "Votação" deste formulário. ',
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')
    ));

    echo $this->Form->input('observacoes', array('label' => 'Observações', 'class' => 'ckeditor'));
    ?>
</fieldset>

<?php echo $this->Form->end(__('Salvar')); ?>

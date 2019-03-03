<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>

<div class="apoios form">
    <?php echo $this->Form->create('Apoio'); ?>
    <fieldset>
        <legend><?php echo __('Adicionar texto de apoio'); ?></legend>
        <?php
        echo $this->Form->input('caderno', array('type' => 'select', 'options' => array('Principal' => 'Principal', 'Anexo' => 'Anexo')));
        echo $this->Form->input('numero_texto');
        echo $this->Form->input('autor', array('class'=>'ckeditor'));
        echo $this->Form->input('titulo');
        echo $this->Form->input('tema', array('type' => 'select',
            'empty' => 'Selecione',
            'options' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV')));
        echo $this->Form->input('gt', array('label' => 'Setor ou grupo de trabalho',
            'empty' => 'Selecione',
            'options' => array(
                'Federais' => 'Federais',
                'Estaduais' => 'Estaduais',
                'GTCQERGDS' => 'GTCQERGDS',
                'GTCA' => 'GTCA',
                'GTC' => 'GTC',
                'GTCT' => 'GTCT',
                'GT Fundações' => 'GT Fundações',
                'GTHMD' => 'GTHMD',
                'GTPAUA' => 'GTPAUA',
                'GTPE' => 'GTPE',
                'GTPFS' => 'GTPFS',
                'GTSSA' => 'GTSSA',
                'GT Verbas' => 'GT Verbas',
                'Comissão da Verdade' => 'Comissão da Verdade',
                'Tesouraria' => 'Tesouraria',
                'Secretaria' => 'Secretaria',
                'Outras' => 'Outras'
            )
                )
        );
        echo $this->Form->input('texto', array('class'=>'ckeditor'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Acões'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('Listar Apoios'), array('action' => 'index')); ?></li>
        <?php
        if (isset($usuario)):
            if ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                ?>
                <li><?php echo $this->Html->link(__('Nova TR'), array('controller' => 'Item', 'action' => 'add')); ?> </li>        
                <?php
            endif;
        endif;
        ?>
    </ul>
</div>

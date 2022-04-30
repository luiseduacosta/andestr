<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<div class="apoios form">
    <?php echo $this->Form->create('Apoio'); ?>
    <fieldset>
        <legend><?php echo __('Editar Textos de Apoio'); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('caderno', array('type' => 'select', 'options' => array('Principal' => 'Principal', 'Anexo' => 'Anexo')));
        echo $this->Form->input('numero_texto');
        echo $this->Form->input('tema', array('type' => 'select',
            'options' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV')));
        echo $this->Form->input('gt', array('label' => 'Setor ou grupo de trabalho',
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
        ));
        echo $this->Form->input('titulo');
        echo $this->Form->input('autor', ['class' => 'ckeditor']);
        echo $this->Form->input('texto', array('type' => 'textarea', array('rows' => '4'), 'class' => 'ckeditor'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>

<div class="actions">
    <h3><?php echo __('Ações'); ?></h3>
    <ul>

        <?php if (isset($usuario)): ?>
            <?php if (($usuario['papel'] == 'editor') || ($usuario['papel'] == 'admin')): ?>
                <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Apoio.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Apoio.id')))); ?></li>
            <?php endif; ?>
        <?php endif; ?>
        <li><?php echo $this->Html->link(__('Lista Apoios'), array('action' => 'index')); ?></li>

    </ul>
</div>

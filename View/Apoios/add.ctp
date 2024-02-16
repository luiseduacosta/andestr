
<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>

<div class="row">
    <div class="col-2">
        <h3 class="h3"><?php echo __('Acões'); ?></h3>
        <ul class="list-group">
            <li class="list-group-item list-group-item-action"><?php echo $this->Html->link(__('Listar Apoios'), array('action' => 'index')); ?></li>
            <?php
            if (isset($usuario)):
                if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                    ?>
                    <li class="list-group-item list-group-item-action"><?php echo $this->Html->link(__('Nova TR'), array('controller' => 'Items', 'action' => 'add')); ?> </li>
                    <?php
                endif;
            endif;
            ?>
        </ul>
    </div>

    <div class="col-7">
        <?php
        echo $this->Form->create('Apoio', [
            'class' => 'form-horizontal',
            'role' => 'form',
            'inputDefaults' => [
                'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
                'div' => ['class' => 'form-group row'],
                'label' => ['class' => 'col-3'],
                'between' => "<div class = 'col-8'>",
                'class' => ['form-control'],
                'after' => "</div>",
                'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
            ]
        ]);
        ?>
        <fieldset>
            <legend><?php echo __('Adicionar texto de apoio'); ?></legend>
            <?php
            echo $this->Form->input('evento_id', ['type' => 'select', 'default' => array_key_last($eventos), 'options' => [$eventos]]);
            echo $this->Form->input('caderno', array('type' => 'select', 'options' => ['Principal' => 'Principal', 'Anexo' => 'Anexo']));
            echo $this->Form->input('numero_texto', ['required']);
            echo $this->Form->input('autor', array('class' => 'ckeditor'));
            echo $this->Form->input('titulo');
            echo $this->Form->input('tema', array('type' => 'select',
                'empty' => 'Selecione',
                'options' => array('I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV')));
            echo $this->Form->input('gt', array('label' => ['text' => 'Setor ou grupo de trabalho', 'class' => 'col-3'],
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
            echo $this->Form->input('texto', array('class' => 'ckeditor'));
            ?>
        </fieldset>
        <div class='row justify-content-left'>
            <div class='col-auto'>
                <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>

    </div>
</div>
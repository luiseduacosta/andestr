<?php echo $this->Html->script('ckeditor/ckeditor', ['inline' => false]); ?>

<div class="row">

    <div class="col-2">
        <h3 class="h3"><?php echo __('Ações'); ?></h3>
        <ul class="list-group">

            <?php if (isset($usuario)): ?>
                <?php if (($usuario['role'] == 'editor') || ($usuario['role'] == 'admin')): ?>
                    <li class="list-group-item list-group-item-action"><?php echo $this->Form->postLink(__('Delete'), ['action' => 'delete', $this->Form->value('Apoio.id')], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $this->Form->value('Apoio.id'))]); ?></li>
                <?php endif; ?>
            <?php endif; ?>
            <li class="list-group-item list-group-item-action"><?php echo $this->Html->link(__('Lista Apoios'), ['action' => 'index']); ?></li>

        </ul>
    </div>

    <div class="col-10">
        <?php
        echo $this->Form->create('Apoio', [
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
            <legend><?php echo __('Editar Textos de Apoio'); ?></legend>
            <?php
            echo $this->Form->input('id');
            echo $this->Form->input('evento_id', ['options' => $eventos]);
            echo $this->Form->input('caderno', ['type' => 'select', 'options' => ['Principal' => 'Principal', 'Anexo' => 'Anexo']]);
            echo $this->Form->input('numero_texto');
            echo $this->Form->input('tema', ['type' => 'select',
                'options' => ['I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV']
            ]);
            echo $this->Form->input('gt', ['label' => ['text' => 'Setor ou grupo de trabalho', 'class' => 'col-4'],
                'options' => [[
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
                    ],
                    'empty' => true
                ]
            ]);
            echo $this->Form->input('titulo');
            echo $this->Form->input('autor', ['class' => 'ckeditor']);
            echo $this->Form->input('texto', ['type' => 'textarea', 'rows' => '6', 'cols' => '50', 'class' => 'ckeditor']);
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
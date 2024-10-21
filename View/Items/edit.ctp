<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($resolucaos); ?>
<?php // pr($trs); ?>
<?php // pr($r['Resolucao']['tr']); ?>

<div class="items form">
    <?php if (!empty($resolucaos)): ?>    
        <table class="table">
            <tr>
                <td><?php echo $this->Html->link('Texto de apoio', '/apoios/view/' . $resolucaos['Apoio']['numero_texto']); ?></td>
            </tr>
            <tr>
                <td><?php echo "TR: " . $resolucaos['Item']['tr']; ?></td>
            </tr>
        </table>
    <?php else: ?>
        <?php echo "<h3 class='h3'>Item sem TR!!</h3>"; ?>
    <?php endif; ?>

    <?php
    echo $this->Form->create('Item', [
        'class' => 'form-horizontal',
        'role' => 'form',
        'inputDefaults' => [
            'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
            'div' => ['class' => 'form-group row'],
            'label' => ['class' => 'col-3'],
            'between' => "<div class = 'col-8'>",
            'class' => ['form-control'],
            'after' => '</div>',
            'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
        ]
    ]);
    ?>
    <fieldset>
        <legend><?php echo __('Editar item'); ?></legend>
        <?php
        echo $this->Form->input('id', ['value' => $resolucaos['Item']['id'],'type' => 'hidden']);
        echo $this->Form->input('apoio_id', ['value' => $resolucaos['Item']['apoio_id'],'type' => 'hidden']);
        echo $this->Form->input('tr', array('label' => "TR", 'value' => $resolucaos['Item']['tr']));
        echo $this->Form->input('item');
        echo $this->Form->input('texto', ['class' => 'ckeditor', 'style' => ['font-size: 16px']]);
        ?>
    </fieldset>
    <div class='row justify-content-left'>
        <div class='col-auto'>
            <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

</div>
<div class="row-fluid">
    <h3 class="h2"><?php echo __('Ações'); ?></h3>
    <ul class="nav">
        <li class="nav-item">
            <?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $this->Form->value('Item.id')], ['confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Item.id')), 'class' => 'nav-link']);
            ?>
        </li>
        <li class='nav-item'>
            <?php echo $this->Html->link(__('Listar items'), array('action' => 'index'), ['nav-link']); ?>
        </li>
    </ul>
</div>

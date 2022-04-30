<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($resolucaos);  ?>
<?php // pr($trs);  ?>
<?php // pr($r['Resolucao']['tr']);  ?>

<div class="items form">
    <?php if (!empty($resolucaos)): ?>    
        <table>
            <tr>
                <td><?php echo $this->Html->link('Texto de apoio', '/apoios/view/' . $resolucaos['Apoio']['numero_texto']); ?></td>
            </tr>
            <tr>
                <td><?php echo "TR: " . $resolucaos['Item']['tr']; ?></td>
            </tr>
        </table>
    <?php else: ?>
        <?php echo "<h3>Item sem TR!!</h3>"; ?>
    <?php endif; ?>

    <?php echo $this->Form->create('Item'); ?>
    <fieldset>
        <legend><?php echo __('Editar item'); ?></legend>
        <?php
        echo $this->Form->input('id', array('type' => 'hidden'));
        echo $this->Form->input('tr', array('label' => "TR", 'value' => $resolucaos['Item']['tr']));
        echo $this->Form->input('item');
        echo $this->Form->input('texto', ['class' => 'ckeditor', 'style' => ['font-size: 16px']]);
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>

</div>
<div class="actions">
    <h3><?php echo __('Ações'); ?></h3>
    <ul>
        <li><?php echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $this->Form->value('Item.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Item.id')))); ?></li>
        <li><?php echo $this->Html->link(__('Listar items'), array('action' => 'index')); ?></li>
    </ul>
</div>

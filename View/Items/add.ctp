<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($resolucaos); ?>
<?php // pr($tr); ?>

<div class="items form">

    <?php echo $this->Form->create('Item'); ?>
    <fieldset>
        <legend><?php echo "Itmes da TR "; ?></legend>
        <?php 
        echo $this->Form->input('apoio_id', array('label' => 'TR', 'type' => 'select', 'options' => $tr));
        echo $this->Form->input('item', array('label' => 'Item. Formato nn.nn Digitar: número da TR, "." o número do item.'));
        echo $this->Form->input('texto', array('label' => 'Item do texto de resolução', 'class'=>'ckeditor'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Acões'); ?></h3>
    <ul>
        <li><?php echo $this->Html->link(__('List Items'), array('action' => 'index')); ?></li>
    </ul>
</div>

<div class="eventos form">
	<?php echo $this->Form->create('Evento'); ?>
	<fieldset>
		<legend><?php echo __('Editar evento'); ?></legend>
		<?php
		echo $this->Form->input('id');
		echo $this->Form->input('nome');
		echo $this->Form->input('ordem');
		echo $this->Form->input('data');
		echo $this->Form->input('local');
		?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Ações'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Excluir'), array('action' => 'delete', $this->Form->value('Evento.id')), array('confirm' => __('Are you sure you want to delete # %s?', $this->Form->value('Evento.id')))); ?>
		</li>
		<li><?php echo $this->Html->link(__('Listar'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('Listar textos de apoio'), array('controller' => 'apoios', 'action' => 'index')); ?>
		</li>
		<li><?php echo $this->Html->link(__('Novo texto de apoio'), array('controller' => 'apoios', 'action' => 'add')); ?>
		</li>
	</ul>
</div>
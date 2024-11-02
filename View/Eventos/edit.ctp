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

		<li><?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $this->Form->value('Evento.id')], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $this->Form->value('Evento.id'))]); ?>
		</li>
		<li><?php echo $this->Html->link(__('Listar'), ['action' => 'index']); ?></li>
		<li><?php echo $this->Html->link(__('Listar textos de apoio'), ['controller' => 'apoios', 'action' => 'index']); ?>
		</li>
		<li><?php echo $this->Html->link(__('Novo texto de apoio'), ['controller' => 'apoios', 'action' => 'add']); ?>
		</li>
	</ul>
</div>
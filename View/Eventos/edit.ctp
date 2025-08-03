<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<ul class="navbar-nav">
		<li class="nav-item">
			<?php echo $this->Html->link(__('Listar'), ['action' => 'index'], ['class' => 'btn btn-primary me-1']); ?>
		</li>
		<?php if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')): ?>
			<li class="nav-item">
				<?php echo $this->Html->link(__('Novo evento'), ['controller' => 'evento', 'action' => 'add'], ['class' => 'btn btn-primary me-1']); ?>
			</li>
			<li class="nav-item">
				<?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $this->Form->value('Evento.id')], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $this->Form->value('Evento.id')), 'class' => 'btn btn-danger btn-block']); ?>
			</li>
		<?php endif; ?>
	</ul>
</nav>

<?php if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')): ?>
	<div class="container">
		<?php echo $this->Form->create('Evento', [
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
		]); ?>
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
		<?php echo $this->Form->submit(__('Confirma'), ['type' => 'Submit', 'label' => __('Confirma'), 'class' => 'btn btn-primary']); ?>
		<?php echo $this->Form->end(); ?>
	</div>
<?php endif;
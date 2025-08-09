<?php if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')): ?>

	<div class="navbar navbar-expand-lg navbar-light bg-light">
		<ul class='navbar-nav'>
			<li class='nav-item'>
				<?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $this->Form->value('Gt.id')], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $this->Form->value('Gt.id')), 'class' => 'btn btn-danger btn-block']); ?>
			</li>
			<li class='nav-item'>
				<?php echo $this->Html->link(__('Listar GTs'), ['action' => 'index'], ['class' => 'btn btn-primary']); ?>
			</li>
		</ul>
	</div>

	<div class="col-9">
		<div class="gts form">
			<?php echo $this->Form->create('Gt', [
				'class' => 'form-horizontal',
				'role' => 'form',
				'inputDefaults' => [
					'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
					'div' => ['class' => 'form-group row'],
					'label' => ['class' => 'col-3'],
					'between' => "<div class = 'col-9'>",
					'class' => ['form-control'],
					'after' => '</div>',
					'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
				]
			]); ?>
			<fieldset>
				<legend><?php echo __('Editar GT'); ?></legend>
				<?php
				echo $this->Form->input('id');
				echo $this->Form->input('sigla');
				echo $this->Form->input('nome');
				echo $this->Form->input('outras', ['label' => ['text' => 'Observações', 'class' => 'col-3'], 'class' => 'form-control']);
				?>
			</fieldset>
			<div class='row justify-content-left'>
				<div class='col-auto'>
					<?php echo $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']); ?>	<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>
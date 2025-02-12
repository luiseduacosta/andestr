<div class="navbar navbar-expand-lg navbar-light bg-light">

	<ul class='navbar-nav'>

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
				'label' => ['class' => 'col-2'],
				'between' => "<div class = 'col-8'>",
				'class' => ['form-control'],
				'after' => '</div>',
				'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
			]
		]); ?>
		<fieldset>
			<legend><?php echo __('Novo GT'); ?></legend>
			<?php
			echo $this->Form->input('sigla');
			echo $this->Form->input('nome');
			echo $this->Form->input('outras');
			?>
		</fieldset>
		<div class='row justify-content-left'>
			<div class='col-auto'>
				<?php echo $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']); ?><?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
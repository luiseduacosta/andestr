<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<ul class="navbar-nav mr-auto">
		<li class='nav-item'>
			<?php echo $this->Html->link(__('Listar GTs'), ['action' => 'index'], ['class' => 'btn btn-primary btn-sm btn-block']); ?>
		</li>
		<?php
		if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
			?>
			<li class='nav-item'>
				<?php echo $this->Form->postLink(__('Excluir GT'), ['action' => 'delete', $gt['Gt']['id']], ['confirm' => __('Tem certeza que quer excluir o registro # %s?', $gt['Gt']['id']), 'class' => 'btn btn-danger btn-sm btn-block']); ?>
			</li>
			<li class='nav-item'>
				<?php echo $this->Html->link(__('Editar GT'), ['action' => 'edit', $gt['Gt']['id']], ['class' => 'btn btn-primary btn-sm btn-block']); ?>
			</li>
			<li class='nav-item'>
				<?php echo $this->Html->link(__('Novo GT'), ['action' => 'add'], ['class' => 'btn btn-primary btn-sm btn-block']); ?>
			</li>
		<?php endif; ?>
	</ul>
</nav>

<div class="container">
	<h2 class='h2'><?php echo __('GT'); ?></h2>
	<dl class="row">
		<dt class='col-3'><?php echo __('Id'); ?></dt>
		<dd class='col-9'>
			<?php echo h($gt['Gt']['id']); ?>
			&nbsp;
		</dd>
		<dt class='col-3'><?php echo __('Sigla'); ?></dt>
		<dd class='col-9'>
			<?php echo h($gt['Gt']['sigla']); ?>
			&nbsp;
		</dd>
		<dt class='col-3'><?php echo __('Nome'); ?></dt>
		<dd class='col-9'>
			<?php echo h($gt['Gt']['nome']); ?>
			&nbsp;
		</dd>
		<dt class='col-3'><?php echo __('Observações'); ?></dt>
		<dd class='col-9'>
			<?php echo h($gt['Gt']['outras']); ?>
			&nbsp;
		</dd>
	</dl>
</div>

<?php
if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
	?>
	<div class="container">
		<div class="row">
			<ul class="list-group">
				<li class="list-group-item">
					<?php echo $this->Html->link(__('Novo GT'), ['controller' => 'gts', 'action' => 'add'], ['class' => 'btn btn-primary btn-sm btn-block']); ?>
				</li>
			</ul>
		</div>
	</div>
<?php endif; ?>
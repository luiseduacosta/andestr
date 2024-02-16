<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>

<div class="navbar navbar-expand-lg navbar-light bg-light">

	<ul class='navbar-nav'>

<?php if (strpos($action, 'add') === false): ?>
		<li class='nav-item'><?php echo "<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), array('confirm' => __('Are you sure you want to delete # %s?', \$this->Form->value('{$modelClass}.{$primaryKey}')), 'class' => 'btn btn-danger btn-block')); ?>"; ?></li>
<?php endif; ?>
		<li class='nav-item'><?php echo "<?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index'), ['class' => 'btn btn-primary']); ?>"; ?></li>
<?php
		$done = array();
		foreach ($associations as $type => $data) {
			foreach ($data as $alias => $details) {
				if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
					echo "\t\t<li class='nav-item'><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index'), ['class' => 'btn btn-primary btn-sm btn-block']); ?> </li>\n";
					echo "\t\t<li class='nav-item'><?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add'), ['class' => 'btn btn-primary btn-sm btn-block']); ?> </li>\n";
					$done[] = $details['controller'];
				}
			}
		}
?>
	</ul>
</div>

<div class="col-9">
<div class="<?php echo $pluralVar; ?> form">
<?php echo "<?php echo \$this->Form->create('{$modelClass}', [
	        'class' => 'form-horizontal',
			'role' => 'form',
			'inputDefaults' => [
				'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
				'div' => ['class' => 'form-group row'],
				'label' => ['class' => 'col-2'],
				'between' => \"<div class = 'col-8'>\",
				'class' => ['form-control'],
				'after' => '</div>',
				'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
	]
]); ?>\n"; ?>
	<fieldset>
		<legend><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></legend>
<?php
		echo "\t<?php\n";
		foreach ($fields as $field) {
			if (strpos($action, 'add') !== false && $field === $primaryKey) {
				continue;
			} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
				echo "\t\techo \$this->Form->input('{$field}');\n";
			}
		}
		if (!empty($associations['hasAndBelongsToMany'])) {
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
				echo "\t\techo \$this->Form->input('{$assocName}');\n";
			}
		}
		echo "\t?>\n";
?>
	</fieldset>
    <div class='row justify-content-left'>
        <div class='col-auto'>
<?php echo "<?php echo \$this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']); ?>"; ?>
<?php echo "<?php echo \$this->Form->end(); ?>"; ?>
        </div>
    </div>
</div>
	</div>

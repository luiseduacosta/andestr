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

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-ligth bg-ligth">
        <ul class="navbar-nav">
            <li class="nav-item"><?php echo "<?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add'), ['class' => 'btn btn-primary btn-block']); ?>"; ?></li>
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
    </nav>

    <div class="<?php echo $pluralVar; ?> row">
        <h2 class="h2"><?php echo "<?php echo __('{$pluralHumanName}'); ?>"; ?></h2>
        <table cellpadding="0" cellspacing="0" class="table table-hover table-striped table-responsive">
            <thead class="thead-light">
                <tr>
                    <?php foreach ($fields as $field): ?>
                        <th><?php echo "<?php echo \$this->Paginator->sort('{$field}'); ?>"; ?></th>
                    <?php endforeach; ?>
                    <th class="table-active"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
                echo "\t<tr>\n";
                foreach ($fields as $field) {
                    $isKey = false;
                    if (!empty($associations['belongsTo'])) {
                        foreach ($associations['belongsTo'] as $alias => $details) {
                            if ($field === $details['foreignKey']) {
                                $isKey = true;
                                echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}']), ['class' => 'btn btn-secondary btn-sm btn-block']); ?>\n\t\t</td>\n";
                                break;
                            }
                        }
                    }
                    if ($isKey !== true) {
                        echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
                    }
                }

                echo "\t\t<td class=\"table-success\">\n";
                echo "\t\t\t<?php echo \$this->Html->link(__('View'), array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), ['class' => 'btn btn-primary btn-sm btn-block']); ?>\n";
                echo "\t\t\t<?php echo \$this->Html->link(__('Edit'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), ['class' => 'btn btn-primary btn-sm btn-block']); ?>\n";
                echo "\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('confirm' => __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}']), 'class' => 'btn btn-danger btn-sm btn-block')); ?>\n";
                echo "\t\t</td>\n";
                echo "\t</tr>\n";

                echo "<?php endforeach; ?>\n";
                ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <p>
            <?php echo "<?php
	echo \$this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>"; ?>
        </p>
    </div>

    <div class="row">
        <div class="pagination">
            <?php
            echo "<?php\n";
            echo "\t\techo \$this->Paginator->prev('< ' . __('previous'), array('class' => 'page-link'), null, array('class' => 'page-link'));\n";
            echo "\t\techo \$this->Paginator->numbers(array('separator' => '', 'class' => 'page-link'));\n";
            echo "\t\techo \$this->Paginator->next(__('next') . ' >', array('class' => 'page-link'), null, array('class' => 'page-link'));\n";
            echo "\t?>\n";
            ?>
        </div>
    </div>
</div>

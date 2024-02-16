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

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto">
        <?php
        echo "\t\t<li class='nav-item'><?php echo \$this->Form->postLink(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('confirm' => __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}']), 'class' => 'btn btn-danger btn-sm btn-block')); ?> </li>\n";
        echo "\t\t<li class='nav-item'><?php echo \$this->Html->link(__('Edit " . $singularHumanName . "'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), ['class' => 'btn btn-primary btn-sm btn-block']); ?> </li>\n";
        echo "\t\t<li class='nav-item'><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index'), ['class' => 'btn btn-primary btn-sm btn-block']); ?> </li>\n";
        echo "\t\t<li class='nav-item'><?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add'), ['class' => 'btn btn-primary btn-sm btn-block']); ?> </li>\n";

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

<div class="<?php echo $pluralVar; ?> container">
    <h2 class='h2'><?php echo "<?php echo __('{$singularHumanName}'); ?>"; ?></h2>
    <dl class="row">
        <?php
        foreach ($fields as $field) {
            $isKey = false;
            if (!empty($associations['belongsTo'])) {
                foreach ($associations['belongsTo'] as $alias => $details) {
                    if ($field === $details['foreignKey']) {
                        $isKey = true;
                        echo "\t\t<dt class='col-3'><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></dt>\n";
                        echo "\t\t<dd class='col-9'>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
                        break;
                    }
                }
            }
            if ($isKey !== true) {
                echo "\t\t<dt class='col-3'><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
                echo "\t\t<dd class='col-9'>\n\t\t\t<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
            }
        }
        ?>
    </dl>
</div>

<div class="container">
    <div class="row">
        <?php
        if (!empty($associations['hasOne'])) :
            foreach ($associations['hasOne'] as $alias => $details):
                ?>
                <div class="row">
                    <h3 class='h2'><?php echo "<?php echo __('Related " . Inflector::humanize($details['controller']) . "'); ?>"; ?></h3>
                    <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
                    <dl class='row'>
                        <?php
                        foreach ($details['fields'] as $field) {
                            echo "\t\t<dt class='col-3'><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
                            echo "\t\t<dd class='col-9'>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}']; ?>\n&nbsp;</dd>\n";
                        }
                        ?>
                    </dl>
                    <?php echo "<?php endif; ?>\n"; ?>
                    <div class="navbar navbar-ligth bg-light">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item"><?php echo "<?php echo \$this->Html->link(__('Edit " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}']), ['class' => 'btn btn-primary btn-sm btn-block']); ?></li>\n"; ?>
                        </ul>
                    </div>
                </div>
                <?php
            endforeach;
        endif;
        ?>
    </div>

    <?php
    if (empty($associations['hasMany'])) {
        $associations['hasMany'] = array();
    }
    if (empty($associations['hasAndBelongsToMany'])) {
        $associations['hasAndBelongsToMany'] = array();
    }
    $relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
    foreach ($relations as $alias => $details):
        $otherSingularVar = Inflector::variable($alias);
        $otherPluralHumanName = Inflector::humanize($details['controller']);
        ?>
        <div class="row">
            <h3 class='h2'><?php echo "<?php echo __('Related " . $otherPluralHumanName . "'); ?>"; ?></h3>
            <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
            <table cellpadding = "0" cellspacing = "0" class="table table-striped table-hover">
                <thead class="thead-light">
                    <tr>
                        <?php
                        foreach ($details['fields'] as $field) {
                            echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
                        }
                        ?>
                        <th class="table-primary"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    echo "\t<?php foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
                    echo "\t\t<tr>\n";
                    foreach ($details['fields'] as $field) {
                        echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}']; ?></td>\n";
                    }

                    echo "\t\t\t<td class=\"table-info\">\n";
                    echo "\t\t\t\t<?php echo \$this->Html->link(__('View'), array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']), ['class' => 'btn btn-primary btn-sm btn-block']); ?>\n";
                    echo "\t\t\t\t<?php echo \$this->Html->link(__('Edit'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']), ['class' => 'btn btn-primary btn-sm btn-block']); ?>\n";
                    echo "\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('confirm' => __('Are you sure you want to delete # %s?', \${$otherSingularVar}['{$details['primaryKey']}']), 'class' => 'btn btn-danger btn-sm btn-block')); ?>\n";
                    echo "\t\t\t</td>\n";
                    echo "\t\t</tr>\n";

                    echo "\t<?php endforeach; ?>\n";
                    ?>
                </tbody>
            </table>
            <?php echo "<?php endif; ?>\n\n"; ?>
        </div>


        <?php
    endforeach;
    ?>
</div>

<div class="container">
    <div class="row">
        <ul class="list-group">
            <li class="list-group-item"><?php echo "<?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add'), ['class' => 'btn btn-primary btn-sm btn-block']); ?>"; ?> </li>
        </ul>
    </div>
</div>

<div class="apoios index">
    <h2><?php echo __('Textos de Apoio'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('caderno'); ?></th>
                <th><?php echo $this->Paginator->sort('numero_texto', 'Nº'); ?></th>
                <th><?php echo $this->Paginator->sort('tema'); ?></th>
                <th><?php echo $this->Paginator->sort('gt', 'GT'); ?></th>
                <th><?php echo $this->Paginator->sort('titulo'); ?></th>
                <th><?php echo $this->Paginator->sort('autor'); ?></th>
                <th><?php echo $this->Paginator->sort('texto'); ?></th>
                <th class="actions"><?php echo __('Ações'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apoios as $apoio): ?>
                <tr>
                    <td><?php echo h($apoio['Apoio']['id']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['caderno']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['numero_texto']); ?>&nbsp;</td>
                    <td><?php echo $this->Html->link($apoio['Apoio']['tema'], 'index/tema:' . $apoio['Apoio']['tema']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['gt']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['titulo']); ?>&nbsp;</td>
                    <td><?php echo $this->Text->truncate(h($apoio['Apoio']['autor']), 200, array('ellipsis' => ' ...', 'exact' => false)); ?>&nbsp;</td>
                    <td><?php echo $this->Text->truncate(h($apoio['Apoio']['texto']), 200, array('ellipsis' => ' ...', 'exact' => false)); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('Ver'), array('action' => 'view', $apoio['Apoio']['id'])); ?>
                        <?php
                        if (isset($usuario)):
                            if ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                                ?>
                                <?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $apoio['Apoio']['id'])); ?>
                                <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $apoio['Apoio']['id']), array('confirm' => __('Confirma excluir o registro # %s?', $apoio['Apoio']['id']))); ?>
                            <?php endif; ?>
    <?php endif; ?>
                    </td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>	</p>
    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>
</div>

<div class="actions">
    <h3><?php echo __('Ações'); ?></h3>
    <ul>
        <?php
        if (isset($usuario)):
            // pr($usuario);
            if ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                ?>
                <li><?php echo $this->Html->link(__('Novo texto de apoio'), array('action' => 'add')); ?></li>    
                <li><?php echo $this->Html->link(__('Resoluções'), array('controller' => 'items', 'action' => 'index')); ?> </li>        
                <?php if ($usuario['papel'] == 'relator'): ?>
                    <li><?php echo $this->Html->link(__('Resoluções'), array('controller' => 'items', 'action' => 'index/grupo:' . $usuario['grupo'])); ?> </li>
                <?php endif; ?>
            <?php elseif ($usuario['papel'] == 'relator'): ?>
                <li><?php echo $this->Html->link(__('Resoluções'), array('controller' => 'items', 'action' => 'index')); ?> </li>
            <?php endif; ?>
        <?php else: ?>
            <li><?php echo $this->Html->link(__('Resoluções'), array('controller' => 'items', 'action' => 'index')); ?> </li>
<?php endif; ?> 
    </ul>
</div>

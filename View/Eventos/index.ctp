<?php if (isset($usuario)): ?>
    <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <ul class='navbar-nav mr-auto'>
                <a class='navbar-brand'><?php echo __('Ações'); ?></a>        
                <li class='nav-item'><?php echo $this->Html->link(__('Novo evento'), ['action' => 'add'], ['class' => 'nav-link']); ?></li>
            </ul>
        </nav>
    <?php endif ?>
<?php endif ?> 

<div class="row">
    <h2 class="h2"><?php echo __('Eventos'); ?></h2>
    <table cellpadding="0" cellspacing="0" class= "table table-striped table-hover table-responsive">
        <thead class="thead-light">
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('evento'); ?></th>
                <th><?php echo $this->Paginator->sort('ordem'); ?></th>
                <th><?php echo $this->Paginator->sort('data'); ?></th>
                <th><?php echo $this->Paginator->sort('local'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($eventos as $evento): ?>
                <tr>
                    <td><?php echo h($evento['Evento']['id']); ?>&nbsp;</td>
                    <td><?php echo $this->Html->link(h($evento['Evento']['evento']), ['action' => 'view', $evento['Evento']['id']]); ?>&nbsp;</td>
                    <td><?php echo h($evento['Evento']['ordem']); ?>&nbsp;</td>
                    <td><?php echo h($evento['Evento']['data']); ?>&nbsp;</td>
                    <td><?php echo h($evento['Evento']['local']); ?>&nbsp;</td>
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

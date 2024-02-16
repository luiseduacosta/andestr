<script>
    $(document).ready(function () {
        var url = "<?= $this->Html->url(['controller' => 'Apoios', 'action' => 'index/evento:']); ?>";
        $("#EventoEventoId").change(function () {
            var evento_id = $(this).val();
            /* alert(evento_id); */
            window.location = url + evento_id;
        })

    })
</script>

<div class="row justify-content-center">
    <div class="col-auto">

        <?php if (isset($usuario) && $usuario['role'] == 'admin'): ?>
            <?php echo $this->Form->create('Evento', ['class' => 'form-inline']); ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Eventos&nbsp', 'style' => 'display: inline;'], 'options' => $eventos, 'default' => $evento, 'class' => 'form-control']); ?>
            <?php echo $this->Form->end(); ?>
        <?php else: ?>
            <p class="text-center text-secondary h2"><?php echo end($eventos); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto">
        <a class="navbar-brand"><?php echo __('Ações'); ?></a>
        <?php
        if (isset($usuario)):
            // pr($usuario);
            if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                ?>
                <li class="nav-item"><?php echo $this->Html->link(__('Novo texto de apoio'), ['action' => 'add'], ['class' => 'nav-link']); ?></li>    
                <li class="nav-item"><?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index'], ['class' => 'nav-link']); ?> </li>        
                <?php if ($usuario['role'] == 'relator'): ?>
                    <li class="nav-item"><?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index/grupo:' . $usuario['grupo']], ['class' => 'nav-link']); ?> </li>
                <?php endif; ?>
            <?php elseif ($usuario['role'] == 'relator'): ?>
                <li class="nav-item"><?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index'], ['class' => 'nav-link']); ?> </li>
            <?php endif; ?>
        <?php else: ?>
            <li class="nav-item"><?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index'], ['class' => 'nav-link']); ?> </li>
        <?php endif; ?> 
    </ul>
</div>

<div class="row">
    <h2><?php echo __('Textos de Apoio'); ?></h2>
    <table cellpadding="0" cellspacing="0" class="table">
        <thead class="thead-light">
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('evento'); ?></th>
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
                    <td><?php echo h($apoio['Evento']['evento']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['caderno']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['numero_texto']); ?>&nbsp;</td>
                    <td><?php echo $this->Html->link(strip_tags($apoio['Apoio']['tema']), 'index/tema:' . $apoio['Apoio']['tema']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['gt']); ?>&nbsp;</td>
                    <td><?php echo strip_tags($apoio['Apoio']['titulo']); ?>&nbsp;</td>
                    <td><?php echo $this->Text->truncate(strip_tags($apoio['Apoio']['autor']), 200, array('ellipsis' => ' ...', 'exact' => false)); ?>&nbsp;</td>
                    <td><?php echo $this->Text->truncate(strip_tags($apoio['Apoio']['texto']), 200, array('ellipsis' => ' ...', 'exact' => false)); ?>&nbsp;</td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('Ver'), array('action' => 'view', $apoio['Apoio']['id'])); ?>
                        <?php
                        if (isset($usuario)):
                            if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
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
        echo $this->Paginator->counter([
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ]);
        ?>
    </p>
    <div class="pagination justify-content-center">
        <?php
        echo $this->Paginator->prev('< ' . __('previous'), [], null, ['class' => 'prev disabled']);
        echo $this->Paginator->numbers(['separator' => ''], ['class' => 'page-link']);
        echo $this->Paginator->next(__('next') . ' >', [], null, ['class' => 'next disabled']);
        ?>
    </div>
</div>

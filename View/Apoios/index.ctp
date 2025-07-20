<?php

// pr($apoio);
// pr($gts);
// pr($eventos);
// die();

?>

<script>
    $(document).ready(function () {
        var url = "<?= $this->Html->build(['controller' => 'Apoios', 'action' => 'index', '?' => ['evento_id' => '']]); ?>";
        $("#EventoEventoId").change(function () {
            var evento_id = $(this).val();
            /* alert(evento_id); */
            window.location = url + evento_id;
        })

    })
</script>

<div class="row justify-content-center">
    <div class="row mb-3">
        <?php if (isset($evento_id)): ?>
            <?php echo $this->Form->create('Evento', ['class' => 'form-inline']); ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Eventos', 'class' => 'd-inline-block p-1 form-label'], 'options' => $eventos, 'default' => $evento_id, 'class' => 'form-control']); ?>
        <?php else: ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Eventos', 'class' => 'd-inline-block p-1 form-label'], 'options' => $eventos, 'default' => end($eventos), 'class' => 'form-control']); ?>
        <?php endif; ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<div class="row justify-content-center">
    <p>
        <?php
        echo $this->Paginator->counter([
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ]);
        ?>
    </p>
</div>

<div class="row justify-content-center">
    <div class="pagination">
        <?php
        echo $this->Paginator->prev('< ' . __('anterior'), array('class' => 'page-link'), null, array('class' => 'page-link'));
        echo $this->Paginator->numbers(array('separator' => '', 'class' => 'page-link'));
        echo $this->Paginator->next(__('próximo') . ' >', array('class' => 'page-link'), null, array('class' => 'page-link'));
        ?>
    </div>
</div>

<div class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto">
        <a class="navbar-brand"><?php echo __('Ações'); ?></a>
        <?php if (isset($evento_id)): ?>
            <?php
            if (isset($usuario)):
                if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                    ?>
                    <li class="nav-item">
                        <?php echo $this->Html->link(__('Novo texto de apoio'), ['action' => 'add', '?' => ['evento_id' => $evento_id]], ['class' => 'nav-link']); ?>
                    </li>
                    <li class="nav-item">
                        <?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index', '?' => ['evento_id' => $evento_id]], ['class' => 'nav-link']); ?>
                    </li>
                <?php elseif ($usuario['role'] == 'relator'): ?>
                    <li class="nav-item">
                        <?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index', '?' => ['grupo' => $usuariogrupo], 'evento_id' => $evento_id], ['class' => 'nav-link']); ?>
                    </li>
                <?php endif; ?>
            <?php else: ?>
                <li class="nav-item">
                    <?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index', '?' => ['evento_id' => $evento_id]], ['class' => 'nav-link']); ?>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</div>

<div class="container">
    <h2><?php echo __('Textos de Apoio'); ?></h2>

    <table cellpadding="0" cellspacing="0" class="table">
        <thead class="thead-light">
            <tr>
                <th><?php echo $this->Paginator->sort('id'); ?></th>
                <th><?php echo $this->Paginator->sort('nomedoevento'); ?></th>
                <th><?php echo $this->Paginator->sort('caderno'); ?></th>
                <th><?php echo $this->Paginator->sort('numero_texto', 'Nº'); ?></th>
                <th><?php echo $this->Paginator->sort('tema'); ?></th>
                <th><?php echo $this->Paginator->sort('gt', 'GT'); ?></th>
                <th><?php echo $this->Paginator->sort('gt_id', 'GT'); ?></th>
                <th><?php echo $this->Paginator->sort('titulo'); ?></th>
                <th><?php echo $this->Paginator->sort('autor'); ?></th>
                <th><?php echo $this->Paginator->sort('texto'); ?></th>
                <th class="actions"><?php echo __('Ações'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apoios as $apoio): ?>
                <?php // pr($apoio) ?>
                <tr>
                    <td><?php echo h($apoio['Apoio']['id']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Evento']['nome']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['caderno']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Apoio']['numero_texto']); ?>&nbsp;</td>
                    <td><?php echo $this->Html->link(strip_tags($apoio['Apoio']['tema']), 'index/tema:' . $apoio['Apoio']['tema']); ?>&nbsp;
                    </td>
                    <td><?php echo h($apoio['Apoio']['gt']); ?>&nbsp;</td>
                    <td><?php echo h($apoio['Gt']['sigla']); ?>&nbsp;</td>
                    <td><?php echo strip_tags($apoio['Apoio']['titulo']); ?>&nbsp;</td>
                    <td><?php echo $this->Text->truncate(strip_tags($apoio['Apoio']['autor']), 200, ['ellipsis' => ' ...', 'exact' => false]); ?>&nbsp;
                    </td>
                    <td><?php echo $this->Text->truncate(strip_tags($apoio['Apoio']['texto']), 200, ['ellipsis' => ' ...', 'exact' => false]); ?>&nbsp;
                    </td>
                    <td class="actions">
                        <?php echo $this->Html->link(__('Ver'), ['action' => 'view', $apoio['Apoio']['id']]); ?>
                        <?php
                        if (isset($usuario)):
                            if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                                ?>
                                <?php echo $this->Html->link(__('Edit'), ['action' => 'edit', $apoio['Apoio']['id']]); ?>
                                <?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $apoio['Apoio']['id']], ['confirm' => __('Confirma excluir o registro # %s?', $apoio['Apoio']['id'])]); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row justify-content-center">
        <p>
            <?php
            echo $this->Paginator->counter([
                'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
            ]);
            ?>
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="pagination">
            <?php
            echo $this->Paginator->prev('< ' . __('anterior'), array('class' => 'page-link'), null, array('class' => 'page-link'));
            echo $this->Paginator->numbers(array('separator' => '', 'class' => 'page-link'));
            echo $this->Paginator->next(__('próximo') . ' >', array('class' => 'page-link'), null, array('class' => 'page-link'));
            ?>
        </div>
    </div>

</div>
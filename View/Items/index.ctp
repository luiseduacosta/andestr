<?php // pr($items);              ?>
<?php // pr($tr);                                                         ?>
<?php // pr($votacao);                                                         ?>
<?php pr($evento_id); ?>
<?php // pr($usuariogrupo);               ?>

<script>

    document.addEventListener("DOMContentLoaded", function () {
        // Your code to run since DOM is loaded and ready

        var url = "<?= $this->Html->url(['controller' => 'Items', 'action' => 'index/evento_id:']); ?>";
        console.log(url);
        // document.getElementById('EventoEventoId');
        // console.log(document.getElementById('EventoEventoId'));
        document.querySelector("#EventoEventoId").addEventListener('change', function () {
            console.log('Valor: ', this.value);
            var evento_id = this.value;
            console.log(url + evento_id);
            /* alert(evento_id); */
            window.location.assign(url + evento_id);
        })
    });
</script>

<div class="row justify-content-center">
    <div class="col-auto">

        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <?php echo $this->Form->create('Evento', ['class' => 'form-inline']); ?>
            <?php echo $this->Form->input('evento_id', ['id' => 'EventoEventoId', 'type' => 'select', 'label' => ['text' => 'Eventos&nbsp', 'style' => 'display: inline;'], 'options' => $eventos, 'default' => $evento_id, 'class' => 'form-control']); ?>
            <?php echo $this->Form->end(); ?>
        <?php else: ?>
            <p class="text-center text-secondary h2"><?php echo end($eventos); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-2">

        <h3 class="h2"><?php echo __('TRs'); ?></h3>
        <ul class="list-group">
            <?php
            if (isset($usuario)):
                if ($usuario['role'] == 'admin' || $usuario['role'] == 'editor'):
                    ?>
                    <li class="list-group-item">
                        <?php
                        echo $this->Html->link(__('Inserir item em TR'), array('action' => 'add'), ['class' => 'list-group-link']);
                        ?>
                    </li>
                    <?php
                endif;
            endif;
            ?>

            <?php
            if (isset($usuario['role'])):
                if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                    ?>
                    <li class="list-group-item">
                        <?php
                        echo $this->Html->link('Ver votações', '/Votacaos/index?' . 'evento_id=' . $evento_id);
                        ?>
                    </li>
                    <?php
                elseif ($usuario['role'] == 'relator'):
                    ?>
                    <li class="list-group-item">
                        <?php
                        echo $this->Html->link('Ver votações', '/Votacaos/index?' . 'grupo=' . $usuariogrupo . '&evento_id=' . $evento_id);
                        ?>
                    </li>
                    <?php
                endif;
            endif;
            ?>

            <?php foreach ($tr as $c_tr): ?>

                <?php if (isset($usuario)): ?>
                    <?php if ($usuario['role'] == 'relator'): ?>
                        <li class="list-group-item">
                            <?php echo $this->Html->link('TR: ' . $c_tr['Item']['tr'], 'index?tr=' . $c_tr['Item']['tr'] . '?grupo=' . $usuariogrupo . '&evento=' . $evento_id); ?>
                        </li>
                    <?php elseif ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                        <li class="list-group-item">
                            <?php echo $this->Html->link('TR: ' . $c_tr['Item']['tr'], 'index?tr=' . $c_tr['Item']['tr'] . '&evento=' . $evento_id); ?>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="list-group-item">
                        <?php echo $this->Html->link('TR: ' . $c_tr['Item']['tr'], 'index?tr=' . $c_tr['Item']['tr'] . '?evento=' . $evento_id); ?>
                    </li>
                <?php endif; ?>

            <?php endforeach; ?>

        </ul>

    </div>

    <div class="col-10">
        <h3 class="h3"><?php echo __('TR por Items'); ?></h2>
            <table cellpadding="0" cellspacing="0" class="table table-hover table-striped table-responsive">
                <thead class="thead-light">
                    <tr>
                        <th><?php echo $this->Paginator->sort('id', 'TR'); ?></th>
                        <th><?php echo $this->Paginator->sort('item', 'Item'); ?></th>
                        <th><?php echo $this->Paginator->sort('texto', 'Texto'); ?></th>
                        <th class="table-secondary"><?php echo __('Acões'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($items)): ?>
                        <?php foreach ($items as $c_item): ?>
                            <?php // pr($c_item); ?>
                            <tr>
                                <td><?php
                                    if (isset($usuario)):
                                        if ($usuario['role'] == 'admin' || $usuario['role'] == 'editor'):
                                            echo $this->Html->link(substr($c_item['Item']['item'], 0, 2), ['controller' => 'apoios', 'action' => 'view?tr=' . substr($c_item['Item']['item'], 0, 2) . '&evento=' . $c_item['Apoio']['evento_id']]);
                                        elseif ($usuario['role'] == 'relator'):
                                            echo $this->Html->link(substr($c_item['Item']['item'], 0, 2), ['controller' => 'apoios', 'action' => 'view?tr=' . substr($c_item['Item']['item'], 0, 2) . '&grupo:' . $usuariogrupo]);
                                        endif;
                                    else:
                                        echo $this->Html->link(substr($c_item['Item']['item'], 0, 2), ['controller' => 'apoios', 'action' => 'view?tr=' . substr($c_item['Item']['item'], 0, 2) . '&evento=' . $c_item['Apoio']['evento_id']]);
                                    endif;
                                    ?>&nbsp;</td>

                                <td><?php
                                    if (isset($usuario)):
                                        if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                                            echo $this->Html->link($c_item['Item']['item'], 'view/' . $c_item['Item']['id']);
                                        elseif ($usuario['role'] == 'relator'):
                                            echo $this->Html->link($c_item['Item']['item'], 'view/' . $c_item['Item']['id'] . '/grupo:' . $usuariogrupo);
                                        endif;
                                    else:
                                        echo $this->Html->link($c_item['Item']['item'], 'view/' . $c_item['Item']['id']);
                                    endif;
                                    ?>&nbsp;</td>

                                <td><?php echo $this->Text->truncate($c_item['Item']['texto'], 500, array('ellipsis' => ' ...', 'exact' => false)); ?>&nbsp;</td>

                                <td class="row">

                                    <ul class="nav">
                                        <?php if (isset($usuario)): ?>

                                            <!-- /* Relator vota */ -->
                                            <?php if ($usuario['role'] == 'relator'): ?>
                                                <!-- se for um item de inclusão, não tem que aparecer o botão de votar -->
                                                <?php if (substr($c_item['Item']['item'], 3, 2) == '99'): ?>
                                                    <li class="nav-item">
                                                        <p class = 'btn btn-secondary btn-block btn-sm'>Item incluído por votação</p>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votação ou inclusão'), '/votacaos/add/' . $c_item['Item']['id'] . '/grupo:' . $usuariogrupo, ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (count($c_item['Votacao']) > 0): ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item:' . substr($c_item['Item']['item'], 0, 5), ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <p class='btn btn-secondary btn-block btn-sm'>Sem votação</p>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- /* Editor não vota */  -->
                                            <?php elseif ($usuario['role'] == 'editor'): ?>
                                                <?php if (count($c_item['Votacao']) > 0): ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item:' . substr($c_item['Item']['item'], 0, 5), ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <p class='btn btn-secondary btn-block btn-sm'>Sem votação</p>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- /* Admin pode votar */ -->
                                            <?php elseif ($usuario['role'] == 'admin'): ?>
                                                <?php if (count($c_item['Votacao']) > 0): ?>
                                                    <!-- se for um item de inclusão, não tem que aparecer o botão de votar -->
                                                    <?php if (substr($c_item['Item']['item'], 3, 2) == '99'): ?>
                                                        <li class="nav-item">
                                                            <p class = 'btn btn-info btn-block btn-sm'>Item incluído por votação</p>
                                                        </li>
                                                    <?php else: ?>
                                                        <li class="nav-item">
                                                            <?php echo $this->Html->link(__('Votação ou inclusão'), '/votacaos/add/' . $c_item['Item']['id'], ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item_id:' . $c_item['Item']['id'], ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <p class='btn btn-muted btn-block btn-sm'>Sem votação</p>
                                                    </li>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votação ou inclusão'), '/votacaos/add/' . $c_item['Item']['id'], ['class' => 'btn btn-secondary btn-sm']); ?>
                                                    </li>
                                                <?php endif; ?>

                                            <?php endif; ?>

                                        <?php else: ?>
                                            <!-- /* Visitante não vota */ -->
                                            <?php if (count($c_item['Votacao']) > 0): ?>
                                                <li class="nav-item">
                                                    <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item:' . substr($c_item['Item']['item'], 0, 5), ['class' => 'btn btn-secondary']); ?>
                                                </li>
                                            <?php else: ?>
                                                <li class="nav-item">
                                                    <p class='btn btn-secondary btn-block btn-sm'>Sem votação</p>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
    </div>

</div>
<div class="row justify-content-center">
    <p>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
        ));
        ?>
    </p>
</div>
<div class="pagination justify-content-center">
    <?php
    echo $this->Paginator->prev('< ' . __('previous'), array('class' => 'page-link'), null, array('class' => 'page-link'));
    echo $this->Paginator->numbers(array('separator' => '', 'class' => 'page-link'), ['class' => 'page-link']);
    echo $this->Paginator->next(__('next') . ' >', array('class' => 'page-link'), null, array('class' => 'page-link'));
    ?>
</div>

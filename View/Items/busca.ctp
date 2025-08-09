<?php // pr($items);  ?>
<?php // pr($tr);  ?>
<?php // pr($votacao);  ?>
<?php // pr($evento_id);  ?>
<?php // pr($eventos);  ?>
<?php // pr($usuario);  ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class='navbar-nav mr-auto'>
        <li class='nav-item'>
            <?php echo $this->Html->link(__('Listar'), ['action' => 'index'], ['class' => 'nav-link']); ?>
        </li>
    </ul>
</nav>

<div class="row justify-content-center">
    <div class="form-group mx-2">
        <?php echo $this->Form->create('Evento', ['type' => 'get', 'class' => 'form-inline']); ?>
        <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Eventos', 'class' => 'd-inline-block p-1 form-label'], 'options' => $eventos, 'default' => $evento_id, 'class' => 'form-control']); ?>
    </div>
    <div class="form-group mx-2">
        <?php
        echo $this->Form->input('termo', [
            'class' => 'form-control',
            'placeholder' => 'Digite sua busca...',
            'label' => false,
            'value' => $termo
        ]);
        ?>
    </div>
    <?php echo $this->Form->submit('Buscar', ['class' => 'btn btn-primary mx-2']); ?>
    <?php echo $this->Form->end(); ?>
</div>

<div class="row">
    <div class="col-2">
        <h3 class="h2"><?php echo __('TRs'); ?></h3>
        <ul class="list-group">
            <?php
            if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')):
                ?>
                <li class="list-group-item">
                    <?php
                    if (isset($evento_id)):
                        echo $this->Html->link(__('Inserir item em TR'), ['action' => 'add', '?' => ['evento_id' => $evento_id]], ['class' => 'list-group-link']);
                    endif;
                    ?>
                </li>
                <?php
            endif;
            ?>

            <?php
            if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
                ?>
                <li class="list-group-item">
                    <?php
                    echo $this->Html->link('Ver votações', ['controller' => 'Votacaos', 'action' => 'index', '?' => ['evento_id' => $evento_id]]);
                    ?>
                </li>
                <?php
            elseif (isset($usuario) && $usuario['role'] == 'relator'):
                ?>
                <li class="list-group-item">
                    <?php
                    echo $this->Html->link('Ver votações', ['controller' => 'Votacaos', 'action' => 'index', '?' => ['grupo' => substr($usuario['username'], 5, 2), 'evento_id' => $evento_id]]);
                    ?>
                </li>
                <?php
            endif;
            ?>

            <?php foreach ($tr as $c_tr): ?>
                <?php if (isset($usuario)): ?>
                    <?php if ($usuario['role'] == 'relator'): ?>
                        <li class="list-group-item">
                            <?php echo $this->Html->link('TR: ' . $c_tr['Item']['tr'], ['action' => 'index', '?' => ['tr' => $c_tr['Item']['tr'], 'grupo' => substr($usuario['username'], 5, 2), 'evento_id' => $evento_id]]); ?>
                        </li>
                    <?php elseif ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                        <li class="list-group-item">
                            <?php echo $this->Html->link('TR: ' . $c_tr['Item']['tr'], ['action' => 'index', '?' => ['tr' => $c_tr['Item']['tr'], 'evento_id' => $evento_id]]); ?>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="list-group-item">
                        <?php echo $this->Html->link('TR: ' . $c_tr['Item']['tr'], ['action' => 'index', '?' => ['tr' => $c_tr['Item']['tr'], 'evento_id' => $evento_id]]); ?>
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
                        <th><?php echo $this->Paginator->sort('item', 'Id'); ?></th>
                        <th><?php echo $this->Paginator->sort('tr', 'TR'); ?></th>
                        <th><?php echo $this->Paginator->sort('item', 'item'); ?></th>
                        <th><?php echo $this->Paginator->sort('texto', 'Texto'); ?></th>
                        <th class="table-secondary"><?php echo __('Acões'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($items)): ?>
                        <?php foreach ($items as $c_item): ?>
                            <?php // pr($c_item); ?>
                            <tr>
                                <td>
                                    <?php
                                    if (isset($usuario)):
                                        if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                                            echo $this->Html->link($c_item['Item']['id'], ['action' => 'view', $c_item['Item']['id']]);
                                        elseif ($usuario['role'] == 'relator'):
                                            echo $this->Html->link($c_item['Item']['id'], ['action', '?' => ['view', $c_item['Item']['id'], 'grupo' => substr($usuario['username'], 5, 2)]]);
                                        endif;
                                    else:
                                        echo $this->Html->link($c_item['Item']['id'], ['action' => 'view', $c_item['Item']['id']]);
                                    endif;
                                    ?>&nbsp;
                                </td>

                                <td>
                                    <?php
                                    if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')):
                                        echo $this->Html->link($c_item['Item']['tr'], ['controller' => 'Apoios', 'action' => 'view', $c_item['Apoio']['id']]);
                                    elseif (isset($usuario) && $usuario['role'] == 'relator'):
                                        echo $this->Html->link($c_item['Item']['tr'], ['controller' => 'Apoios', 'action' => 'view', $c_item['Apoio']['id']]);
                                    else:
                                        echo $this->Html->link($c_item['Item']['tr'], ['controller' => 'Apoios', 'action' => 'view', $c_item['Apoio']['id']]);
                                    endif;
                                    ?>&nbsp;
                                </td>

                                <td>
                                    <?= $this->Html->link(
                                        $c_item['Item']['item'],
                                        ['controller' => 'Items', 'action' => 'view', $c_item['Item']['id']],
                                        ['title' => strip_tags(html_entity_decode($c_item['Item']['texto'], ENT_QUOTES, 'UTF-8')), 'class' => 'text-decoration-none']
                                    ); ?>
                                    &nbsp;
                                </td>

                                <td>
                                    <?php
                                    $posicao = strpos(html_entity_decode($c_item['Item']['texto'], ENT_QUOTES, 'UTF-8'), ".</p>");
                                    if ($posicao < 150) {
                                        echo $this->Text->truncate(html_entity_decode($c_item['Item']['texto'], ENT_QUOTES, 'UTF-8'), $posicao, ['ellipsis' => ' ...', 'exact' => false]);
                                    } else {
                                        echo $this->Text->truncate($c_item['Item']['texto'], 500, ['ellipsis' => ' ...', 'exact' => false]);
                                    }
                                    ?>&nbsp;
                                </td>

                                <td class="row">

                                    <ul class="nav">
                                        <?php if (isset($usuario)): ?>

                                            <!-- /** Relator vota */ -->
                                            <?php if ($usuario['role'] == 'relator'): ?>
                                                <!-- se for um item de inclusão, não tem que aparecer o botão de votar -->
                                                <?php if (isset($c_item['Votacao']['resultado']) && $c_item['Votacao']['resultado'] == 'inclusão'): ?>
                                                    <li class="nav-item">
                                                        <p class='btn btn-secondary btn-block btn-sm'>Item incluído</p>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votação ou inclusão'), ['controller' => 'votacaos', 'action' => 'add', '?' => ['item_id' => $c_item['Item']['id'], 'grupo' => substr($usuario['username'], 5, 2)]], ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if (sizeof($c_item['Votacao']) > 0): ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), ['controller' => 'votacaos', 'action' => 'index', '?' => ['item_id' => $c_item['Item']['id']]], ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <p class='btn btn-secondary btn-block btn-sm'>Sem votação</p>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- /** Editor não vota */  -->
                                            <?php elseif ($usuario['role'] == 'editor'): ?>
                                                <?php if (count($c_item['Votacao']) > 0): ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), ['controller' => 'votacaos', 'action' => 'index', '?' => ['item_id' => $c_item['Item']['id']]], ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <p class='btn btn-secondary btn-block btn-sm'>Sem votação</p>
                                                    </li>
                                                <?php endif; ?>

                                                <!-- /** Admin pode votar */ -->
                                            <?php elseif ($usuario['role'] == 'admin'): ?>
                                                <?php if (count($c_item['Votacao']) > 0): ?>
                                                    <!-- se for um item de inclusão, não tem que aparecer o botão de votar -->
                                                    <?php if ($c_item['Votacao'][0]['resultado'] == 'inclusão'): ?>
                                                        <li class="nav-item">
                                                            <p class='btn btn-info btn-block btn-sm'>Item incluído</p>
                                                        </li>
                                                    <?php else: ?>
                                                        <li class="nav-item">
                                                            <?php echo $this->Html->link(__('Votar ou incluir'), ['controller' => 'votacaos', 'action' => 'add', '?' => ['item_id' => $c_item['Item']['id']]], ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), ['controller' => 'votacaos', 'action' => 'index', '?' => ['item_id' => $c_item['Item']['id']]], ['class' => 'btn btn-secondary btn-block btn-sm']); ?>
                                                    </li>
                                                <?php else: ?>
                                                    <li class="nav-item">
                                                        <p class='btn btn-muted btn-block btn-sm'>Sem votação</p>
                                                    </li>
                                                    <li class="nav-item">
                                                        <?php echo $this->Html->link(__('Votação ou inclusão'), ['controller' => 'votacaos', 'action' => 'add', '?' => ['item_id' => $c_item['Item']['id']]], ['class' => 'btn btn-secondary btn-sm']); ?>
                                                    </li>
                                                <?php endif; ?>

                                            <?php endif; ?>

                                        <?php else: ?>
                                            <!-- /* Visitante não vota */ -->
                                            <?php if (count($c_item['Votacao']) > 0): ?>
                                                <li class="nav-item">
                                                    <?php echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), ['controller' => 'votacaos', 'action' => 'index', '?' => ['item' => $c_item['Item']['item'], 'evento_id' => $c_item['Apoio']['evento_id']]], ['class' => 'btn btn-secondary']); ?>
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
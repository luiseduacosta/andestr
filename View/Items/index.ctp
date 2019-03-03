<?php // pr($items);                                                            ?>
<?php // pr($tr);                                                          ?>
<?php // pr($votacao);                                                               ?>

<div class="items index">
    <h2><?php echo __('TR por Items'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('id', 'TR'); ?></th>
                <th><?php echo $this->Paginator->sort('item', 'Item'); ?></th>
                <th><?php echo $this->Paginator->sort('texto', 'Texto'); ?></th>
                <th class="actions"><?php echo __('Acões'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($items)): ?>
            <?php foreach ($items as $c_item): ?>
            <?php // pr($c_item); ?>
                <tr>
                    <td><?php
                        if (isset($usuario)):
                            if ($usuario['papel'] == 'admin' || $usuario['papel'] == 'editor'):
                                echo $this->Html->link(substr($c_item['Item']['item'], 0, 2), 'index/tr:' . substr($c_item['Item']['item'], 0, 2));
                            elseif ($usuario['papel'] == 'relator'):
                                echo $this->Html->link(substr($c_item['Item']['item'], 0, 2), 'index/tr:' . substr($c_item['Item']['item'], 0, 2) . '/grupo:' . $usuario['grupo']);
                            endif;
                        else:
                            echo $this->Html->link(substr($c_item['Item']['item'], 0, 2), 'index/tr:' . substr($c_item['Item']['item'], 0, 2));
                        endif;
                        ?>&nbsp;</td>

                    <td><?php
                        if (isset($usuario)):
                            if ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                                echo $this->Html->link($c_item['Item']['item'], 'view/' . $c_item['Item']['id']);
                            elseif ($usuario['papel'] == 'relator'):
                                echo $this->Html->link($c_item['Item']['item'], 'view/' . $c_item['Item']['id'] . '/grupo:' . $usuario['grupo']);
                            endif;
                        else:
                            echo $this->Html->link($c_item['Item']['item'], 'view/' . $c_item['Item']['id']);
                        endif;
                        ?>&nbsp;</td>

                    <td><?php echo $this->Text->truncate($c_item['Item']['texto'], 500, array('ellipsis' => ' ...', 'exact' => false)); ?>&nbsp;</td>

                    <td class="actions">

                        <?php
                        if (isset($usuario)):

                            /* Relator vota */
                            if ($usuario['papel'] == 'relator'):
                                echo $this->Html->link(__('Registrar votação'), '/votacaos/add/' . $c_item['Item']['id'] . '/grupo:' . $usuario['grupo']);
                                if (count($c_item['Votacao']) > 0):
                                    echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item:' . substr($c_item['Item']['item'], 0, 5));
                                else:
                                    echo "Sem votação";
                                endif;
                            
                            /* Editor não vota */
                            elseif ($usuario['papel'] == 'editor'):
                                if (count($c_item['Votacao']) > 0):
                                    echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item:' . substr($c_item['Item']['item'], 0, 5));
                                else:
                                    echo "Sem votação";
                                endif;

                            /* Admin pode votar */
                            elseif ($usuario['papel'] == 'admin'):
                                if (count($c_item['Votacao']) > 0):
                                    echo $this->Html->link(__('Registrar votação'), '/votacaos/add/' . $c_item['Item']['id'] . '/item' . substr($c_item['Item']['item'], 0, 5));
                                    echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item:' . substr($c_item['Item']['item'], 0, 5));
                                else:
                                    echo $this->Html->link(__('Sem votação'), '/votacaos/add/' . $c_item['Item']['id']);
                                    echo $this->Html->link(__('Registrar votação'), '/votacaos/add/' . $c_item['Item']['id']);
                                endif;

                            endif;

                        else:
                            /* Visitante não vota */
                            if (count($c_item['Votacao']) > 0):
                                echo 'Votações: ' . count($c_item['Votacao']);
                            // echo $this->Html->link(__('Votações: ') . count($c_item['Votacao']), '/votacaos/index/item:' . substr($c_item['Item']['item'], 0, 5));
                            else:
                                echo "Sem votação";
                            endif;
                        endif;
                        ?> 

                    </td>
                </tr>
            <?php endforeach; ?>
           <?php endif; ?>     
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

    <h3><?php echo __('Acões'); ?></h3>
    <ul>

        <li><?php
            if (isset($usuario)):
                if ($usuario['papel'] == 'admin' || $usuario['papel'] == 'editor'):
                    // echo $usuario['papel'];
                    echo $this->Html->link(__('Inserir item em TR'), array('action' => 'add'));
                endif;
            endif;
            ?>
        </li>

        <li><?php
            if (isset($usuario['papel'])):
                if ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                    echo $this->Html->link('Ver votações', '/Votacaos/index');
                elseif ($usuario['papel'] == 'relator'):
                    echo $this->Html->link('Ver votações', '/Votacaos/index' . '/grupo:' . $usuario['grupo']);
                endif;
            endif;
            ?>
        </li>

        <li><?php
            if (isset($usuario['papel'])):
                if ($usuario['papel'] == 'editor'):
                    echo "Editor não vota";
                elseif ($usuario['papel'] == 'admin'):
                    echo $this->Html->link('Inserir votação', '/Votacaos/add');
                elseif ($usuario['papel'] == 'relator'):
                    echo $this->Html->link('Inserir votação (novo item)', '/Votacaos/add' . '/grupo:' . $usuario['grupo'] . '/tr:' . substr($c_item['Item']['item'], 0, 2));
                endif;
            endif;
            ?>
        </li>


        <?php foreach ($tr as $c_tr): ?>

            <?php if (isset($usuario)): ?>
                <?php if ($usuario['papel'] == 'relator'): ?>
                    <li><?php echo $this->Html->link('TR: ' . $c_tr['items']['tr'], 'index/tr:' . $c_tr['items']['tr'] . '/grupo:' . $usuario['grupo']); ?></li>

                <?php elseif ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'): ?>
                    <li><?php echo $this->Html->link('TR: ' . $c_tr['items']['tr'], 'index/tr:' . $c_tr['items']['tr']); ?></li>

                <?php endif; ?>
            <?php else: ?>

                    <li><?php echo $this->Html->link('TR: ' . $c_tr['items']['tr'], 'index/tr:' . $c_tr['items']['tr']); ?></li>                        
            <?php endif; ?>

        <?php endforeach; ?>

    </ul>

</div>

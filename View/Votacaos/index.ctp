<?php // pr($votacaos);                                  ?>

<div class="votacaos index">

    <?php // echo $this->Html->link('Votação de inclusão de novo item', 'add'); ?>

    <div class="paging">
        <?php
        echo $this->Paginator->prev('< ' . __('anterior'), array(), null, array('class' => 'prev disabled'));
        echo $this->Paginator->numbers(array('separator' => ''));
        echo $this->Paginator->next(__('próxino') . ' >', array(), null, array('class' => 'next disabled'));
        ?>
    </div>

    <table>
        <tr>
            <th>
                <?php echo $this->Paginator->sort('id');
                ?>
            </th>

            <th>
                <?php echo $this->Paginator->sort('grupo');
                ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('tr');
                ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('tr_suprimida', 'Suprimida');
                ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('item');
                ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('resultado');
                ?>
            </th>
            <th>
                <?php echo $this->Paginator->sort('votacao');
                ?>
            </th>

        </tr>

        <?php foreach ($votacaos as $c_votacaos): ?>
            <tr>
                <td>
                    <?php
                    echo $c_votacaos['Votacao']['id'];
                    ?>
                </td>

                <td>
                    <?php
                    if (isset($usuario['papel'])):
                        if ($usuario['papel'] == 'relator'):
                            echo $this->Html->link($c_votacaos['Votacao']['grupo'], 'index/grupo:' . $c_votacaos['Votacao']['grupo'] . '/grupo:' . $usuario['grupo']);
                        elseif ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                            echo $this->Html->link($c_votacaos['Votacao']['grupo'], 'index/grupo:' . $c_votacaos['Votacao']['grupo']);
                        endif;
                    else:
                        echo $c_votacaos['Votacao']['grupo'];
                    endif;
                    ?>
                </td>

                <td>
                    <?php
                    if (isset($usuario['papel'])):
                        if ($usuario['papel'] == 'relator'):
                            echo $this->Html->link($c_votacaos['Votacao']['tr'], 'index/tr:' . $c_votacaos['Votacao']['tr'] . '/grupo:' . $usuario['grupo']);
                        elseif ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                            echo $this->Html->link($c_votacaos['Votacao']['tr'], 'index/tr:' . $c_votacaos['Votacao']['tr']);
                        endif;
                    else:
                        echo $c_votacaos['Votacao']['tr'];
                    endif;
                    ?>
                </td>

                <td>
                    <?php
                    echo $c_votacaos['Votacao']['tr_suprimida'];
                    ?>
                </td>

                <td>
                    <?php
                    if (isset($usuario['papel'])):
                        if ($usuario['papel'] == 'relator'):
                            echo $this->Html->link($c_votacaos['Votacao']['item'], 'index/item:' . $c_votacaos['Votacao']['item'] . '/grupo:' . $usuario['grupo']);
                        elseif ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'):
                            echo $this->Html->link($c_votacaos['Votacao']['item'], 'index/item:' . $c_votacaos['Votacao']['item']);
                        endif;
                    else:
                        echo $c_votacaos['Votacao']['item'];
                    endif;
                    ?>
                </td>

                <td>
                    <?php
                    if (isset($usuario['papel'])):
                        if ($usuario['papel'] == 'relator'):
                            echo $this->Html->link($c_votacaos['Votacao']['resultado'], 'view/' . $c_votacaos['Votacao']['id'] . '/grupo:' . $usuario['grupo']);
                        elseif ($usuario['papel'] == 'editor'):
                            echo $c_votacaos['Votacao']['resultado'];
                        elseif ($usuario['papel'] == 'admin'):
                            echo $this->Html->link($c_votacaos['Votacao']['resultado'], 'view/' . $c_votacaos['Votacao']['id'] . '/grupo:' . $c_votacaos['Votacao']['grupo']);
                        endif;
                    else:
                        echo $c_votacaos['Votacao']['resultado'];
                    endif;
                    ?>
                </td>

                <td>
                    <?php
                    echo $c_votacaos['Votacao']['votacao'];
                    ?>
                </td>

            </tr>
        <?php endforeach; ?>                

    </table>

    <?php
    echo $this->Paginator->counter(array(
        'format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} em total, começando no registro {:start} e finalizando no {:end}')
    ));
    ?>

</div>

<div class="actions">

    <?php
    if (isset($usuario['papel'])):
        if ($usuario['papel'] == 'relator'):
            echo $this->Html->link('Votação de novo item ', '/Items/index/' . '/grupo:' . $usuario['grupo']);
        endif;
    endif;
    ?>

    <?php echo "<p></p>"; ?>

    <h3><?php echo __('Grupos'); ?></h3>
    <?php
    if (isset($usuario)):
        // pr($usuario);
        if ($usuario['papel'] == 'admin' || $usuario['papel'] == 'editor'):
            foreach ($grupos as $c_grupo):
                echo "<p>";
                echo $this->Html->link('Grupo ' . $c_grupo['Votacao']['grupo'], 'index/grupo:' . $c_grupo['Votacao']['grupo']);
                echo '</p>';
            endforeach;
        endif;
    endif;
    ?>

</div>

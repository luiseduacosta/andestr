<?php
// pr($votacaos);  
// pr($usuario);
// pr($evento_id);
// pr($eventos);
?>

<script>
    $(document).ready(function () {
        var url = "<?= $this->Html->url(['controller' => 'votacaos', 'action' => 'index?evento_id=']); ?>";
        $("#EventoEventoId").change(function () {
            var evento_id = $(this).val();
            /* alert(evento_id); */
            window.location = url + evento_id;
        })
    })
</script>

<div class="row justify-content-center">
    <div class="col-auto">
        <?php if (isset($evento_id)): ?>
            <?php echo $this->Form->create('Evento', ['class' => 'form-inline']); ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Eventos', 'class' => 'd-inline-block p-1 form-label'], 'options' => $eventos, 'value' => $evento_id, 'class' => 'form-control']); ?>
            <?php echo $this->Form->end(); ?>
        <?php else: ?>
            <p class="text-center text-secondary h2"><?php echo end($eventos); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="container">

    <div class="row">

        <div class="col-2">

            <div class="row justify-content-center">
                <div class="col-auto p-4">
                    <!-- verificar como se faz isto  -->
                    <?php
                    if (isset($usuario) && ($usuario['role'] == 'relator' || $usuario['role'] == 'admin')):
                        echo $this->Html->link('Votação de novo item ', ['controller' => 'Items', 'action' => 'index', '?' => ['evento_id' => $evento_id, 'grupo' => isset($usuariogrupo) ? $usuariogrupo : null]], ['class' => ['btn btn-secondary']]);
                    elseif (isset($usuario) && $usuario['role'] == 'editor'):
                        echo "<h3>Grupos</h3>";
                    endif;
                    ?>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-auto border-right">

                    <?php
                    if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')):
                        // pr($usuario);
                        foreach ($grupos as $c_grupo):
                            echo "<p>";
                            echo $this->Html->link('Grupo ' . $c_grupo['Votacao']['grupo'], ['index', '?' => ['grupo' => $c_grupo['Votacao']['grupo'], 'evento_id' => $evento_id]], ['class' => 'btn btn-secondary']);
                            echo '</p>';
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>

        </div>

        <div class="col-8">

            <div class="row p-3 justify-content-center">
                <div class='pagination'>
                    <?= $this->Paginator->first('Primeiro ', ['class' => 'page-link']) ?>
                    <?= $this->Paginator->prev(' Anterior ', ['class' => 'page-link'], null, []) ?>
                    <?= $this->Paginator->numbers(['separator' => false, 'class' => 'page-link']) ?>
                    <?= $this->Paginator->next(' Posterior ', ['class' => 'page-link'], null, []) ?>
                    <?= $this->Paginator->last(' Último ', ['class' => 'page-link']) ?>
                </div>
            </div>

            <div class="row">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="table-info">
                                <?php echo $this->Paginator->sort('id', 'Ação');
                                ?>
                            </th>

                            <th>
                                <?php echo $this->Paginator->sort('grupo');
                                ?>
                            </th>
                            <th>
                                <?php echo $this->Paginator->sort('tr', 'TR');
                                ?>
                            </th>
                            <th>
                                <?php echo $this->Paginator->sort('tr_suprimida', 'TR suprimida');
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
                                <?php echo $this->Paginator->sort('votacao', 'Votação');
                                ?>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($votacaos as $c_votacaos): ?>
                            <tr>
                                <td class="table-info">
                                    <?php
                                    if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')):
                                        echo $this->Html->link('Ver', ['controller' => 'votacaos', 'action' => 'view', $c_votacaos['Votacao']['id']]);
                                    else:
                                        echo $c_votacaos['Votacao']['id'];
                                    endif;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($usuario)):
                                        if ($usuario['role'] == 'relator'):
                                            echo $this->Html->link($c_votacaos['Votacao']['grupo'], ['action' => 'index', '?' => ['grupo' => $c_votacaos['Votacao']['grupo'], 'evento_id' => $c_votacaos['Votacao']['evento_id']]]);
                                        elseif ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                                            echo $this->Html->link($c_votacaos['Votacao']['grupo'], ['action' => 'index', '?' => ['grupo' => $c_votacaos['Votacao']['grupo'], 'evento_id' => $c_votacaos['Votacao']['evento_id']]]);
                                        endif;
                                    else:
                                        echo $c_votacaos['Votacao']['grupo'];
                                    endif;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($usuario['role'])):
                                        if ($usuario['role'] == 'relator'):
                                            echo $this->Html->link($c_votacaos['Votacao']['tr'], ['action' => 'index', '?' => ['tr' => $c_votacaos['Votacao']['tr'], 'grupo' => isset($usuariogrupo) ? $usuariogrupo : null, 'evento_id' => $c_votacaos['Votacao']['evento_id']]]);
                                        elseif ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                                            echo $this->Html->link($c_votacaos['Votacao']['tr'], ['action' => 'index', '?' => ['tr' => $c_votacaos['Votacao']['tr'], 'evento_id' => $c_votacaos['Votacao']['evento_id']]]);
                                        endif;
                                    else:
                                        echo $c_votacaos['Votacao']['tr'];
                                    endif;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo $c_votacaos['Votacao']['tr_suprimida'] == 0 ? 'Não' : 'Sim';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($usuario) && $usuario['role'] == 'relator'):
                                        echo $this->Html->link($c_votacaos['Votacao']['item'], 
                                        ['action' => 'index', '?' => ['item_id' => $c_votacaos['Votacao']['item_id'], 'grupo' => isset($usuariogrupo) ? $usuariogrupo : null, 'evento_id' => $c_votacaos['Votacao']['evento_id']]],
                                        ['title' => strip_tags(html_entity_decode($c_votacaos['Item']['texto'], ENT_QUOTES, 'UTF-8'))]
                                    );
                                    elseif (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
                                        echo $this->Html->link($c_votacaos['Votacao']['item'], 
                                        ['action' => 'index', '?' => ['item_id' => $c_votacaos['Votacao']['item_id'], 'evento_id' => $c_votacaos['Votacao']['evento_id']]],
                                        ['title' => strip_tags(html_entity_decode($c_votacaos['Item']['texto'], ENT_QUOTES, 'UTF-8'))]
                                    );
                                    else:
                                        echo $this->Html->link($c_votacaos['Votacao']['item'], 
                                        ['controller' => 'Items', 'action' => 'view', $c_votacaos['Votacao']['item_id']],
                                        ['title' => strip_tags(html_entity_decode($c_votacaos['Item']['texto'], ENT_QUOTES, 'UTF-8'))]
                                    );
                                        // echo '<abbr title="' . strip_tags(html_entity_decode($c_votacaos['Item']['texto'], ENT_QUOTES, 'UTF-8')) . '">' . $c_votacaos['Votacao']['item'] . '</abbr>';
                                    endif;
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if (isset($usuario['role'])):
                                        if ($usuario['role'] == 'relator'):
                                            echo $this->Html->link($c_votacaos['Votacao']['resultado'], ['action' => 'view', $c_votacaos['Votacao']['id']]);
                                        elseif ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                                            echo $this->Html->link($c_votacaos['Votacao']['resultado'], ['action' => 'view', $c_votacaos['Votacao']['id']]);
                                        endif;
                                    else:
                                        if ($c_votacaos['Votacao']['resultado'] == 'modificada' || $c_votacaos['Votacao']['resultado'] == 'inclusão'):
                                            echo $this->Html->link($c_votacaos['Votacao']['resultado'], ['action' => 'view', $c_votacaos['Votacao']['id']]);
                                        else:
                                            echo $c_votacaos['Votacao']['resultado'];
                                        endif;
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} em total, começando no registro {:start} e finalizando no {:end}')
        ));
        ?>
    </div>
</div>
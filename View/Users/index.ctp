<?php // pr($users); ?>
<?php // pr($usuario); ?>
<div class="container">

    <?php // echo $this->Html->link('Votação de inclusão de novo item', 'add'); ?>

    <div class="pagination justify-content-center">
        <?php
        echo $this->Paginator->prev('< ' . __('anterior'), array('class' => 'page-link'), null, array('class' => 'page-link'));
        echo $this->Paginator->numbers(array('separator' => '', 'class' => 'page-link'));
        echo $this->Paginator->next(__('próxino') . ' >', array('class' => 'page-link'), null, array('class' => 'page-link'));
        ?>
    </div>

    <table class="table table-striped table-hover">
        <thead class="thead-light">
            <tr>
                <th>
                    <?php echo $this->Paginator->sort('id');
                    ?>
                </th>

                <th>
                    <?php echo $this->Paginator->sort('username', 'Usuário');
                    ?>
                </th>
                <th>
                    <?php echo $this->Paginator->sort('role', 'Papel');
                    ?>
                </th>
                <th>
                    <?php echo $this->Paginator->sort('created', 'Criado');
                    ?>
                </th>
                <th>
                    <?php echo $this->Paginator->sort('modified', 'Modificado');
                    ?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $c_user): ?>
                <?php // pr($c_user); ?>
                <?php if ($usuario['role'] == 'admin'): ?>
                    <tr>
                        <td>
                            <?php
                            echo $c_user['User']['id'];
                            ?>
                        </td>

                        <td>
                            <?php
                            if (isset($usuario['role']) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')):
                                echo $this->Html->link($c_user['User']['username'], ['controller' => 'users', 'action' => 'view', $c_user['User']['id']]);
                            else:
                                echo $c_user['User']['username'];
                            endif;
                            ?>
                        </td>

                        <td>
                            <?php
                            echo $c_user['User']['role'];
                            ?>
                        </td>

                        <td>
                            <?php
                            echo $c_user['User']['created'];
                            ?>
                        </td>

                        <td>
                            <?php
                            echo $c_user['User']['modified'];
                            ?>
                        </td>

                    </tr>
                <?php elseif ($usuario['role'] == 'editor'): ?>
                    <!-- Editor não precisa ver ao admin nem alterar a senha do admin -->
                    <?php if ($c_user['User']['role'] != 'admin'): ?>
                        <?php // echo $c_user['User']['username'] . "<br>"; ?>
                        <tr>
                            <td>
                                <?php
                                echo $c_user['User']['id'];
                                ?>
                            </td>

                            <td>
                                <?php
                                if (isset($usuario['role']) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')):
                                    echo $this->Html->link($c_user['User']['username'], ['controller' => 'users', 'action' => 'view', $c_user['User']['id']]);
                                else:
                                    echo $c_user['User']['username'];
                                endif;
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $c_user['User']['role'];
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $c_user['User']['created'];
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $c_user['User']['modified'];
                                ?>
                            </td>

                        </tr>

                    <?php endif; ?>

                <?php endif; ?>

            <?php endforeach; ?>
        </tbody>
    </table>

    <div class = 'row'>
        <?php
        echo $this->Paginator->counter(array(
            'format' => __('Página {:page} de {:pages}, mostrando {:current} registros de {:count} em total, começando no registro {:start} e finalizando no {:end}')
        ));
        ?>
    </div>
</div>

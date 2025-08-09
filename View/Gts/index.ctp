<div class="container">

    <?php if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')): ?>
        <nav class="navbar navbar-expand-lg navbar-ligth bg-ligth">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <?php echo $this->Html->link(__('Novo GT'), ['action' => 'add'], ['class' => 'btn btn-primary btn-block']); ?>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

    <div class="container">
        <h2 class="h2"><?php echo __('Grupos de Trabalho'); ?></h2>
        <table cellpadding="0" cellspacing="0" class="table table-hover table-striped table-responsive">
            <thead class="thead-light">
                <tr>
                    <th><?php echo $this->Paginator->sort('id'); ?></th>
                    <th><?php echo $this->Paginator->sort('sigla'); ?></th>
                    <th><?php echo $this->Paginator->sort('nome'); ?></th>
                    <th><?php echo $this->Paginator->sort('outras', 'Observações'); ?></th>
                    <th colspan="3" class="table-active"><?php echo __('Ações'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gts as $gt): ?>
                    <tr>
                        <td><?php echo h($gt['Gt']['id']); ?>&nbsp;</td>
                        <td><?php echo h($gt['Gt']['sigla']); ?>&nbsp;</td>
                        <td><?php echo h($gt['Gt']['nome']); ?>&nbsp;</td>
                        <td><?php echo h($gt['Gt']['outras']); ?>&nbsp;</td>
                        <td class="table-success">
                            <?php echo $this->Html->link(__('Ver'), ['action' => 'view', $gt['Gt']['id']], ['class' => 'btn btn-primary btn-sm btn-block']); ?>
                        </td>

                        <?php
                        if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
                            ?>
                            <td class="table-success">
                                <?php
                                echo $this->Html->link(__('Editar'), ['action' => 'edit', $gt['Gt']['id']], ['class' => 'btn btn-primary btn-sm btn-block']);
                                ?>
                            </td>
                            <?php
                        endif;
                        ?>

                        <?php
                        if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
                            ?>
                            <td class="table-success">
                                <?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $gt['Gt']['id']], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $gt['Gt']['id']), 'class' => 'btn btn-danger btn-sm btn-block']); ?>
                            </td>
                            <?php
                        endif;
                        ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <p>
            <?php
            echo $this->Paginator->counter(array(
                'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
            ));
            ?>
        </p>
    </div>
    <div class="row">
        <div class="pagination">
            <?php
            echo $this->Paginator->prev('< ' . __('previous'), array('class' => 'page-link'), null, array('class' => 'page-link'));
            echo $this->Paginator->numbers(array('separator' => '', 'class' => 'page-link'));
            echo $this->Paginator->next(__('next') . ' >', array('class' => 'page-link'), null, array('class' => 'page-link'));
            ?>
        </div>
    </div>
</div>
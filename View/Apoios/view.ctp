<?php
// pr($apoio);
?>

<div class="row">

    <div class="col-3">
        <h1 class="h3"><?php echo __('Acões'); ?></h1>
        <ul class="list-group">
            <?php if (isset($usuario)): ?>
                <?php if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>

            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Html->link(__('Editar Texto de apoio'), ['action' => 'edit', $apoio['Apoio']['id']], ['class' => 'list-group-item list-group-item-action']); ?>
            </li>
            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Form->postLink(__('Excluir Texto de Apoio'), ['action' => 'delete', $apoio['Apoio']['id']], ['confirm' => __('Está seguro que quer excluir este registro # %s?', $apoio['Apoio']['id']), 'class' => 'list-group-item list-group-item-action text-truncate']); ?>
            </li>
            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Html->link(__('Listar Todos'), ['action' => 'index', '?' => ['evento_id' => $apoio['Apoio']['evento_id']]], ['class' => 'list-group-item list-group-item-action text-truncate']); ?>
            </li>
            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Html->link(__('Novo Texto de Apoio'), ['action' => 'add', '?' => ['evento_id' => $apoio['Apoio']['evento_id']]], ['class' => 'list-group-item list-group-item-action text-truncate']); ?>
            </li>
            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Html->link(__('Eventos'), ['controller' => 'Eventos', 'action' => 'index', '?' => ['evento_id' => $apoio['Apoio']['evento_id']]], ['class' => 'list-group-item list-group-item-action text-truncate']); ?>
            </li>
            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Html->link(__('Inserir TR'), ['controller' => 'Items', 'action' => 'add', '?' => ['evento_id' => $apoio['Apoio']['evento_id']]], ['class' => 'list-group-item list-group-item-action text-truncate']); ?>
            </li>
                <?php else: ?>
            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Html->link(__('Eventos'), ['controller' => 'Eventos', 'action' => 'index', '?' => ['evento_id' => $apoio['Apoio']['evento_id']]], ['class' => 'list-group-item list-group-item-action text-truncate']); ?>
            </li>
            <li class='list-group-item list-group-item-action'>
                        <?php echo $this->Html->link(__('Listar Todos Apoios'), ['action' => 'index', '?' => ['evento_id' => $apoio['Apoio']['id']]], ['class' => 'list-group-item list-group-item-action text-truncate']); ?>
            </li>

                <?php endif; ?>
            <?php endif; ?>

        </ul>
    </div>

    <div class="col-7">
        <h1 class="h3"><?php echo __('Texto de apoio: ' . $apoio['Apoio']['numero_texto']); ?></h1>
        <dl class="row">
            <dt class="col-sm-3"><?php echo __('Id'); ?></dt>
            <dd class="col-sm-9">
                <?php echo h($apoio['Apoio']['id']); ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('Evento'); ?></dt>
            <dd class="col-sm-9">
                <?php echo h($apoio['Evento']['nome']); ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('Caderno'); ?></dt>
            <dd class="col-sm-9">
                <?php echo h($apoio['Apoio']['caderno']); ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('Texto número: '); ?></dt>
            <dd class="col-sm-9">
                <?php echo h($apoio['Apoio']['numero_texto']); ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('Tema'); ?></dt>
            <dd class="col-sm-9">
                <?php echo h($apoio['Apoio']['tema']); ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('GT'); ?></dt>
            <dd class="col-sm-9">
                <?php echo h($apoio['Apoio']['gt']); ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('Titulo'); ?></dt>
            <dd class="col-sm-9">
                <?php echo h($apoio['Apoio']['titulo']); ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('Autor(es)'); ?></dt>
            <dd class="col-sm-9">
                <?php
                echo $this->Text->truncate($apoio['Apoio']['autor'], 200, ['ellipsis' => ' ...', 'exact' => false]);
                ?>
                &nbsp;
            </dd>

            <dt class="col-sm-3"><?php echo __('Texto de apoio'); ?></dt>
            <dd class="col-sm-9">
                <?php
                echo $this->Text->truncate($apoio['Apoio']['texto'], 200, ['ellipsis' => $this->Html->link(' ...', 'apoiocompleto/' . $apoio['Apoio']['id']), 'exact' => false]);
                ?>
                &nbsp;
            </dd>
        </dl>

        <div class="row">
            <?php if (count($apoio['Item']) > 0): ?>
            <h3><?php echo __('Texto de resoluções: ' . substr($apoio['Item'][0]['item'], 0, 2)); ?></h3>

                <?php foreach ($apoio['Item'] as $c_apoio): ?>
                    <?php // pr($c_apoio); ?>

            <dl>
                <dt><?php echo __('Item'); ?></dt>
                <dd>
                            <?php echo "<b>" . $this->Html->link($c_apoio['item'], '/Items/view/' . $c_apoio['id']) . "</b>" . " " . $c_apoio['texto']; ?>
                    &nbsp;
                </dd>
            </dl>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>
</div>
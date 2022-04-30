<?php // pr($apoio);    ?>

<div class="apoios view">
    <h2><?php echo __('Texto de apoio: ' . $apoio['Apoio']['numero_texto']); ?></h2>
    <dl>
        <dt><?php echo __('Id'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['id']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Caderno'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['caderno']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Texto número: '); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['numero_texto']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Tema'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['tema']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('GT'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['gt']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Titulo'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['titulo']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Autor(es)'); ?></dt>
        <dd>
            <?php
            echo $this->Text->truncate($apoio['Apoio']['autor'], 200, array('ellipsis' => ' ...', 'exact' => false));
            ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Texto de apoio'); ?></dt>
        <dd>
            <?php
            echo $this->Text->truncate($apoio['Apoio']['texto'], 200, array('ellipsis' => $this->Html->link(' ...', 'apoiocompleto/' . $apoio['Apoio']['id']), 'exact' => false));
            ?>
            &nbsp;
        </dd>
    </dl>

    <div class="related">
        <?php if (count($apoio['Item']) > 0): ?>
            <h3><?php echo __('Texto de resoluções: ' . substr($apoio['Item'][0]['item'], 0, 2)); ?></h3>

            <?php foreach ($apoio['Item'] as $c_apoio): ?>
                <?php // pr($c_apoio); ?>

                <dl>
                    <dt><?php echo __('Item'); ?></dt>
                    <dd>
                        <?php echo "<b>" . $this->Html->link($c_apoio['item'], '/Items/view/' . $c_apoio['id']) . "</b>" . " " . $c_apoio['texto']; ?>
                        &nbsp;</dd>
                </dl>

            <?php endforeach; ?>

        <?php endif; ?>
    </div>

</div>

<div class="actions">
    <h3><?php echo __('Acões'); ?></h3>
    <ul>
        <?php if (isset($usuario)): ?>
            <?php if ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'): ?>

                <li><?php echo $this->Html->link(__('Editar Texto de apoio'), array('action' => 'edit', $apoio['Apoio']['id'])); ?> </li>
                <li><?php echo $this->Form->postLink(__('Excluir Texto de Apoio'), array('action' => 'delete', $apoio['Apoio']['id']), array('confirm' => __('Está seguro que quer excluir este registro # %s?', $apoio['Apoio']['id']))); ?> </li>
                <li><?php echo $this->Html->link(__('Listar Todos'), array('action' => 'index')); ?> </li>
                <li><?php echo $this->Html->link(__('Novo Texto de Apoio'), array('action' => 'add')); ?> </li>

            <?php else: ?>

                <li><?php echo $this->Html->link(__('Listar Todos Apoios'), array('action' => 'index')); ?> </li>

            <?php endif; ?>
        <?php endif; ?>

    </ul>
</div>

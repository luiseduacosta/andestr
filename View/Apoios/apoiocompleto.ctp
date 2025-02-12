<div class="apoios view">
    <h2><?php echo __('Texto de Apoio'); ?></h2>
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

        <dt><?php echo __('GT'); ?></dt>
        <dd>
            <?php echo h($apoio['Apoio']['gt_id']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Titulo'); ?></dt>
        <dd>
            <?php echo strip_tags($apoio['Apoio']['titulo']); ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Autor(es)'); ?></dt>
        <dd>
            <?php
            echo strip_tags($apoio['Apoio']['autor']);
            ?>
            &nbsp;
        </dd>
        <dt><?php echo __('Texto de apoio'); ?></dt>
        <dd>
            <?php
            echo $apoio['Apoio']['texto'];
            ?>
            &nbsp;
        </dd>
    </dl>

</div>
<?php if (isset($usuario) && $usuario['role'] == 'admin' && $usuario['role'] == 'editor'): ?>

<?php endif; ?>
<div class="actions">
    <h3><?php echo __('Ações'); ?></h3>
    <ul>
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li><?php echo $this->Html->link(__('Editar este texto'), ['action' => 'edit', $apoio['Apoio']['id']]); ?> </li>
            <li><?php echo $this->Form->postLink(__('Excluir este texto'), ['action' => 'delete', $apoio['Apoio']['id']], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $apoio['Apoio']['id'])]); ?> </li>
        <?php endif; ?>
        <li><?php echo $this->Html->link(__('Textos de apoio'), ['action' => 'index']); ?> </li>
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li><?php echo $this->Html->link(__('Novo texto de apoio'), ['action' => 'add']); ?> </li>
        <?php endif; ?>
        <li><?php echo $this->Html->link(__('Resoluções'), ['controller' => 'items', 'action' => 'index']); ?> </li>
        <?php if (isset($usuario) && ($usuario['role'] == 'admin' || $usuario['role'] == 'editor')): ?>
            <li><?php echo $this->Html->link(__('Inserir resoluçao'), ['controller' => 'items', 'action' => 'add']); ?> </li>
        <?php endif; ?>
    </ul>
</div>

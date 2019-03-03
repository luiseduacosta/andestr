<?php // pr($item);       ?>
<?php // pr($votacao);    ?>

<div class="items view">

    <dl>
        <dt><?php echo __('Texto de apoio: '); ?></dt>
        <dd><?php echo $this->Html->link('Texto de apoio', '/apoios/view/' . $item['Item']['apoio_id']); ?></dd>

        <dt><?php echo __('TR: '); ?></dt>
        <dd><?php echo substr($item['Item']['item'], 0, 2); ?></dd>

    </dl>

    <h2><?php echo __('Item'); ?></h2>
    <dl>

        <dt><?php echo __('Item'); ?></dt>
        <dd>
            <?php echo h($item['Item']['item']); ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Texto'); ?></dt>
        <dd>
            <?php echo $item['Item']['texto']; ?>
            &nbsp;
        </dd>

        <dt><?php echo __('Votação'); ?></dt>
        <dd>
            <?php
            if ($item['Votacao']):
                echo $this->Html->link('Ver votação', '/votacaos/index/item:' . $item['Item']['item'], '');
            else:
                echo "Sem votação";
            endif;
            ?>
            &nbsp;
        </dd>

    </dl>
</div>

<div class="actions">
    <h3><?php echo __('Acões'); ?></h3>
    <ul>
        <?php if (isset($usuario)): ?>
        <?php // pr($usuario); ?>
            <?php if ($usuario['papel'] == 'relator'): ?>
                <li><?php echo $this->Html->link(__('Listar Items'), array('action' => 'index')); ?> </li>           
            <?php elseif ($usuario['papel'] == 'editor' || $usuario['papel'] == 'admin'): ?>
                <li><?php echo $this->Html->link(__('Novo Item'), array('action' => 'add')); ?> </li>            
                <li><?php echo $this->Html->link(__('Editar Item'), array('action' => 'edit', $item['Item']['id'])); ?> </li>
                <li><?php echo $this->Form->postLink(__('Excluir Item'), array('action' => 'delete', $item['Item']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $item['Item']['id']))); ?> </li>
                <li><?php echo $this->Html->link(__('Listar Items'), array('action' => 'index')); ?> </li>
            <?php endif; ?>
        <?php endif; ?>     
    </ul>
</div>

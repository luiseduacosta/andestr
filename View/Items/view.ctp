<?php // pr($item); ?>
<?php // pr($votacao); ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <ul class="navbar-nav mr-auto">
    <a class="navbar-brand"><?php echo __('Acões'); ?></a>
        <?php if (isset($usuario)): ?>
            <?php // pr($usuario); ?>
            <?php if ($usuario['role'] == 'relator'): ?>
                <li class="nav-item"><?php echo $this->Html->link(__('Listar Items'), ['action' => 'index', '?' => ['evento_id' => $item['Apoio']['evneto_id'], 'apoio_id' => $item['Item']['apoio_id'], 'tr' => $item['Item']['tr']]], ['class' => "nav-link"]); ?></li>
            <?php elseif ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'): ?>
                <li class="nav-item"><?php echo $this->Html->link(__('Textos de Apoio'), ['controller' => 'Apoios', 'action' => 'index', '?' => ['evento_id' => $item['Apoio']['evento_id']]], ['class' => "nav-link"]); ?> </li>
                <li class="nav-item"><?php echo $this->Html->link(__('Novo Apoio'), ['controller' => 'Apoios', 'action' => 'add', '?' => ['evento_id' => $item['Apoio']['evento_id']]], ['class' => "nav-link"]); ?> </li>
                <li class="nav-item"><?php echo $this->Html->link(__('Novo Item'), ['action' => 'add', '?' => ['evento_id' => $item['Apoio']['evento_id']]], ['class' => "nav-link"]); ?> </li>
                <li class="nav-item"><?php echo $this->Html->link(__('Editar Item'), ['action' => 'edit', $item['Item']['id']], ['class' => "nav-link"]); ?> </li>
                <li class="nav-item"><?php echo $this->Form->postLink(__('Excluir Item'), ['action' => 'delete', $item['Item']['id']], ['confirm' => __('Are you sure you want to delete # %s?'), $item['Item']['id'], 'class' => "nav-link"]); ?> </li>
                <li class="nav-item"><?php echo $this->Html->link(__('Listar Items'), ['action' => 'index', '?' => ['evento_id' => $item['Apoio']['evento_id'], 'apoio_id' => $item['Item']['apoio_id'], 'tr' => $item['Item']['tr']]], ['class' => "nav-link"]); ?> </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</nav>

<dl class="row">
    <dt class="col-sm-3"><?php echo __('Texto de apoio: '); ?></dt>
    <dd class="col-sm-9"><?php echo $this->Html->link('Texto de apoio', ['controller' => 'apoios', 'action' => 'view', $item['Item']['apoio_id']]); ?></dd>

    <dt class="col-sm-3"><?php echo __('TR: '); ?></dt>
    <dd class="col-sm-9"><?php echo $this->Html->link($item['Item']['tr'], ['controller' => 'apoios', 'action' => 'view', ($item['Apoio']['id'])]); ?></dd>

    <dt class="col-sm-3"><?php echo __('Item'); ?></dt>
    <dd class="col-sm-9">
        <?php echo h($item['Item']['item']); ?>
        &nbsp;
    </dd>

    <dt class="col-sm-3"><?php echo __('Texto'); ?></dt>
    <dd class="col-sm-9">
        <?php echo $item['Item']['texto']; ?>
        &nbsp;
    </dd>
    
    <dt class="col-sm-3"><?php echo __('Votação'); ?></dt>
    <dd class="col-sm-9">
        <?php
        if ($item['Votacao']):
            echo $this->Html->link('Ver votação', ['controller' => 'votacaos', 'action' => 'index', '?' => ['evento_id' => $item['Apoio']['evento_id'], 'item_id' => $item['Item']['id']]]);
        else:
            echo "Sem votação";
        endif;
        ?>
        &nbsp;
    </dd>
</dl>

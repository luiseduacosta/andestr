<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class='navbar-nav mr-auto'>
        <li class='nav-item'>
            <?php echo $this->Html->link(__('Listar'), ['action' => 'index'], ['class' => 'nav-link']); ?>
        </li>
    </ul>
</nav>

<div class="row justify-content-center">
    <?php echo $this->Form->create('Apoio', ['type' => 'get', 'class' => 'form-inline']); ?>
        <div class="form-group mx-2">
            <?php 
            echo $this->Form->input('evento_id', [
                'type' => 'select',
                'options' => $eventos,
                'default' => $evento_id,
                'class' => 'form-control',
                'label' => ['text' => 'Eventos', 'class' => 'd-inline-block p-1 form-label']
            ]); 
            ?>
        </div>
        <div class="form-group mx-2">
            <?php 
            echo $this->Form->input('termo', [
                'class' => 'form-control',
                'placeholder' => 'Digite sua busca...',
                'label' => false,
                'value' => $termo
            ]); 
            ?>
        </div>
        <?php echo $this->Form->submit('Buscar', ['class' => 'btn btn-primary mx-2']); ?>
    <?php echo $this->Form->end(); ?>
</div>

<?php if (!empty($apoios)): ?>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('numero_texto', 'Nº'); ?></th>
                <th><?php echo $this->Paginator->sort('titulo'); ?></th>
                <th><?php echo $this->Paginator->sort('autor'); ?></th>
                <th><?php echo $this->Paginator->sort('tema'); ?></th>
                <th><?php echo $this->Paginator->sort('gt', 'Grupo de Trabalho'); ?></th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apoios as $apoio): ?>
                <tr>
                    <td><?php echo h($apoio['Apoio']['numero_texto']); ?></td>
                    <td><?php echo $apoio['Apoio']['titulo']; ?></td>
                    <td><?php echo $apoio['Apoio']['autor']; ?></td>
                    <td><?php echo $apoio['Apoio']['tema']; ?></td>
                    <td><?php echo h($apoio['Apoio']['gt']); ?></td>
                    <td>
                        <?php echo $this->Html->link('Ver', ['action' => 'view', $apoio['Apoio']['id']], ['class' => 'btn btn-sm btn-info']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php echo $this->element('pagination'); ?>
<?php else: ?>
    <p class="text-center mt-4">Nenhum resultado encontrado.</p>
<?php endif; ?>

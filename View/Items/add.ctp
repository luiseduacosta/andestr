<?php
// pr($eventos);
// pr($evento); 
if (strlen($ultimo_tr) == 1) {
    $ultimo_tr = '0' . $ultimo_tr + 1;
} else {
    $ultimo_tr = $ultimo_tr + 1;
}
?>

<script>
    $(document).ready(function () {
        var url = "<?= $this->Html->url(['controller' => 'Items', 'action' => 'add/evento_id:']); ?>";
        $("#EventoEventoId").change(function () {
            var evento_id = $(this).val();
            /* alert(evento_id); */
            window.location = url + evento_id;
        })

    })
</script>

<div class="row justify-content-center">
    <div class="col-auto">

        <?php if (isset($usuario) && $usuario['role'] == 'admin'): ?>
            <?php echo $this->Form->create('Evento', ['class' => 'form-inline']); ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Evento', 'style' => 'display: inline;'], 'options' => $eventos, 'default' => $evento_id], ['class' => 'form-control']); ?>
            <?php echo $this->Form->end(); ?>
        <?php else: ?>
            <h1 style="text-align: center;"><?php echo end($eventos); ?></h1>
        <?php endif; ?>
    </div>
</div>

<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false)); ?>
<?php // pr($resolucaos); ?>
<?php // pr($tr); ?>

<div class="items form">

    <?php
    echo $this->Form->create('Item', [
        'class' => 'form-horizontal',
        'role' => 'form',
        'inputDefaults' => [
            'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
            'div' => ['class' => 'form-group row'],
            'label' => ['class' => 'col-3'],
            'between' => "<div class = 'col-8'>",
            'class' => ['form-control'],
            'after' => '</div>',
            'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
        ]
    ]);
    ?>
    <fieldset>
        <legend><?php echo "Items da TR "; ?></legend>
        <?php
        echo $this->Form->input('tr', ['label' => 'TR', 'value' => $ultimo_tr]);
        echo $this->Form->input('item', ['label' => 'Item. Formato nn.nn Digitar: número da TR, "." o número do item.', 'placeholder' => $ultimo_tr . '.00']);
        echo $this->Form->input('texto', ['label' => 'Item do texto de resolução'], ['class' => 'ckeditor']);
        ?>
    </fieldset>
    <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
    <?= $this->Form->end(); ?>
</div>

<div class="row-fluid">
    <h3 class='h2'><?php echo __('Acões'); ?></h3>
    <ul class='nav'>
        <?php if (isset($evento_id)): ?>
            <li clsss='' nav-link'>
                <?php echo $this->Html->link(__('Listar TRs'), ['action' => 'index', '?' => ['evento_id' => $evento_id]], ['class' => 'nav-link']); ?></li>
        <?php endif; ?>
    </ul>
</div>
<?php // pr($resolucaos); ?>
<?php // pr($trs); ?>
<?php // pr($r['Resolucao']['tr']); ?>

<h1 class="h3"><?php echo $resolucaos['Apoio']['Evento']['nome'] . ' - ' . $resolucaos['Apoio']['Evento']['data'] . ' - ' . $resolucaos['Apoio']['Evento']['local']; ?></h1>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $this->Form->value('Item.id')], ['confirm' => __('Tem certeza que quer excluir este item # %s?', $this->Form->value('Item.id')), 'class' => 'nav-link']);
            ?>
        </li>
        <li class='nav-item'>
            <?php echo $this->Html->link(__('Listar items'), ['action' => 'index'], ['class' => 'nav-link']); ?>
        </li>
    </ul>
</nav>

<div class="container">
    <?php if (!empty($resolucaos)): ?>
        <table class="table table-striped table-hover table-responsive">
            <tr>
                <td><?php echo $this->Html->link('Texto de apoio', '/apoios/view/' . $resolucaos['Apoio']['id']); ?>
                </td>
            </tr>
            <tr>
                <td><?php echo "TR: " . $resolucaos['Item']['tr']; ?></td>
            </tr>
        </table>
    <?php else: ?>
        <?php echo "<h3 class='h3'>Item sem TR!!</h3>"; ?>
    <?php endif; ?>

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
        <legend><?php echo __('Editar item'); ?></legend>
        <?php
        echo $this->Form->input('id', ['value' => $resolucaos['Item']['id'], 'type' => 'hidden']);
        echo $this->Form->input('apoio_id', ['value' => $resolucaos['Item']['apoio_id'], 'type' => 'hidden']);
        echo $this->Form->input('tr', ['label' => ['text' => "TR", 'class' => 'col-3'], 'value' => $resolucaos['Item']['tr']]);
        echo $this->Form->input('item', ['label' => ['text' => 'Item', 'class' => 'col-3']]);
        echo $this->Form->input('texto', ['type' => 'textarea', 'style' => ['font-size: 16px']]);
        ?>
    </fieldset>
    <div class='row justify-content-left'>
        <div class='col-auto'>
            <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

</div>

<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Strikethrough,
        Font,
        Paragraph,
        Table,
        TableToolbar,
        SourceEditing
    } from 'ckeditor5';

    function initTextoEditor() {
        let texto;
        if (typeof texto !== 'undefined') {
            texto.destroy()
                .then(() => {
                    console.log('Texto editor destroyed successfully');
                })
                .catch(error => {
                    console.error('Error destroying text editor:', error);
                });
        }
        ClassicEditor
            .create(document.querySelector('#ItemTexto'), {
                plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph, Table, TableToolbar, SourceEditing],
                toolbar: [
                    'sourceEditing', 'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'insertTable'
                ],
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                }
            })
            .then(editor => {
                texto = editor;
                var campoTexto = document.querySelector('#ItemTexto');
                texto.setData(campoTexto.value);
                let contenudoTexto = texto.getData();
                console.log('Conteúdo do texto:', contenudoTexto);
            })
            .catch(error => {
                console.error('Error initializing text editor:', error);
            });
    }

    // Initialize editors when the DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        initTextoEditor();
    });

</script>
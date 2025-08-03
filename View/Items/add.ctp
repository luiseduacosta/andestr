<?php
// pr($eventos);
// pr($evento); 
?>

<script>
    $(document).ready(function () {
        var url = "<?= $this->Html->url(['controller' => 'Items', 'action' => 'add/evento_id:']); ?>";
        $("#EventoEventoId").change(function () {
            var evento_id = $(this).val();
            /* alert(evento_id); */
            window.location = url + evento_id;
        })

        var tr;
        tr = "<?= isset($ultimo_tr) ? $ultimo_tr : '01'; ?>";
        $("#ItemTr").on("keyup", function () {
            var value = $(this).val();
            $("#ItemItem").val(value + "." + "00");
        });
    })

</script>

<div class="row justify-content-center">
    <div class="col-auto">
        <?php if (isset($usuario) && $usuario['role'] == 'admin'): ?>
            <?php echo $this->Form->create('Evento', ['class' => 'form-inline']); ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Evento', 'class' => 'd-inline-block p-1 form-label'], 'options' => $eventos, 'default' => $evento_id, 'class' => 'form-control']); ?>
            <?php echo $this->Form->end(); ?>
        <?php else: ?>
            <h1 style="text-align: center;"><?php echo end($eventos); ?></h1>
        <?php endif; ?>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class='navbar-nav'>
        <?php if (isset($evento_id)): ?>
            <li clsss='nav-link'>
                <?php echo $this->Html->link(__('Listar TRs'), ['action' => 'index', '?' => ['evento_id' => $evento_id]], ['class' => 'nav-link']); ?>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<?php echo $this->Html->script('ckeditor/ckeditor', ['inline' => false]); ?>

<div class="container">

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
        echo $this->Form->input('apoio_id', ['value' => $apoio_id, 'options' => $apoios]);
        echo $this->Form->input('tr', ['label' => ['text' => 'TR', 'class' => 'col-3'], 'value' => $ultimo_tr]);
        echo $this->Form->input('item', ['label' => ['text' => 'Item. Formato nn.nn Digitar: número da TR, "." o número do item.', 'class' => 'col-3'], 'value' => $ultimo_tr . "." . $item_item, 'placeholder' => '__.__']);
        echo $this->Form->input('texto', ['label' => ['text' => 'Item do texto de resolução', 'class' => 'col-3']]);
        ?>
    </fieldset>
    <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
    <?= $this->Form->end(); ?>
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
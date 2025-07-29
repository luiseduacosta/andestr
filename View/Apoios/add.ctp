<div class="row">
    <div class="col-2">
        <h3 class="h3"><?php echo __('Acões'); ?></h3>
        <ul class="list-group">
            <?php if (isset($evento_id)): ?>
                <li class="list-group-item list-group-item-action">
                    <?php echo $this->Html->link(__('Listar Apoios'), ['action' => 'index', '?' => ['evento_id' => $evento_id]]); ?>
                </li>
            <?php endif; ?>
            <?php
            if (isset($usuario)):
                if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                    ?>
                    <?php if (isset($evento_id)): ?>
                        <li class="list-group-item list-group-item-action">
                            <?php echo $this->Html->link(__('Nova TR'), ['controller' => 'Items', 'action' => 'add', '?' => ['evento_id' => $evento_id]]); ?>
                        </li>
                    <?php endif; ?>
                    <?php
                endif;
            endif;
            ?>
        </ul>
    </div>

    <div class="col-9">
        <?php
        echo $this->Form->create('Apoio', [
            'class' => 'form-horizontal',
            'role' => 'form',
            'inputDefaults' => [
                'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
                'div' => ['class' => 'form-group row'],
                'label' => ['class' => 'col-3'],
                'between' => "<div class = 'col-8'>",
                'class' => ['form-control'],
                'after' => "</div>",
                'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
            ]
        ]);
        ?>
        <fieldset>
            <legend><?php echo __('Adicionar texto de apoio'); ?></legend>
            <?php
            echo $this->Form->input('evento_id', ['type' => 'select', 'default' => isset($evento_id) ? $evento_id : end($eventos), 'options' => $eventos]);
            echo $this->Form->input('caderno', ['type' => 'select', 'options' => ['Principal' => 'Principal', 'Anexo' => 'Anexo']]);
            echo $this->Form->input('numero_texto', ['required' => true]);
            echo $this->Form->input('autor', ['type' => 'textarea', 'required' => false]);
            echo $this->Form->input('titulo', ['required' => true]);
            echo $this->Form->input('tema', [
                'type' => 'select',
                'empty' => 'Selecione',
                'options' => ['I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV'],
                'required' => true
            ]);
            echo $this->Form->input('gt_id', [
                'label' => ['text' => 'GT ou Setor', 'class' => 'col-3'],
                'type' => 'select',
                'options' => $gts,
                'empty' => 'Selecione',
                'required' => true
            ]);
            echo $this->Form->input('texto', ['type' => 'textarea', 'rows' => 10, 'cols' => 50, 'required' => false]);
            ?>
        </fieldset>
        <div class='row justify-content-center'>
            <div class='col-auto'>
                <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
                <?= $this->Form->end() ?>
            </div>
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
        TableToolbar
    } from 'ckeditor5';

    let autor;
    if (typeof autor !== 'undefined') {
        autor.destroy();
    }
    ClassicEditor
        .create(document.querySelector('#ApoioAutor'), {
            plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then(editor => {
            autor = editor;
            autor.setData("");
            console.log("Autor editor initialized successfully");
        })
        .catch(error => {
            console.error('Error initializing autor editor:', error);
        });

    let texto;
    if (typeof texto !== 'undefined') {
        texto.destroy();
    }
    ClassicEditor
        .create(document.querySelector('#ApoioTexto'), {
            plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph, Table, TableToolbar],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', 'insertTable', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
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
            texto.setData("");
            console.log("Texto editor initialized successfully");
        })
        .catch(error => {
            console.error('Error initializing texto editor:', error);
        });
        
</script>
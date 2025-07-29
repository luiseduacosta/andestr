<?php

// pr($apoio['Apoio']['texto']);
// pr($apoio['Apoio']['numero_texto']);
// pr($gts);
// pr($eventos);
// die();

?>

<div class="row">
    <div class="col-2">
        <h3 class="h3"><?php echo __('Ações'); ?></h3>
        <ul class="list-group">

            <?php if (isset($usuario)): ?>
                <?php if (($usuario['role'] == 'editor') || ($usuario['role'] == 'admin')): ?>
                    <li class="list-group-item list-group-item-action">
                        <?php echo $this->Form->postLink(__('Excluir'), ['action' => 'delete', $this->Form->value('Apoio.id')], ['confirm' => __('Tem certeza que quer excluir este registro # %s?', $this->Form->value('Apoio.id'))]); ?>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
            <li class="list-group-item list-group-item-action">
                <?php echo $this->Html->link(__('Listar'), ['action' => 'index']); ?></li>

        </ul>
    </div>

    <div class="col-10">
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
            <legend><?php echo __('Editar Textos de Apoio'); ?></legend>
            <?php
            echo $this->Form->input('id', ['type' => 'hidden', 'value' => $apoio['Apoio']['id']]);
            echo $this->Form->input('evento_id', ['default' => $apoio['Apoio']['evento_id'], 'type' => 'select', 'options' => $eventos, 'label' => ['text' => 'Evento', 'class' => 'col-3'], 'required' => true]);
            echo $this->Form->input('caderno', ['type' => 'select', 'options' => ['Principal' => 'Principal', 'Anexo' => 'Anexo'], 'required' => true]);
            echo $this->Form->input('numero_texto', ['value' => $apoio['Apoio']['numero_texto'], 'required' => true]);
            echo $this->Form->input('tema', [
                'type' => 'select',
                'options' => ['I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV'],
                'required' => true,
            ]);
            echo $this->Form->input('gt_id', [
                'label' => ['text' => 'Setor ou grupo de trabalho', 'class' => 'col-3'],
                'value' => $apoio['Apoio']['gt_id'],
                'type' => 'select',
                'options' => [$gts],
                'empty' => true,
                'required' => true
                ],        
            );
            echo $this->Form->input('titulo', ['value' => $apoio['Apoio']['titulo'], 'required' => true]);
            echo $this->Form->input('autor', ['type' => 'textarea' ,'value' => trim($apoio['Apoio']['autor']), 'required' => false]); // There is a bug whith Ckeditor5, this field may not work properly if is required.  
            echo $this->Form->input('texto', ['type' => 'textarea', 'rows' => '10', 'cols' => '50', 'value' => trim($apoio['Apoio']['texto']), 'required' => false]);
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

    function initAutorEditor() {
        let autor;
        if (typeof autor !== 'undefined') {
            autor.destroy()
            .then(() => {
                console.log('Autor editor destroyed successfully');
            })
            .catch(error => {
                console.error('Error destroying autor editor:', error);
            });
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
                console.log('Autor editor initialized successfully');
                var campoAutor = document.querySelector('#ApoioAutor');
                autor.setData(campoAutor.value);
                let contenudoAutor = autor.getData();
                console.log('Conteúdo do autor:', contenudoAutor);
            })
            .catch(error => {
                console.error('Error initializing autor editor:', error);
            });
    }

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
            .create(document.querySelector('#ApoioTexto'), {
                plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph, Table, TableToolbar],
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
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
                console.log('Texto editor initialized successfully');
                var campoTexto = document.querySelector('#ApoioTexto');
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
        initAutorEditor();
        initTextoEditor();
    });
</script>

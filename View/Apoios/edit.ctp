<?php

// pr($apoio['Apoio']['texto']);
// pr($apoio['Apoio']['numero_texto']);
// pr($gts);
// pr($eventos);
// pr($evento_id);
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
            echo $this->Form->input('evento_id', ['options' => $eventos]);
            echo $this->Form->input('caderno', ['type' => 'select', 'options' => ['Principal' => 'Principal', 'Anexo' => 'Anexo']]);
            echo $this->Form->input('numero_texto', ['value' => $apoio['Apoio']['numero_texto']]);
            echo $this->Form->input('tema', [
                'type' => 'select',
                'options' => ['I' => 'I', 'II' => 'II', 'III' => 'III', 'IV' => 'IV']
            ]);
            echo $this->Form->input('gt_id', [
                'label' => ['text' => 'Setor ou grupo de trabalho', 'class' => 'col-3'],
                'value' => $apoio['Apoio']['gt_id'],
                'type' => 'select',
                'options' => [$gts],
                'empty' => true
                ]
            );
            echo $this->Form->input('titulo', ['value' => $apoio['Apoio']['titulo']]);
            echo $this->Form->input('autor', ['value' => $apoio['Apoio']['autor']]);
            echo $this->Form->input('texto', ['type' => 'textarea', 'rows' => '10', 'cols' => '50', 'value' => $apoio['Apoio']['texto']]);
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
        Paragraph
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
            console.log('Olá editor ApoioAutor was initialized', autor);
            autor.setData("<?php echo $apoio['Apoio']['autor']; ?>");
        });

    let texto;
    if (typeof texto !== 'undefined') {
        texto.destroy();
    }
    ClassicEditor
        .create(document.querySelector('#ApoioTexto'), {
            plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then(editor => {
            texto = editor;
            console.log('Olá editor ApoioTexto was initialized', texto);
            texto.setData("<?php echo $apoio['Apoio']['texto']; ?>");
        });
</script>
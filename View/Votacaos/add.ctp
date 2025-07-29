<?php // pr($usuario); ?>
<?php // pr($item); ?>
<?php // pr(substr($item['Item']['texto'], 0, 1)); ?>
<?php // pr($votacao['Votacao']['id']); ?>
<?php // $this->request->data = $votacao; ?>
<?php // pr($this->data); ?>
<?php // die(); ?>

<?php // echo $this->Html->script('ckeditor/ckeditor', ['inline' => false]); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>

<script>

    function copiatr() {
        var tr = document.getElementById("VotacaoTr").value;
        if (tr.length == 1) {
            tr = '0' + tr;
        }
        document.getElementById("VotacaoItem").value = tr;
    }

    $(document).ready(function () {

        $("#VotacaoGrupo").mask("09", { placeholder: "__" });
        $("#VotacaoTr").mask("09", { placeholder: "__" });
        $("#VotacaoItem").mask("00.00.99", { placeholder: "__.__" });
        $("#VotacaoVotacao").mask("09/09/09", { placeholder: "__/__/__" });

    });

</script>

<?php if (isset($item)): ?>
    <dl class='row'>
        <dt class='col-2'><?php echo __('Item'); ?></dt>
        <dd class='col-9'><?php echo $item['Item']['item']; ?></dd>

        <dt class='col-2'><?php echo __('Texto'); ?></dt>
        <dd class='col-9'><?php echo $item['Item']['texto']; ?></dd>
    </dl>
<?php endif; ?>

<?php
echo $this->Form->create('Votacao', [
    'class' => 'form-horizontal',
    'role' => 'form',
    'inputDefaults' => [
        'format' => ['before', 'label', 'between', 'input', 'after', 'error'],
        'div' => ['class' => 'form-group row'],
        'label' => ['class' => 'col-4'],
        'between' => "<div class = 'col-8'>",
        'class' => ['form-control'],
        'after' => "</div>",
        'error' => ['attributes' => ['wrap' => 'span', 'class' => 'help-inline']]
    ]
]);
?>

<?php if (isset($usuario)): ?>
    <?php if ($usuario['role'] == 'relator'): ?>
        <?php $tamanho = strlen($usuario['username']) ?>
        <?php
        if ($tamanho == 7):
            $usuariogrupo = substr($usuario['username'], 5, 2);
        elseif ($tamanho == 6):
            $usuariogrupo = substr($usuario['username'], 5, 1);
        endif;
        ?>
        <legend><?php echo __('Grupo ' . $usuariogrupo . '. Inserir votação'); ?></legend>
    <?php endif; ?>
<?php else: ?>
    <legend><?php echo __('Inserir votação'); ?></legend>
<?php endif; ?>

<?php
echo $this->Form->input('item_id', array('type' => 'hidden', 'value' => $item['Item']['id']));
echo $this->Form->input('evento_id', array('type' => 'hidden', 'value' => $item['Apoio']['evento_id']));

if (isset($usuario)):
    // pr($usuario);
    if ($usuario['role'] == 'relator'):
        echo $this->Form->input('grupo', ['type' => 'hidden', 'value' => substr($usuario['username'], 5, 2), 'required']);
    elseif ($usuario['role'] == 'admin'):
        if (isset($votacao)):
            // echo "1 *";
            echo $this->Form->input('grupo', ['label' => ['text' => 'Grupo.', 'class' => 'col-4'], 'placeholder' => 'Digite até dois carateres numéricos.', 'value' => $votacao['Votacao']['grupo']], 'required');
        else:
            // echo "2 *";
            echo $this->Form->input('grupo', ['label' => ['text' => 'Grupo', 'class' => 'col-4'], 'placeholder' => 'Digite um ou até dois carateres numéricos', 'maxlength' => 2], 'required');
        endif;
    endif;
else:
    // echo $this->Form->input('grupo', array('label' => 'Grupo. Digite até dois carateres numéricos'));
endif;

if (isset($item)):
    echo $this->Form->input('tr', array('label' => ['text' => 'TR', 'class' => 'col-4'], 'value' => strlen($item['Item']['tr'] == 1) ? '0' . $item['Item']['tr'] : $item['Item']['tr']));
elseif (isset($tr)):
    echo $this->Form->input('tr', array('label' => ['text' => 'TR', 'class' => 'col-4'], 'placeholder' => 'Digite até dois carateres numéricos', 'value' => strlen($tr) == 1) ? '0' . $tr : $tr);
else:
    echo $this->Form->input('tr', array('label' => ['text' => 'TR', 'class' => 'col-4'], 'placeholder' => 'Digite até dois carateres numéricos'));
endif;
?>

<fieldset id="suprecaototal" class="border rounded-3 p-3">
    <legend class="float-none w-auto px-3 bg-danger">Supresão da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_suprimida', [
        'label' => ['text' => 'Votação de supresão da TR como um todo. Selecionar "Suprimida" na caixa de seleção "Resolução". Colocar a votação no campo "Votação" deste formulário.', 'class' => 'col-4'],
        'type' => 'select',
        'options' => ['0' => 'Não', '1' => 'Sim'],
        'onChange' => "oculta()"
    ]);
    ?>
</fieldset>

<fieldset id='votacao' class='border rounded-3 p-3'>
    <legend id='legendavotacao' class="float-none w-auto px-3 bg-info">Votação de cada item da TR</legend>

    <?php
    if (isset($item)):
        echo $this->Form->input('item', ['label' => ['text' => 'Item. Formato nn.nn Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'class' => 'col-4'], 'value' => substr($item['Item']['item'], 0, 5), "onClick" => "copiatr()"]);
    elseif (isset($tr)):
        echo $this->Form->input('item', ['label' => ['text' => 'Item. Formato nn.nn Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'class' => 'col-4'], 'value' => $tr . '.99', "onClick" => "copiatr()"]);
    else:
        echo $this->Form->input('item', ['label' => ['text' => 'Item. Formato nn.nn Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'class' => 'col-4'], 'placeholder' => '00.00', "onClick" => "copiatr()"]);
    endif;

    echo $this->Form->input('resultado', [
        'label' => ['text' => 'Resolução', 'class' => 'col-4'],
        'type' => 'select',
        'selected' => isset($votacao) ? $votacao['Votacao']['resultado'] : null,
        'empty' => 'Selecione',
        'options' => [
            'aprovada' => 'Aprovado integralmente',
            'modificada' => 'Aprovado com modificações',
            'suprimida' => 'Suprimido',
            'inclusão' => 'Inclusão de novo item',
            'minoritária' => 'Proposta minoritária (1/3)',
            'remitida' => 'Remitida para outro tema e/ou TR. Especificar em observações',
            'outra' => 'Outra votação. Especificar em observações'
        ],
        'onchange' => 'selecionavotacao()'
    ]);

    if (isset($votacao)):
        echo $this->Form->input('votacao', [
            'label' => ['text' => 'Votação. Digite nesta ordem: favoráveis / contrários / abstenções', 'class' => 'col-4'],
            'value' => str_replace('-', '/', $votacao['Votacao']['votacao'])
        ]);
    else:
        echo $this->Form->input('votacao', [
            'label' => ['text' => 'Votação. Digite nesta ordem: favoráveis / contrários / abstenções', 'class' => 'col-4'],
            'placeholder' => '00/00/00'
        ]);
    endif;
    ?>

    <div id='itemmodificada'>
        <?php
        echo $this->Html->link("Verificador de diferenças entre textos", "https://editor.mergely.com/", ['target' => 'blank']);
        echo "<br>";
        echo $this->Form->input('item_modificada', ['label' => ['text' => 'Item modificado', 'class' => 'col-4'], 'value' => isset($item_modificada) ? $votacao['Votacao']['item_modificada'] : '', 'class' => 'ckeditor']);
        ?>
    </div>

    <div id='itemincluida'>
        <?php
        echo "<br>";
        echo $this->Form->input('item_incluida', ['label' => ['text' => 'Inclusão de novo item', 'class' => 'col-4 form-label'], 'type' => 'textarea', 'rows' => 5, 'cols' => 50, 'value' => isset($item_modificada) ? $votacao['Votacao']['item_modificada'] : '', 'class' => 'form-control']);
        ?>
    </div>

    <div id='itemminoritaria'>
        <?php
        echo "<br>";
        echo $this->Form->input('item_minoritaria', ['label' => ['text' => 'Item minoritária. Tache o texto do item para indicar suprimida e digite "Suprimida" em Observações.', 'class' => 'col-4 form-label'], 'type' => 'textarea', 'rows' => 5, 'cols' => 50, 'value' => isset($item_modificada) ? $votacao['Votacao']['item_modificada'] : '', 'class' => 'form-control']);
        ?>
    </div>

</fieldset>

<fieldset id='aprovacaototal' class='border rounded-3 p-3'>
    <legend class="float-none w-auto px-3 bg-success">Aprovação da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_aprovada', [
        'label' => ['text' => 'Votação da TR como um todo. É para aprovar os items que não foram destacados. Selecionar "Aprovada" na caixa de seleção "Resolução" anterior. Colocar a votação no campo "Votação" deste formulário.', 'class' => 'col-4'],
        'type' => 'select',
        'options' => ['0' => 'Não', '1' => 'Sim'],
        'onchange' => 'aprovatr()',
    ])
    ;
    ?>
</fieldset>

<fieldset class='border rounded-3 p-3'>
    <legend class="float-none w-auto px-3">Observações</legend>
    <?php
    echo $this->Form->input('observacoes', ['label' => ['text' => 'Observações', 'class' => 'col-4'], 'class' => 'ckeditor']);
    ;
    ?>
</fieldset>

<div class='row justify-content-center'>
    <div class='col-auto'>
        <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
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

    let modificada;
    if (typeof modificada !== 'undefined') {
        modificada.destroy();
    }
    ClassicEditor
        .create(document.querySelector('#VotacaoItemModificada'), {
            plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then(editor => {
            modificada = editor;
            console.log('Olá editor VotacaoItemModificada was initialized', modificada);
            modificada.setData("<?= str_replace(["\r", "\n"], '', $item['Item']['texto']); ?>");
        });

    let incluida;
    if (typeof incluida !== 'undefined') {
        incluida.destroy();
    }
    ClassicEditor
        .create(document.querySelector('#VotacaoItemIncluida'), {
            plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then(editor => {
            incluida = editor;
            console.log('Olá editor VotacaoItemIncluida was initialized', incluida);
            incluida.setData("");
        });

    let minoritaria;
    if (typeof minoritaria !== 'undefined') {
        minoritaria.destroy();
    }
    ClassicEditor
        .create(document.querySelector('#VotacaoItemMinoritaria'), {
            plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then(editor => {
            minoritaria = editor;
            console.log('Olá editor VotacaoItemMinoritaria was initialized', minoritaria);
            minoritaria.setData("<?= str_replace(["\r", "\n"], '', $item['Item']['texto']); ?>");
        });

    let observacoes;
    if (typeof observacoes !== 'undefined') {
        observacoes.destroy();
    }
    ClassicEditor
        .create(document.querySelector('#VotacaoObservacoes'), {
            plugins: [Essentials, Bold, Italic, Strikethrough, Font, Paragraph],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', 'strikethrough', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        })
        .then(editor => {
            observacoes = editor;
            console.log('Olá editor VotacaoObservacoes was initialized', observacoes);
            observacoes.setData("");
        });
</script>

<script>

    document.getElementById("itemmodificada").style.display = "none";
    document.getElementById("itemincluida").style.display = "none";
    document.getElementById("itemminoritaria").style.display = "none";

    if (document.getElementById("VotacaoResultado").value == "minoritária") {
        document.getElementById("itemminoritaria").style.display = "block";
    }

    function oculta() {

        var supresao = document.getElementById("VotacaoTrSuprimida").value;
        // alert(supresao);
        if (supresao === '1') {
            document.getElementById("legendavotacao").innerHTML = "<p class='h2'>Supresão de todos os items da TR</p>";
            document.getElementById("aprovacaototal").style.display = "none";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['tr'] ?>";
            document.getElementById("VotacaoResultado").value = "suprimida";
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "none";

        } else if (supresao === '0') {
            document.getElementById("legendavotacao").innerHTML = "<p class='h2'>Votação de cada item da TR</p>";
            document.getElementById("aprovacaototal").style.display = 'block';
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>";
            document.getElementById("VotacaoResultado").value = "";
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "none";

        }

    }

    function aprovatr() {

        var aprovada = document.getElementById("VotacaoTrAprovada").value;
        // alert(aprovada);
        if (aprovada === '1') {
            // alert(supresao);
            // document.getElementById("votacao").style.display = "none";
            document.getElementById("suprecaototal").style.display = "none";
            document.getElementById("legendavotacao").innerHTML = "<p class='h2'>Aprovação de todos os items da TR que não foram destacados</p>";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['tr'] ?>";
            document.getElementById("aprovacaototal").style.display = "block";
            document.getElementById("VotacaoResultado").value = "aprovada";
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "none";

        } else if (aprovada === '0') {
            // alert(aprovada);
            // document.getElementById("votacao").style.display = 'block';
            document.getElementById("suprecaototal").style.display = "block";
            document.getElementById("legendavotacao").innerHTML = "<p class='h2'>Votação de cada item da TR</p>";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>";
            document.getElementById("aprovacaototal").style.display = "block";
            document.getElementById("VotacaoResultado").value = "";
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "none";
        }

    }

    function selecionavotacao() {

        var resultado = document.getElementById("VotacaoResultado").value;
        // alert(resultado);
        if (resultado == 'suprimida') {
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>"

        } else if (resultado == 'modificada') {
            document.getElementById("itemmodificada").style.display = "block";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>"

        } else if (resultado == 'inclusão') {
            document.getElementById("itemincluida").style.display = "block";
            document.getElementById("itemminoritaria").style.display = "none";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['tr'] . "." . '99' ?>"

        } else if (resultado == 'aprovada') {
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>"

        } else if (resultado == 'minoritária') {
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "block";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>"

        } else if (resultado == 'remitida') {
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "none";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>"

        } else if (resultado == 'outra') {
            document.getElementById("itemmodificada").style.display = "none";
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "none";
            document.getElementById("VotacaoItem").value = "<?= $item['Item']['item'] ?>"

        } else {
            document.getElementById("aprovacaototal").style.display = "block";
            document.getElementById("itemincluida").style.display = "none";
            document.getElementById("itemminoritaria").style.display = "none";
            document.getElementById("itemmodificada").style.display = "none";
        }
    }

</script>

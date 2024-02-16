<?php // pr($usuario);      ?>
<?php // pr($item['Item']['texto']);      ?>
<?php // pr($votacao['Votacao']['id']); ?>
<?php // $this->request->data = $votacao;    ?>
<?php // pr($this->data);    ?>
<?php // die();    ?>

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
    <?php if ($usuario['role'] == 'relator' || $usuario['role'] == 'admin'): ?>
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
            echo $this->Form->input('grupo', array('label' => ['text' => 'Grupo.', 'class' => 'col-4'], 'placeholder' => 'Digite até dois carateres numéricos.', 'value' => $votacao['Votacao']['grupo']));
        else:
            // echo "2 *";
            echo $this->Form->input('grupo', array('label' => ['text' => 'Grupo', 'class' => 'col-4'], 'placeholder' => 'Digite um ou até dois carateres numéricos', 'maxlength' => 2));
        endif;
    endif;
else:
// echo $this->Form->input('grupo', array('label' => 'Grupo. Digite até dois carateres numéricos'));
endif;

if (isset($item)):
    echo $this->Form->input('tr', array('label' => ['text' => 'TR', 'class' => 'col-4'], 'placeholder' => 'Digite até dois carateres numéricos', 'value' => substr($item['Item']['item'], 0, 2)));
elseif (isset($tr)):
    echo $this->Form->input('tr', array('label' => ['text' => 'TR', 'class' => 'col-4'], 'placeholder' => 'Digite até dois carateres numéricos', 'value' => $tr));
else:
    echo $this->Form->input('tr', array('label' => ['text' => 'TR', 'class' => 'col-4'], 'placeholder' => 'Digite até dois carateres numéricos'));
endif;
?>

<fieldset class='border rounded-3 p-3'>
    <legend class="float-none w-auto px-3">Supresão da TR na sua totalidade</legend>

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
    <legend id='legendavotacao' class="float-none w-auto px-3">Votação de cada item da TR</legend>

    <?php
    if (isset($item)):
        echo $this->Form->input('item', ['label' => ['text' => 'Item. Formato nn.nn.nn Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'class' => 'col-4'], 'value' => substr($item['Item']['item'], 0, 5)]);
    elseif (isset($tr)):
        echo $this->Form->input('item', ['label' => ['text' => 'Item. Formato nn.nn.nn Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'class' => 'col-4'], 'value' => $tr . '.99']);
    else:
        echo $this->Form->input('item', ['label' => ['text' => 'Item. Formato nn.nn.nn Digitar: número da TR "." e o número do item. Digite "99" como número do item para indicar inclusão de novo item.', 'class' => 'col-4'], 'placeholder' => '00.00.00']);
    endif;

    echo $this->Form->input('resultado', [
        'label' => ['text' => 'Resolução', 'class' => 'col-4'],
        'type' => 'select',
        'selected' => isset($votacao) ? $votacao['Votacao']['resultado'] : null,
        'empty' => 'Selecione',
        'options' => ['aprovada' => 'Aprovada sem alterações',
            'modificada' => 'Aprovada com modificações',
            'suprimida' => 'Suprimida',
            'inclusão' => 'Inclusão de novo item',
            'minoritária' => 'Proposta minoritária (1/3)',
            'remitida' => 'Remitida para outro tema e/ou TR. Especificar em observações',
            'outra' => 'Outra votação especificar em observações'],
        'onchange' => 'resultado()'
    ]);

    if (isset($votacao)):
        echo $this->Form->input('votacao', [
            'label' => ['text' => 'Votação. Digite nesta ordem: favoráveis / contrários / abstenções', 'class' => 'col-4'],
            'value' => str_replace('-', '/', $votacao['Votacao']['votacao'])]);
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
        echo $this->Form->input('item_modificada', ['label' => ['text' => 'Item modificado, novo item, item remetido para outro tema e/ou TR ou item aprovado como minoritário', 'class' => 'col-4'], 'value' => isset($item_modificada) ? $votacao['Votacao']['item_modificada'] : '', 'class' => 'ckeditor']);
        ?>
    </div>
</fieldset>

<fieldset id='aprovacaototal' class='border rounded-3 p-3'>
    <legend class="float-none w-auto px-3">Aprovação da TR na sua totalidade</legend>

    <?php
    echo $this->Form->input('tr_aprovada', [
        'label' => ['text' => 'Votação da TR como um todo. É para aprovar os items que não foram destacados. Selecionar "Aprovada" na caixa de seleção "Resolução" anterior. Colocar a votação no campo "Votação" deste formulário.', 'class' => 'col-4'],
        'type' => 'select',
        'options' => array('0' => 'Não', '1' => 'Sim')
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

<div class='row justify-content-left'>
    <div class='col-auto'>
        <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>

    // $(document).ready(function () {

    if (typeof modificada !== 'undefined') {
        modificada.destroy();
    }
    if (typeof observacoes !== 'undefined') {
        observacoes.destroy();
    }

    ClassicEditor
            .create(document.querySelector('#VotacaoItemModificada'), {
                toolbar: ['bold', 'italic', 'underline', 'strike', 'link', 'undo', 'redo', 'numberedList', 'bulletedList']
            })
            .then(editor => {
                modificada = editor;
                console.log('Editor was initialized', modificada);
                modificada.setData('');
            });

    ClassicEditor
            .create(document.querySelector('#VotacaoObservacoes'), {
                language: 'pt'
            })
            .then(editor => {
                observacoes = editor;
                console.log('Editor was initialized', observacoes);
                observacoes.setData('');
            });
    // });

    function oculta() {

        var supresao = document.getElementById("VotacaoTrSuprimida").value;
        // alert(supresao);
        if (supresao === '1') {
            // alert(supresao);
            // document.getElementById("votacao").style.display = "none";
            document.getElementById("legendavotacao").innerHTML = "<h2 class='h2'>Votação da supresão da TR</h2>";
            document.getElementById("aprovacaototal").style.display = "none";
            document.getElementById("VotacaoResultado").value = "suprimida";
            document.getElementById("itemmodificada").style.display = "none";
        } else if (supresao === '0') {
            // alert(supresao);
            // document.getElementById("votacao").style.display = 'block';
            document.getElementById("aprovacaototal").style.display = 'block';
            document.getElementById("VotacaoResultado").value = "";
            document.getElementById("itemmodificada").style.display = "block";
            document.getElementById("legendavotacao").innerHTML = "<h2 class='h2'>Votação dos items da TR</h2>";
        }

    }

    function resultado() {

        var resultado = document.getElementById("VotacaoResultado").value;

        if (resultado === 'suprimida') {
            document.getElementById("itemmodificada").style.display = "none";

        } else if (resultado === 'modificada') {
            document.getElementById("itemmodificada").style.display = "block";

            if (typeof modificada !== 'undefined') {
                modificada.destroy();
            }
            if (typeof observacoes !== 'undefined') {
                observacoes.destroy();
            }
            ClassicEditor
                    .create(document.querySelector('#VotacaoItemModificada'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        modificada = editor;
                        console.log('Editor was initialized', modificada);
                        modificada.setData(`<?= $item['Item']['texto']; ?>`);
                    });

            ClassicEditor
                    .create(document.querySelector('#VotacaoObservacoes'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        observacoes = editor;
                        console.log('Editor was initialized', observacoes);
                        observacoes.setData('');
                    });

        } else if (resultado === 'inclusão') {
            document.getElementById("itemmodificada").style.display = "block";

            if (modificada) {
                modificada.destroy();
            }
            if (observacoes) {
                observacoes.destroy();
            }
            ClassicEditor
                    .create(document.querySelector('#VotacaoItemModificada'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        modificada = editor;
                        console.log('Editor was initialized', modificada);
                        modificada.setData('');
                    });

            ClassicEditor
                    .create(document.querySelector('#VotacaoObservacoes'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        observacoes = editor;
                        console.log('Editor was initialized', observacoes);
                        observacoes.setData('');
                    });

        } else if (resultado === 'aprovada') {
            document.getElementById("itemmodificada").style.display = "none";

        } else if (resultado === 'minoritária') {
            document.getElementById("itemmodificada").style.display = "block";

            if (modificada) {
                modificada.setData('');
            }

            if (observacoes) {
                observacoes.destroy();
            }

            ClassicEditor
                    .create(document.querySelector('#VotacaoItemModificada'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        modificada = editor;
                        console.log('Editor was initialized', modificada);
                        modificada.setData('');
                    });

            ClassicEditor
                    .create(document.querySelector('#VotacaoObservacoes'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        observacoes = editor;
                        console.log('Editor was initialized', observacoes);
                        observacoes.setData('');
                    });

        } else if (resultado === 'remitida') {
            document.getElementById("itemmodificada").style.display = "none";

        } else if (resultado === 'outra') {
            document.getElementById("itemmodificada").style.display = "none";

        } else {
            document.getElementById("aprovacaototal").style.display = "block";
            document.getElementById("itemmodificada").style.display = "block";
            if (modificada) {
                modificada.destroy();
            }
            if (observacoes) {
                observacoes.destroy();
            }

            ClassicEditor
                    .create(document.querySelector('#VotacaoItemModificada'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        modificada = editor;
                        console.log('Editor was initialized', modificada);
                        modificada.setData('');
                    });

            ClassicEditor
                    .create(document.querySelector('#VotacaoObservacoes'), {
                        language: 'pt'
                    })
                    .then(editor => {
                        observacoes = editor;
                        console.log('Editor was initialized', observacoes);
                        observacoes.setData('');
                    });
        }
    }



</script>

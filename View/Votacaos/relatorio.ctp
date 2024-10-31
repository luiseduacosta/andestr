<?php // pr($this->data);                              ?>
<?php // pr($relatorio);                              ?>
<?php // pr($situacao);                              ?>
<?php // pr($quantidade);                              ?>
<?php // pr($evento_id); ?>
<script>
    $(document).ready(function () {
        var url = "<?= $this->Html->url(['controller' => 'votacaos', 'action' => 'relatorio?evento_id=']); ?>";
        $("#EventoEventoId").change(function () {
            var evento_id = $(this).val();
            /* alert(evento_id); */
            window.location = url + evento_id;
        })

    })
</script>

<div class="row justify-content-center">
    <div class="col-auto">

        <?php echo $this->Form->create('Evento', ['class' => 'form-inline']); ?>
        <?php if (isset($evento_id)): ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Evento', 'style' => 'display: inline;'], 'options' => $eventos, 'default' => $evento_id, 'class' => 'form-control']); ?>
        <?php else: ?>
            <?php echo $this->Form->input('evento_id', ['type' => 'select', 'label' => ['text' => 'Evento', 'style' => 'display: inline;'], 'options' => $eventos, 'default' => end($eventos), 'class' => 'form-control']); ?>
        <?php endif; ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<?php if (!isset($relatorio)): ?>
    <?php isset($usuario['grupo']) ? $titulo = 'Relatório do grupo: ' . $usuario['grupo'] : $titulo = 'Relatório geral'; ?>

    <legend><?php echo $titulo; ?></legend>
    <?php
    echo $this->Form->create('Relatorio', [
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

    <?php echo $this->Form->input('trs', ['label' => ['text' => 'TR', 'class' => 'col-1'], 'placeholder' => 'Se for a solicitar mais de uma TR, separe cada uma por vírgulas']); ?>
    <div class='row justify-content-left'>
        <div class='col-auto'>
            <?= $this->Form->submit('Confirma', ['type' => 'Submit', 'label' => ['text' => 'Confirma', 'class' => 'col-4'], 'class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>

<?php else: ?>
    <?php
    $i = 0;
    // pr($relatorio);
    // echo count($relatorio);
    foreach ($relatorio as $c_relatorio):
        // pr($c_relatorio);
    endforeach;

    if (!empty($relatorio[0])):
        foreach ($relatorio as $c_relatorio):
            ?>
            <?php echo '<h1>TR: ' . $c_relatorio[0]['Votacao']['tr'] . '</h1>';
            ?>
            <?php echo "<h1>Situação nos grupos</h1>"; ?>
            <?php echo $quantidade[$i] . "<br>"; ?>
            <?php echo $situacao[$i] . "<br>"; ?>
            <?php $i++; ?>

            <?php $textotr = NULL; // Para colocar o texto da TR somente uma vez ?>
            <?php foreach ($c_relatorio as $tr_relatorio): ?>
                <?php
                if ($textotr != $tr_relatorio['Votacao']['item']):
                    if (substr($tr_relatorio['Votacao']['item'], 3, 2) == '99'):
                        echo '<p style="font-size:18px;">';
                        echo "<b>Inclusões</b>";
                        echo '</p>';
                    else:
                        echo '<p style="font-size:18px; text-align: justify;">';
                        echo '<b>Item: ' . substr($tr_relatorio['Votacao']['item'], 3, 2) . '</b>: ' . strip_tags($tr_relatorio['Item']['texto']);
                        echo "</p>";
                    endif;
                endif;
                $textotr = $tr_relatorio['Votacao']['item']; // Guardo o texto da TR para comparar e saber se mudou e assim garantir que não coloque outra vez
                ?>

                <?php echo "<p>Grupo: " . $tr_relatorio['Votacao']['grupo']; ?>
                <?php echo ". Resultado: " . '<b>' . $tr_relatorio['Votacao']['resultado'] . '</b>'; ?>
                <?php echo ". Votação: (" . $tr_relatorio['Votacao']['votacao'] . ')'; ?>
                <?php if ($tr_relatorio['Votacao']['observacoes']): ?>
                    <?php echo ". Observações: " . $tr_relatorio['Votacao']['observacoes']; ?>
                <?php endif; ?>
                <?php if ($tr_relatorio['Votacao']['item_modificada']): ?>
                    <?php echo '<ul><p><span style = "font-family: Lucida Console, Courier New, monospace;">' . $tr_relatorio['Votacao']['item_modificada'] . "</span></p>"; ?>
                    <?php echo "</ul>"; ?>
                    <?php echo "<br>"; ?>
                <?php endif; ?>

                <?php // pr($c_relatorio);    ?>

            <?php endforeach; ?>

            <?php // echo $i;       ?>

            <?php echo '<br>'; ?>


        <?php endforeach; ?>
    <?php // pr($grupos);  ?>
    <?php endif; ?>
<?php endif; ?>
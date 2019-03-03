<?php // pr($this->data);                ?>
<?php // pr($relatorio);                ?>
<?php // pr($situacao);                ?>
<?php // pr($quantidade);                ?>

<?php if (!(isset($relatorio))): ?>
    <?php isset($usuario['grupo']) ? $titulo = 'Relatório do grupo: ' . $usuario['grupo'] : $titulo = 'Relatório geral'; ?>

    <legend><?php echo $titulo; ?></legend>
    <?php echo $this->Form->create('Relatorio'); ?>

    <?php echo $this->Form->input('trs', array('label' => 'TR. Se for a solicitar mais de uma TR, separe cada uma por vírgulas')); ?>

    <?php echo $this->Form->end(__('Salvar')); ?>

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

            <?php $m = 1; ?>
            <?php $mtotal = NULL; ?>
            <?php foreach ($c_relatorio as $tr_relatorio): ?>

                <?php
                if (strlen(strval($m)) == 1):
                    $m1 = substr($tr_relatorio['Votacao']['item'], 0, 3);
                    $m2 = '0';
                    $m3 = strval($m);
                    $mtotal = $m1 . $m2 . $m3;
                elseif (strlen(strval($m)) == 2):
                    $m1 = substr($tr_relatorio['Votacao']['item'], 0, 3);
                    $m2 = '';
                    $m3 = strval($m);
                    $mtotal = $m1 . $m2 . $m3;
                endif;

                if ($tr_relatorio['Votacao']['item'] === $mtotal):
                    echo '<p>';
                    echo '<b>Item: ' . substr($tr_relatorio['Votacao']['item'], 3, 2) . '</b>: ' . $tr_relatorio['Item']['texto'];
                    echo "</p>";
                    $m++;
                endif;
                ?>

                <?php // echo 'Índice -> ' . $i; ?>
                <?php // echo "<p><b>Item " . substr($tr_relatorio['Votacao']['item'], 3, 2) . ": </b>"; ?>
                <?php // echo $tr_relatorio['Item']['texto'] . '</p>'; ?>
                <?php echo "<p>Grupo: " . $tr_relatorio['Votacao']['grupo']; ?>
                <?php // echo '<br>'; ?>
                <?php echo ". Resultado: " . '<b>' . $tr_relatorio['Votacao']['resultado'] . '</b>'; ?>
                <?php echo ". Votação: (" . $tr_relatorio['Votacao']['votacao'] . ')'; ?>
                <?php if ($tr_relatorio['Votacao']['observacoes']): ?>
                    <?php echo ". Observações: " . $tr_relatorio['Votacao']['observacoes']; ?>
                <?php endif; ?>
                <?php if ($tr_relatorio['Votacao']['item_modificada']): ?>
                    <?php echo '<p><i>' . $tr_relatorio['Votacao']['item_modificada'] . "</i>"; ?>
                <?php endif; ?>
                <?php echo "</p>"; ?>

                <?php // pr($c_relatorio);  ?>

            <?php endforeach; ?>

            <?php // echo $i;     ?>

            <?php echo '<br>'; ?>


        <?php endforeach; ?>
        <?php // pr($grupos);  ?>
    <?php endif; ?>
<?php endif; ?>

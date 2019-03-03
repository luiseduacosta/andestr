<?php // pr($votacao);       ?>

<?php echo $this->Html->link('Editar', 'edit/' . $votacao['Votacao']['id']); ?>
<?php echo " "; ?>
<?php // echo $this->Html->link('TR', '/Resolucaos/view/' . $votacao['Votacao']['resolucao_id']); ?>    
<?php echo $this->Html->link('Exluir', 'delete/' . $votacao['Votacao']['id'], array('confirm' => __('Está seguro que quer excluir este registro'))); ?>

<table>
    <tr>
        <td>
            <?php
            echo 'Grupo';
            ?>
        </td>
        <td>
            <?php
            echo $votacao['Votacao']['grupo'];
            ?>
        </td>
    </tr>

    <tr>
        <td>
            <?php
            echo 'TR';
            ?>
        </td>
        <td>
            <?php
            if (isset($votacao['Votacao']['grupo'])):
                // pr($usuario['grupo']);
                echo $this->Html->link($votacao['Votacao']['tr'], '/Items/index/tr:' . $votacao['Votacao']['tr'] . '/grupo:' . $votacao['Votacao']['grupo']);
            else:

            endif;
            ?>
        </td>
    </tr>

    <tr>

        <td>
            <?php
            echo 'TR suprimida?';
            ?>
        </td>

        <td>
            <?php
            echo $votacao['Votacao']['tr_suprimida'];
            ?>
        </td>
    </tr>

    <tr>

        <td>
            <?php
            echo 'Item';
            ?>
        </td>

        <td>
            <?php echo $votacao['Votacao']['item'];
            ?>
        </td>
    </tr>

    <tr>

        <td>
            <?php
            echo 'Resultado';
            ?>
        </td>
        <td>
            <?php echo $votacao['Votacao']['resultado'];
            ?>
        </td>
    </tr>

    <tr>
        <td>
            <?php
            echo 'Votação';
            ?>
        </td>
        <td>
            <?php
            $votos = explode('/', $votacao['Votacao']['votacao']);
            $total = $votos[0] + $votos[1] + $votos[2];
            $terco = $total / 3;
            $minoritaria = ($votos[1] >= $terco ? "minoritária" : '');
            echo $votacao['Votacao']['votacao'] . " || Total " . $total . ' || 1/3 = ' . round($terco) . "  " . '<h1 style="text-transform: uppercase; animation-duration: 3s";>' . $minoritaria . '</h1>';
            ?>
        </td>
    </tr>

    <tr>
        <td>
            <?php
            echo 'Modificação';
            ?>
        </td>
        <td>
            <?php
            echo $votacao['Votacao']['item_modificada'];
            ?>
        </td>
    </tr>

</table>

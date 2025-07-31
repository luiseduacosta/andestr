<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'Andes-SN: plataforma para os grupos mistos dos Conads e Congressos');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
    ?>
<!DOCTYPE html>
<html>

<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $this->fetch('title'); ?>
    </title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.js"
        integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.css" />
    <script type="importmap">
            {
            "imports": {
            "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/43.2.0/ckeditor5.js",
            "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/43.2.0/"
            }
            }
    </script>

    <?php
    echo $this->Html->meta('icon');

    // echo $this->Html->css('cake.generic-novo');
    
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
    <!-- Corrige o CSS do cake.generic-novo. Será desnecessário //-->
    <style>
        a {
            color: rgba(0, 0, 0, 0.5)
        }

        dl {
            color: black
        }

        ;
    </style>

</head>

<body class="bg-ligth">
    <div class="container-fluid">
        <h1 class='h2 bg-danger text-light p-3'>Andes-SN: plataforma para os grupos mistos dos Conads e Congressos</h1>
        <nav class='navbar navbar-expand-lg navbar-light bg-light'>
            <?php echo $this->Html->link('ANDES-SN', 'http://www.andes.org.br', ['target' => '_blank', '_full' => true, 'class' => 'navbar-brand']); ?>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuprincipal"
                aria-controls="menuprincipal" aria-expanded="false" aria-label="Barra de navegação superior">
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="navbar-nav mr-auto collapse navbar-collapse" id="menuprincipal">
                <li class=" nav-item">
                    <?php echo $this->Html->link('Eventos', ['controller' => 'Eventos', 'action' => 'index'], ['class' => 'nav-link']); ?>
                </li>

                <li class="nav-item">
                    <?php
                    if (isset($usuario)):
                        if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                            echo $this->Html->link('Usuários', ['controller' => 'Users', 'action' => 'index'], ['class' => 'nav-link']);
                        endif;
                    endif;
                    ?>
                </li>

                <li class="nav-item">
                    <?php echo $this->Html->link('Grupos de Trabalho', ['controller' => 'Gts', 'action' => 'index'], ['class' => 'nav-link']); ?>
                </li>

                <li class="nav-item">
                    <?php echo $this->Html->link('Textos de apoio', ['controller' => 'Apoios', 'action' => 'index'], ['class' => 'nav-link']); ?>
                </li>

                <li class="nav-item">
                    <?php echo $this->Html->link('TRs', ['controller' => 'Items', 'action' => 'index'], ['class' => 'nav-link']); ?>
                </li>

                <li class="nav-item">
                    <?php
                    if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
                        echo $this->Html->link('Votações', ['controller' => 'Votacaos', 'action' => 'index'], ['class' => 'nav-link']);
                    elseif (isset($usuario) && $usuario['role'] == 'relator'):
                        echo $this->Html->link('Votações', ['controller' => 'Votacaos', 'action' => 'index', '?' => ['grupo' => substr($usuario['username'], 5, 2)]], ['class' => 'nav-link']);
                    endif;
                    ?>
                </li>

                <li class="nav-item">
                    <?php
                    if (isset($usuario) && ($usuario['role'] == 'editor' || $usuario['role'] == 'admin')):
                        echo $this->Html->link('Grupos', ['controller' => 'Votacaos', 'action' => 'index'], ['class' => 'nav-link']);
                    elseif (isset($usuario) && $usuario['role'] == 'relator'):
                        if (strlen($usuario['username']) == 6):
                            $usuariogrupo = substr($usuario['username'], 5, 1);
                        elseif (strlen($usuario['username']) == 7):
                            $usuariogrupo = substr($usuario['username'], 5, 2);
                        endif;
                        echo $this->Html->link('Grupos', ['controller' => 'Votacaos', 'action' => 'index', '?' => ['grupo' => $usuariogrupo]], ['class' => 'nav-link']);
                    endif;
                    ?>
                </li>

                <li class="nav-item">
                    <?php echo $this->Html->link('Relatórios', ['controller' => 'Votacaos', 'action' => 'relatorio', '?' => ['evento_id' => $this->Session->read('evento_id')]], ['class' => 'nav-link']); ?>
                </li>

                <?php if (isset($usuario)): ?>
                    <li class="nav-item">
                        <?php echo $this->Html->link('Sair', ['controller' => 'Users', 'action' => 'logout'], ['class' => 'nav-link']); ?>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <?php echo $this->Html->link('Entrar', ['controller' => 'Users', 'action' => 'login'], ['class' => 'nav-link']); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <?php
            if (isset($usuario)):
                if ($usuario['role'] === 'relator'):
                    if (strlen($usuario['username']) == 6):
                        $usuariogrupo = substr($usuario['username'], 5, 1);
                    elseif (strlen($usuario['username']) == 7):
                        $usuariogrupo = substr($usuario['username'], 5, 2);
                    endif;
                    echo $this->Html->link("Grupo: " . $usuariogrupo, ['controller' => 'Votacaos', 'action' => 'index', '?' => ['grupo' => $usuariogrupo]], ['class' => 'navbar-brand mb-0 h1']);
                elseif ($usuario['role'] === 'editor'):
                    echo "<span class = 'navbar-brand mb-0 h1'>Editor</p>";
                elseif ($usuario['role'] == 'admin'):
                    echo "<span class = 'navbar-brand mb-0 h1'>Administrador</span>";
                endif;
            else:
                echo "<span class = 'navbar-brand mb-0 h1'>Visitante</span>";
            endif;
            ?>
        </nav>

        <div class="container">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->fetch('content'); ?>
        </div>

        <div id="footer">
            <?php
            echo $this->Html->link(
                $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
                'http://www.cakephp.org/',
                array('target' => '_blank', 'escape' => false, 'id' => 'cake-powered')
            );
            ?>
            <p>
                <?php echo $cakeVersion; ?>
            </p>
        </div>
    </div>
    <?php echo $this->element('sql_dump'); ?>
</body>

</html>
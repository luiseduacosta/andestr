<nav class='navbar navbar-expand-lg navbar-light bg-light'>
    <?php echo $this->Html->link('ANDES-SN', 'http://www.andes.org.br', ['target' => '_blank', '_full' => true, 'class' => 'navbar-brand']); ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuprincipal"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Barra de navegação">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menuprincipal">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <?php echo $this->Html->link('Eventos', ['controller' => 'Eventos', 'action' => 'index'], ['class' => 'nav-link']); ?>
            </li>
            <li class="nav-item">
                <?php
                if (isset($usuario)):
                    // pr($usuario);
                    // die();
                    if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                        echo $this->Html->link('Usuários', ['controller' => 'users', 'action' => 'index'], ['class' => 'nav-link']);
                    endif;
                endif;
                // pr($usuario);
                ?>
            </li>
            <li class="nav-item">
                <?php echo $this->Html->link('Textos de apoio', ['controller' => 'Apoios', 'action' => 'index'], ['class' => 'nav-link']); ?>
            </li>
            <li class="nav-item">
                <?php echo $this->Html->link('TRs', ['controller' => 'Items', 'action' => 'index'], ['class' => 'nav-link']); ?>
            </li>
            <li class="nav-item">
                <?php
                if (isset($usuario)):
                    if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                        echo $this->Html->link('Votação', ['controller' => 'Votacaos', 'action' => 'index'], ['class' => 'nav-link']);
                    elseif ($usuario['role'] == 'relator'):
                        echo $this->Html->link('Votação', ['controller' => 'Items', 'action' => 'index', 'grupo' => substr($usuario['username'], 5, 2)], ['class' => 'nav-link']);
                    endif;
                endif;
                ?>
            </li>
            <li class="nav-item">
                <?php
                if (isset($usuario)):
                    if ($usuario['role'] == 'editor' || $usuario['role'] == 'admin'):
                        echo $this->Html->link('Grupos', ['controller' => 'Votacaos', 'action' => 'index'], ['class' => 'nav-link']);
                    elseif ($usuario['role'] == 'relator'):
                        if (strlen($usuario['username']) == 6):
                            $usuariogrupo = substr($usuario['username'], 5, 1);
                        elseif (strlen($usuario['username']) == 7):
                            $usuariogrupo = substr($usuario['username'], 5, 2);
                        endif;
                        // echo $this->Html->link('Grupos', '/Votacaos/index' . '/grupo:' . $usuariogrupo, ['class' => 'nav-link']);
                    endif;
                endif;
                ?>
            </li>
            <li class="nav-item">
                <?php echo $this->Html->link('Relatórios', ['controller' => 'Votacaos', 'action' => 'relatorio'], ['class' => 'nav-link']); ?>
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
    </div>
</nav>
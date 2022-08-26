<div class="wrapper bg-light">
    <nav class="main-header navbar navbar-expand navbar-dark border-bottom-0 text-sm">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" href="USUARIO_formPass.php">
                    <i class="fas fa-user-astronaut"></i>
                </a>
            </li>
            <li class="nav-item">
                <button type="submit" form="logout-form" class=" btn nav-link">
                    <i class="fas fa-power-off"></i>
                </button>
                <form id="logout-form"
                      action="../php/USUARIO_formLogoff.php"
                      method="POST" class="d-none">
                </form>
            </li>
        </ul>
    </nav>
    <?include_once('sidebar.php')?>
    <div class="content-wrapper bg-light">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <?
                    $auth->getApplicationPath
                    (
                        $auth->getApplicationCode
                        (
                            $auth->getApplicationName($_SERVER['PHP_SELF'])
                        )
                    );
                    ?>
                </div>
            </div>
        </section>

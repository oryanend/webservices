<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    .navbar-brand {
        font-family: "Poppins", sans-serif;
        font-weight: bold;
        color: #191919;
    }
    .nav-item {
        margin: 0;
        font-size: 14pt;
    }
    .nav-item > a.nav-link > img {
        margin-right: 12px;
    }
    .active {
        font-weight: initial;
        background-color: rgba(76, 175, 80, 0.3);
        padding: 10px;
        border-radius: 6px;
        box-shadow: rgba(0, 0, 0, 0.05) 0px 1px 2px 0px;
    }
    #offcanvasNavbarLabel {
        font-weight: bold;
    }
    .offcanvas-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .navbar-nav {
        flex-grow: 1;
    }
    .logout-button-container {
        padding: 20px 0;
    }
    #btnSair {
        width: 100%;
    }
</style>

<nav class="navbar navbar-custom fixed-top">
    <div class="container-fluid">
        <?php
        if ($_SESSION['tipo'] === 'PROFESSOR') {
            echo "<a class='navbar-brand' href='/easyFit/professor/painel.php'><img src='/easyFit/assets/images/blackIconEasyFit.png' alt='logo' width='30' height='28' class='d-inline-block align-text-top' id='logotipo'>EasyFit</a>";
        } else {
            echo "<a class='navbar-brand' href='/easyFit/admin/painel.php'><img src='/easyFit/assets/images/blackIconEasyFit.png' alt='logo' width='30' height='28' class='d-inline-block align-text-top' id='logotipo'>EasyFit</a>";
        }
        ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end offcanvas-custom" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Olá, <?php echo $_SESSION['name']; ?>!</h5>
                <button type="button" class="btn-close btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav">
                    <?php
                    if ($_SESSION['tipo'] === 'PROFESSOR') {
                        echo "<li class='nav-item'><a class='nav-link " . ($current_page == 'painel.php' ? 'active' : '') . "' href='/easyFit/professor/painel.php'><img src='/easyFit/assets/images/icons/homeSVG.svg' alt='logo' width='30' height='30' class='d-inline-block align-text-center' id='logotipo'>Home</a></li>";
                    } else {
                        echo "<li class='nav-item'><a class='nav-link " . ($current_page == 'painel.php' ? 'active' : '') . "' href='/easyFit/admin/painel.php'><img src='/easyFit/assets/images/icons/homeSVG.svg' alt='logo' width='30' height='30' class='d-inline-block align-text-center' id='logotipo'>Home</a></li>";
                    }

                    if ($_SESSION['tipo'] === 'ADMIN') {
                        echo "<li class='nav-item'><a class='nav-link " . ($current_page == 'manage_users.php' ? 'active' : '') . "' href='/easyFit/manage_users.php'><img src='/easyFit/assets/images/icons/userSVG.svg' alt='logo' width='30' height='30' class='d-inline-block align-text-center' id='logotipo'>Usuários</a></li>";
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'add_exercicios.php' ? 'active' : ''; ?>" href="/easyFit/add_exercicios.php"><img src='/easyFit/assets/images/icons/workoutSVG.svg' alt='logo' width='30' height='25' class='d-inline-block align-text-center' id='logotipo'>Adicionar Exercício</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'exercicios_usuario.php' ? 'active' : ''; ?>" href="/easyFit/exercicios_usuario.php"><img src='/easyFit/assets/images/icons/gymSVG.svg' alt='logo' width='30' height='25' class='d-inline-block align-text-center' id='logotipo'>Exercícios do Usuário</a>
                    </li>
                </ul>
                <div class="logout-button-container">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="window.location.href='/easyfit/logout.php'" id="btnSair">SAIR</button>
                </div>
            </div>
        </div>
    </div>
</nav>

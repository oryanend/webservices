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
        overflow: hidden;
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

<nav class="navbar navbar-custom sticky-top">
    <div class="container-fluid">
        <a class='navbar-brand' href='/easyFit/user/painel.php'><img src='/easyFit/assets/images/blackIconEasyFit.png' alt='logo' width='30' height='24' class='d-inline-block align-text-top'>EasyFit</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end offcanvas-custom" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Olá, <?php echo $_SESSION['name']; ?>!</h5>
                <button type="button" class="btn-close btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav flex-grow-1">
                    <?php
                    echo "<li class='nav-item'><a class='nav-link " . ($current_page == 'painel.php' ? 'active' : '') . "' href='/easyFit/user/painel.php'><img src='/easyFit/assets/images/icons/homeSVG.svg' alt='logo' width='30' height='30' class='d-inline-block align-text-center' id='logotipo'>Home</a></li>";
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'listaexercicios.php' ? 'active' : ''; ?>" href="/easyFit/user/listaexercicios.php"><img src='/easyFit/assets/images/icons/workoutSVG.svg' alt='logo' width='30' height='25' class='d-inline-block align-text-center' id='logotipo'>Exercícios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'perfil.php' ? 'active' : ''; ?>" href="/easyFit/user/perfil.php"><img src='/easyFit/assets/images/icons/userSVG.svg' alt='logo' width='30' height='25' class='d-inline-block align-text-center' id='logotipo'>Perfil</a>
                    </li>
                </ul>
                <div class="logout-button-container mt-auto">
                    <button type="button" class="btn btn-outline-danger w-100" onclick="window.location.href='../logout.php'" id="btnSair">SAIR</button>
                </div>
            </div>
        </div>
    </div>
</nav>

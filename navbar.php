<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Define nível de pastas */
$nivel = isset($pastaAtual) ? $pastaAtual : "";
?>

<!-- ================= NAVBAR ================== -->
<link rel="stylesheet" href="<?= $nivel ?>style.css">

<div class="navbar">
    <div class="header-inner-content">

        <h1 class="logo">Aqua<span>Mind</span></h1>

        <nav>
            <ul>
                <li><a href="<?= $nivel ?>index.php">Início</a></li>
                <li><a href="<?= $nivel ?>index.php#sobre-empresa">Sobre Nós</a></li>

                <!-- DROPDOWN PRODUTOS -->
<li class="dropdown">
    <a href="#">Produtos <span class="dropdown-arrow">▼</span></a>
    <ul class="dropdown-menu">
        <li><a href="<?= $nivel ?>Produtos/tratamento.php">Tratamento de Água</a></li>
        <li><a href="<?= $nivel ?>Produtos/equipamentos.php">Equipamentos de Piscina</a></li>
    </ul>
</li>


                <?php if (!isset($_SESSION['usuario_nome'])): ?>
                    <li><a href="<?= $nivel ?>login/login.php">Login</a></li>
                    <li><a href="<?= $nivel ?>cadastro/cadastro.php">Cadastro</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="nav-icon-container">

            <!-- DARK MODE -->
            <button id="darkmode-toggle">🌙</button>

            <!-- ACESSIBILIDADE -->
            <div id="accessibility-controls">
                <button id="increase-font">A+</button>
                <button id="decrease-font">A-</button>
            </div>

            <!-- USUÁRIO LOGADO -->
            <?php if (isset($_SESSION['usuario_nome'])): ?>
                <div class="user-dropdown">
                    <button id="userButton" class="user-btn">
                        👤 <?= htmlspecialchars($_SESSION['usuario_nome']) ?> ▼
                    </button>
                    <div class="user-menu" id="userMenu">
                        <a href="<?= $nivel ?>login/alterar_senha.php">🔑 Alterar Senha</a>
                        <?php if ($_SESSION['tipo_usuario'] === 'master'): ?>
                            <a href="<?= $nivel ?>configuracao/configuracao.php"> ⚙️ Painel Master</a>
                            <a href="<?= $nivel ?>admin/log_acessos.php">⚙️ Tela log</a>
                        <?php endif; ?>
                        <a href="<?= $nivel ?>logout.php">🚪 Sair</a>
                    </div>
                </div>
            <?php endif; ?>

            <img src="<?= $nivel ?>img/menu.png" class="menu-button" alt="Menu">
        </div>

    </div>
</div>

<!-- ================= JAVASCRIPT GLOBAL DO NAVBAR ================= -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    /* ===== MENU MOBILE ===== */
    const navbar = document.querySelector(".navbar");
    const menubutton = document.querySelector(".menu-button");

    if (menubutton) {
        menubutton.addEventListener("click", () => {
            navbar.classList.toggle("show-menu");
        });
    }

    /* ===== DARK MODE ===== */
    const darkModeToggle = document.getElementById("darkmode-toggle");
    const body = document.body;

    if (localStorage.getItem("darkMode") === "enabled") {
        body.classList.add("dark-mode");
        darkModeToggle.textContent = "☀";
    }

    darkModeToggle.addEventListener("click", () => {
        const active = body.classList.toggle("dark-mode");
        darkModeToggle.textContent = active ? "☀️" : "🌙";
        localStorage.setItem("darkMode", active ? "enabled" : "disabled");
    });

    /* ===== ACESSIBILIDADE ===== */
    let fontSize = localStorage.getItem("fontSize")
        ? parseInt(localStorage.getItem("fontSize"))
        : 100;

    document.body.style.fontSize = fontSize + "%";

    document.getElementById("increase-font").addEventListener("click", () => {
        if (fontSize < 150) {
            fontSize += 10;
            document.body.style.fontSize = fontSize + "%";
            localStorage.setItem("fontSize", fontSize);
        }
    });

    document.getElementById("decrease-font").addEventListener("click", () => {
        if (fontSize > 70) {
            fontSize -= 10;
            document.body.style.fontSize = fontSize + "%";
            localStorage.setItem("fontSize", fontSize);
        }
    });

    /* ===== DROPDOWN USUÁRIO ===== */
    const userButton = document.getElementById("userButton");
    const userDropdown = document.querySelector(".user-dropdown");

    if (userButton && userDropdown) {

        const userMenu = document.getElementById("userMenu");

        userButton.addEventListener("click", (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle("show");
        });

        userMenu.addEventListener("click", (e) => e.stopPropagation());

        document.addEventListener("click", () => {
            userDropdown.classList.remove("show");
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") userDropdown.classList.remove("show");
        });
    }

    /* ===== DROPDOWN MOBILE (Produtos) ===== */
    const dropdownLinks = document.querySelectorAll("li.dropdown > a");

    dropdownLinks.forEach(link => {
        const parentLi = link.parentElement;

        link.addEventListener("click", function(e) {
            if (window.innerWidth <= 800) {
                e.preventDefault(); // evita navegação
                parentLi.classList.toggle("open"); // abre ou fecha submenu
            }
        });
    });

});
</script>

document.getElementById('form').addEventListener('submit', function (e) {
  e.preventDefault();

  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  // Validações básicas (ajuste conforme regras reais)
  const loginValido = /^[a-zA-Z]{6}$/.test(username);
  const senhaValida = /^[a-zA-Z]{8}$/.test(password);

  if (loginValido && senhaValida) {
    // Salva o nome e a senha no localStorage
    localStorage.setItem('usuarioLogado', username);
    localStorage.setItem('senhaUsuario', password);

    // Redireciona para a página principal após login bem-sucedido
    window.location.href = '/index.html';
  } else {
    document.getElementById('mensagem').textContent = "Login ou senha inválidos.";
  }
});

// Verificar se o usuário está logado ao carregar a página de login
window.onload = function () {
  const userNameElement = document.getElementById('nome-usuario');
  const userInfo = document.querySelector('.container-logado');
  const btnLogout = document.getElementById('btn-logout');
  const usuarioLogado = localStorage.getItem('usuarioLogado');

  if (usuarioLogado) {
    // Exibe área logada
    userNameElement.textContent = `Bem-vindo, ${usuarioLogado}`;
    userInfo.style.display = 'block';
  } else {
    userInfo.style.display = 'none';
  }

  // Botão de logout
  btnLogout.addEventListener('click', function () {
    localStorage.removeItem('usuarioLogado');
    localStorage.removeItem('senhaUsuario');
    userInfo.style.display = 'none';
    window.location.href = '/login/login.html';
  });
};

// Toggle menu
const navbar = document.querySelector(".navbar");
const menubutton = document.querySelector(".menu-button");

menubutton.addEventListener("click", () => {
  navbar.classList.toggle("show-menu");
});

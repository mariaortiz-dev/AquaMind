// ==========================
// CPF - BLOQUEIA LETRAS, FORMATA E VALIDA
// ==========================
const cpfInput = document.getElementById("cpf");
const resultado = document.getElementById("resultado");

cpfInput.addEventListener("input", function (e) {
  // Remove tudo que não for número
  let valor = e.target.value.replace(/\D/g, "");

  // Limita a 11 dígitos
  if (valor.length > 11) valor = valor.slice(0, 11);

  // Aplica máscara 000.000.000-00
  valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
  valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
  valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

  e.target.value = valor;

  // Quando o CPF estiver completo, valida automaticamente
  if (valor.replace(/\D/g, "").length === 11) {
    if (validarCPF(valor)) {
      resultado.style.color = "green";
      resultado.textContent = "✅ CPF válido";
      cpfInput.style.borderColor = "green";
    } else {
      resultado.style.color = "red";
      resultado.textContent = "❌ CPF inválido";
      cpfInput.style.borderColor = "red";
    }
  } else {
    resultado.textContent = "";
    cpfInput.style.borderColor = "";
  }
});

// Função para validar CPF
function validarCPF(cpf) {
  cpf = cpf.replace(/[^\d]+/g, "");
  if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;

  let soma = 0, resto;

  for (let i = 1; i <= 9; i++)
    soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;
  if (resto !== parseInt(cpf.substring(9, 10))) return false;

  soma = 0;
  for (let i = 1; i <= 10; i++)
    soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) resto = 0;

  return resto === parseInt(cpf.substring(10, 11));
}


// ==========================
// VALIDAÇÃO E BUSCA DE CEP (SEM ALERT)
// ==========================
const cepInput = document.getElementById("cep");
const cepErro = document.getElementById("cepErro");
const ruaInput = document.getElementById("rua");
const bairroInput = document.getElementById("bairro");
const cidadeInput = document.getElementById("cidade");
const ufInput = document.getElementById("uf");

function limpaEnderecoVisual() {
  ruaInput.value = "";
  bairroInput.value = "";
  cidadeInput.value = "";
  ufInput.value = "";
  cepErro.textContent = "";
  cepInput.style.borderColor = "";
}

// Máscara do CEP: 00000-000
cepInput.addEventListener("input", function (e) {
  let valor = e.target.value.replace(/\D/g, "");
  if (valor.length > 8) valor = valor.slice(0, 8);
  if (valor.length > 5) valor = valor.replace(/(\d{5})(\d{1,3})/, "$1-$2");
  e.target.value = valor;
  cepErro.textContent = "";
  cepInput.style.borderColor = "";
});

// Validação e busca com ViaCEP
cepInput.addEventListener("blur", async function () {
  const cep = cepInput.value.replace(/\D/g, "");

  if (cep === "") {
    cepErro.textContent = "⚠️ Por favor, preencha o CEP.";
    cepInput.style.borderColor = "red";
    limpaEnderecoVisual();
    return;
  }

  const validacep = /^[0-9]{8}$/;
  if (!validacep.test(cep)) {
    cepErro.textContent = "❌ CEP inválido. Digite 8 números (ex: 12345-678).";
    cepInput.style.borderColor = "red";
    limpaEnderecoVisual();
    return;
  }

  cepErro.textContent = "🔎 Buscando endereço...";
  cepErro.classList.remove("erro");
  cepErro.classList.add("sucesso");
  cepInput.style.borderColor = "";

  try {
    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);

    // Verifica se a resposta é válida
    if (!response.ok) throw new Error("Erro na resposta do servidor");

    const data = await response.json();

    if (data.erro) {
      cepErro.textContent = "❌ CEP não encontrado.";
      cepErro.classList.add("erro");
      cepInput.style.borderColor = "red";
      limpaEnderecoVisual();
      return;
    }

    // Preenche os campos
    ruaInput.value = data.logradouro || "";
    bairroInput.value = data.bairro || "";
    cidadeInput.value = data.localidade || "";
    ufInput.value = data.uf || "";

    cepErro.textContent = "✅ Endereço encontrado!";
    cepErro.classList.remove("erro");
    cepErro.classList.add("sucesso");
    cepInput.style.borderColor = "green";

  } catch (error) {
    console.error("Erro no fetch do ViaCEP:", error);
    cepErro.textContent = "❌ Erro ao buscar o CEP. Verifique sua conexão.";
    cepErro.classList.add("erro");
    cepInput.style.borderColor = "green";
    limpaEnderecoVisual();
  }
});


// ==========================
// VALIDAÇÃO TELEFONE 
// ==========================

const telefoneInput = document.getElementById("telefone");
const telefoneErro = document.getElementById("telefone-erro");

telefoneInput.addEventListener("input", function (e) {
  let valor = e.target.value.replace(/\D/g, "");
  if (valor.length > 11) valor = valor.slice(0, 11);

  const formato = valor.length <= 10 ? "($1) $2-$3" : "($1) $2-$3";
  e.target.value = valor.replace(/(\d{2})(\d{4})(\d{0,4})/, formato);
  e.target.value = valor.replace(/(\d{2})(\d{5})(\d{0,4})/, formato);
});

telefoneInput.addEventListener("blur", function () {
  const telefoneValor = telefoneInput.value.replace(/\D/g, "").trim();
  if (telefoneValor === "") {
    telefoneErro.textContent = "⚠️ Preencha seu número de telefone.";
    telefoneInput.style.borderColor = "red";
  } else if (telefoneValor.length < 10 || telefoneValor.length > 11) {
    telefoneErro.textContent = "❌ Número de telefone inválido.";
    telefoneInput.style.borderColor = "red";
  } else if (/^(\d)\1+$/.test(telefoneValor)) {
    telefoneErro.textContent = "❌ Número de telefone inválido (repetido).";
    telefoneInput.style.borderColor = "red";
  } else {
    telefoneErro.textContent = "";
    telefoneInput.style.borderColor = "green";
  }
});

// ==========================
// MOSTRAR / OCULTAR SENHA
// ==========================
function mostrarSenha() {
  const input = document.getElementById("senha");
  const icon = document.getElementById("btn-senha");
  if (input.type === "password") {
    input.type = "text";
    icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
  } else {
    input.type = "password";
    icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
  }
}

function mostrarSenhas() {
  const input = document.getElementById("confirmacaoSenha");
  const icon = document.getElementById("btns-senha");
  if (input.type === "password") {
    input.type = "text";
    icon.classList.replace("bi-eye-fill", "bi-eye-slash-fill");
  } else {
    input.type = "password";
    icon.classList.replace("bi-eye-slash-fill", "bi-eye-fill");
  }
}

// ==========================
// VALIDAÇÃO DE SENHAS IGUAIS
// ==========================
const form = document.getElementById("form");
const senha = document.getElementById("senha");
const senhas = document.getElementById("confirmacaoSenha");
const errorMessage = document.getElementById("errorMessage");

form.addEventListener("submit", function (event) {
  if (senha.value !== senhas.value) {
    event.preventDefault();
    errorMessage.textContent = "⚠️ Senha e confirmação não coincidem.";
    senha.focus();
  } else {
    errorMessage.textContent = "";
  }
});


// ==========================
// FORMATAÇÃO E VALIDAÇÃO - NOME COMPLETO
// ==========================
const nomeCompletoInput = document.getElementById("nome");
const nomeErro = document.getElementById("nome-erro");

nomeCompletoInput.addEventListener("input", function () {
  // Remove números e caracteres especiais, mantendo apenas letras e espaços
  nomeCompletoInput.value = nomeCompletoInput.value.replace(/[^A-Za-zÀ-ÿ\s]/g, "");

  // Coloca a primeira letra de cada palavra em maiúscula
  nomeCompletoInput.value = nomeCompletoInput.value
    .toLowerCase()
    .replace(/(^\w{1})|(\s+\w{1})/g, letra => letra.toUpperCase());
});

nomeCompletoInput.addEventListener("blur", function () {
  const nome = nomeCompletoInput.value.trim();

  if (nome === "") {
    nomeErro.textContent = "⚠️ O campo Nome Completo é obrigatório.";
    nomeCompletoInput.style.borderColor = "red";
  } else if (nome.split(" ").length < 2) {
    nomeErro.textContent = "❌ Digite (Nome e Sobrenome).";
    nomeCompletoInput.style.borderColor = "red";
  } else {
    nomeErro.textContent = "";
    nomeCompletoInput.style.borderColor = "green";
  }
});


// ==========================
// FORMATAÇÃO E VALIDAÇÃO - NOME MATERNO
// ==========================
const nomeMaternoInput = document.getElementById("materno");
const nomeMaternoErro = document.getElementById("nome-materno-erro");

nomeMaternoInput.addEventListener("input", function () {
  // Remove números e caracteres especiais, mantendo apenas letras e espaços
  nomeMaternoInput.value = nomeMaternoInput.value.replace(/[^A-Za-zÀ-ÿ\s]/g, "");

  // Coloca a primeira letra de cada palavra em maiúscula
  nomeMaternoInput.value = nomeMaternoInput.value
    .toLowerCase()
    .replace(/(^\w{1})|(\s+\w{1})/g, letra => letra.toUpperCase());
});

nomeMaternoInput.addEventListener("blur", function () {
  const nome = nomeMaternoInput.value.trim();

  if (nome === "") {
    nomeMaternoErro.textContent = "⚠️ O campo Nome Materno é obrigatório.";
    nomeMaternoInput.style.borderColor = "red";
  } else if (nome.split(" ").length < 2) {
    nomeMaternoErro.textContent = "❌  Digite (Nome e Sobrenome).";
    nomeMaternoInput.style.borderColor = "red";
  } else {
    nomeMaternoErro.textContent = "";
    nomeMaternoInput.style.borderColor = "green";
  }
});


// ==========================
// VALIDAÇÃO DA DATA DE NASCIMENTO
// ==========================
const dataInput = document.getElementById("data");
const dataErro = document.getElementById("data-erro");

dataInput.addEventListener("blur", function () {
  const valor = dataInput.value;
  if (!valor) {
    dataErro.textContent = "⚠️ Preencha sua data de nascimento.";
    dataInput.style.borderColor = "red";
    return;
  }

  const dataNascimento = new Date(valor);
  const hoje = new Date();

  const idade = hoje.getFullYear() - dataNascimento.getFullYear();
  const mes = hoje.getMonth() - dataNascimento.getMonth();

  let idadeCorreta = idade;
  if (mes < 0 || (mes === 0 && hoje.getDate() < dataNascimento.getDate())) {
    idadeCorreta--;
  }

  if (dataNascimento > hoje) {
    dataErro.textContent = "❌ Data inválida! Você não pode ter nascido no futuro.";
    dataInput.style.borderColor = "red";
  } else if (idadeCorreta < 10 || idadeCorreta > 120) {
    dataErro.textContent = "⚠️ Idade fora do intervalo permitido (10 a 120 anos).";
    dataInput.style.borderColor = "red";
  } else {
    dataErro.textContent = "";
    dataInput.style.borderColor = "green";
  }
});

// ==========================
// FORMATAÇÃO LOGIN PARA TER EXATAMENTE 6 CARACTERES ALFABÉTICOS
// ==========================
document.getElementById('login').addEventListener('blur', function() {
  const loginInput = document.getElementById('login');
  const loginErro = document.getElementById('login-erro');
  if (loginInput.value.length !== 6) {
    loginErro.textContent = '⚠️ O login deve ter exatamente 6 letras.';
    loginInput.style.borderColor = 'red';
  } else {
    loginErro.textContent = '';
    loginInput.style.borderColor = 'green';
  }
});
document.getElementById('login').addEventListener('keypress', function(event) {
  const charCode = event.charCode;
  if (!(charCode >= 65 && charCode <= 90) && !(charCode >= 97 && charCode <= 122)) {
    event.preventDefault();
  }
});





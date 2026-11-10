<?php 
$pastaAtual = "../"; 
include "../navbar.php"; 
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AquaMind</title>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../Cadastro/cadastro.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<form id="form" action="salvar_cadastro.php" method="post">
  <h1>Cadastro</h1><br>

  <!-- ====================== ETAPA 1 ====================== -->
  <div class="step" id="etapa1">

    <div class="cadastro">
      <h2>Dados Pessoais</h2>

      <label for="nome">Nome Completo:</label>
      <input type="text" id="nome" name="nome" minlength="8" maxlength="60"
        placeholder="Nome Completo (entre 8 e 60 caracteres)." required>
      <span id="nome-erro" class="erro" style="color: red;"></span><br><br>

      <label for="data">Data de Nascimento:</label>
      <input type="date" id="data" name="data" required>
      <span id="data-erro" class="erro" style="color: red;"></span><br><br>

      <label>Gênero:</label>
      Masculino <input type="radio" name="genero" value="masculino" required>
      Feminino <input type="radio" name="genero" value="feminino" required>
      Outros <input type="radio" name="genero" value="outros" required><br><br>

      <label for="materno">Nome Materno:</label>
      <input type="text" id="materno" name="nome_materno" minlength="8" maxlength="60"
        placeholder="Nome Materno (entre 8 e 60 caracteres)." required>
      <span id="nome-materno-erro" class="erro" style="color: red;"></span><br><br>

      <label for="email">E-mail:</label>
      <input type="email" id="email" name="email"
        pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|com\.br)$"
        title="O e-mail deve terminar com .com ou .com.br"
        placeholder="exemplo@dominio.com ou exemplo@dominio.com.br"
        required><br><br>

      <label for="telefone">Telefone Celular:</label>
      <input type="tel" id="telefone" name="telefone" maxlength="15"
        placeholder="Ex: (21) 99999-9999" required>
      <span id="telefone-erro" style="color: red;"></span><br><br>

      <label for="cpf">CPF:</label>
      <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="Digite seu CPF" required>
      <span id="resultado" style="color: red;"></span><br><br>

      <label for="cep">CEP:</label>
      <input type="text" id="cep" name="cep" maxlength="9" placeholder="Digite seu CEP" required>
      <span id="cepErro" class="erro" style="color: red;"></span><br><br>

      <label for="rua">Endereço:</label>
      <input type="text" id="rua" name="rua" readonly><br><br>

      <label for="bairro">Bairro:</label>
      <input type="text" id="bairro" name="bairro" readonly><br><br>

      <label for="cidade">Cidade:</label>
      <input type="text" id="cidade" name="cidade" readonly><br><br>

      <label for="uf">Estado:</label>
      <input type="text" id="uf" name="uf" readonly><br><br>

      <label for="numero">Número:</label>
      <input type="text" id="numero" name="numero">
    </div>
  </div>

  <!-- ====================== ETAPA 2 ====================== -->
  <div class="step" id="etapa2">
    <div class="login"><br><br>

      <h2>Criando seu Login</h2>

      <label for="login">Login:</label>
      <input type="text" id="login" name="login" minlength="6" maxlength="6"
        title="O login deve ter exatamente 6 letras." placeholder="Digite o login (6 letras)." required>
      <span id="login-erro" style="color: red;"></span><br><br>

      <!-- SENHA -->
      <label for="senha">Senha:</label>
      <div class="senha-container">
        <input type="password" id="senha" name="senha" minlength="8" maxlength="8"
          title="A senha deve ter 8 caracteres, podendo conter letras e números."
          placeholder="Digite a senha." required>
        <i class="bi bi-eye-fill" id="btn-senha" onclick="mostrarSenha()"></i>
      </div><br><br>

      <!-- CONFIRMAÇÃO -->
      <label for="confirmacaoSenha">Confirmação de senha:</label>
      <div class="senha-container">
        <input type="password" id="confirmacaoSenha" name="confirmacaoSenha" minlength="8" maxlength="8"
          title="Confirme sua senha (8 caracteres)." placeholder="Confirme sua senha." required>
        <i class="bi bi-eye-fill" id="btns-senha" onclick="mostrarSenhas()"></i>
      </div>

      <p id="errorMessage" style="color: red;"></p>

    </div>
  </div>

  <!-- BOTÕES ORIGINAIS -->
  <button class="btn" type="submit">Cadastrar</button>
  <button class="btn-r" type="reset">Limpar</button>

</form>

<script src="cadastro.js"></script>
</body>
</html>

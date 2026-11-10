<?php
include_once("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recebendo dados
    $nome = trim($_POST['nome']);
    $data = $_POST['data'];
    $dataNascimento = new DateTime($data);
    $hoje = new DateTime();
    $idade = $hoje->diff($dataNascimento)->y;
    $genero = $_POST['genero'];
    $nome_materno = trim($_POST['nome_materno']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $cpf = trim($_POST['cpf']);
    $cep = trim($_POST['cep']);
    $rua = trim($_POST['rua']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $uf = trim($_POST['uf']);
    $numero = trim($_POST['numero']);
    $login = trim($_POST['login']);
    $senha = $_POST['senha'];
    $confirmacaoSenha = $_POST['confirmacaoSenha'];


    $errors = [];

    // ================= VALIDAÇÕES =================

    if (strlen($nome) < 8 || strlen($nome) > 60) {
        $errors[] = "O nome deve ter entre 8 e 60 caracteres.";
    }

    if (strlen($nome_materno) < 8 || strlen($nome_materno) > 60) {
        $errors[] = "O nome materno deve ter entre 8 e 60 caracteres.";
    }

    if ($dataNascimento > $hoje) {
        $errors[] = "A data de nascimento não pode ser no futuro.";
    } elseif ($idade < 10 || $idade > 120) {
        $errors[] = "A idade deve estar entre 10 e 120 anos.";
    }

    if (!in_array($genero, ['masculino', 'feminino', 'outros'])) {
        $errors[] = "Gênero inválido.";
    }

    if (strlen($nome_materno) < 8 || strlen($nome_materno) > 60) {
        $errors[] = "O nome materno deve ter entre 8 e 60 caracteres.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/\.(com|com\.br)$/", $email)) {
        $errors[] = "E-mail inválido. Deve terminar com .com ou .com.br.";
    }

    if (!preg_match("/^\(\d{2}\) \d{4,5}-\d{4}$/", $telefone)) {
        $errors[] = "Telefone inválido. Use o formato (XX) XXXXX-XXXX.";
    }

    if (!preg_match("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf)) {
        $errors[] = "CPF inválido. Use o formato XXX.XXX.XXX-XX.";
    }

    if (!preg_match("/^\d{5}-\d{3}$/", $cep)) {
        $errors[] = "CEP inválido. Use o formato XXXXX-XXX.";
    }

    if (strlen($login) != 6) {
        $errors[] = "O login deve ter exatamente 6 caracteres.";
    }

    if (strlen($senha) != 8) {
        $errors[] = "A senha deve ter exatamente 8 caracteres.";
    }

    if ($senha !== $confirmacaoSenha) {
        $errors[] = "A confirmação de senha não confere.";
    }

    // ================= VERIFICAR DUPLICIDADES =================

    $checks = [
        ["cpf", $cpf, "Este CPF já está cadastrado."],
        ["email", $email, "Este e-mail já está cadastrado."],
        ["telefone", $telefone, "Este telefone já está em uso."],
        ["login", $login, "Este login já está sendo utilizado."]
    ];

    foreach ($checks as $check) {

        $campo = $check[0];
        $valor = $check[1];
        $mensagem = $check[2];

        $query = $conn->prepare("SELECT id FROM usuarios WHERE $campo = ?");
        $query->bind_param("s", $valor);
        $query->execute();
        $query->store_result();

        if ($query->num_rows > 0) {
            $errors[] = $mensagem;
        }

        $query->close();
    }

    // ================= SE HOUVER ERROS =================

    if (!empty($errors)) {
        $mensagemErro = implode("\\n", $errors);
        echo "<script>alert('⚠️ Erros encontrados:\\n$mensagemErro'); window.history.back();</script>";
        exit;
    }

    // ================= INSERÇÃO =================

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios 
        (nome, data_nascimento, genero, nome_materno, email, telefone, cpf, cep, rua, bairro, cidade, uf, numero, login, senha)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssss",
        $nome,
        $data,
        $genero,
        $nome_materno,
        $email,
        $telefone,
        $cpf,
        $cep,
        $rua,
        $bairro,
        $cidade,
        $uf,
        $numero,
        $login,
        $senhaHash
    );

    if ($stmt->execute()) {
        echo "<script>alert('✅ Cadastro realizado com sucesso!'); window.location.href='../login/login.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "⚠️ Acesso inválido!";
}

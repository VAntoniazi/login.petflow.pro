<?php
require '../config.php';
date_default_timezone_set('America/Sao_Paulo');

function resposta($status, $mensagem) {
    echo json_encode(['status' => $status, 'mensagem' => $mensagem]);
    exit;
}

// Lógica de recuperação
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if ($_POST['tipo'] === 'solicitar_token') {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) resposta('erro', 'E-mail inválido.');

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if (!$stmt->fetch()) resposta('erro', 'E-mail não encontrado.');

        $token = bin2hex(random_bytes(32));
        $expira_em = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $pdo->prepare("DELETE FROM usuarios_senha_resets WHERE email = ?")->execute([$email]);
        $pdo->prepare("INSERT INTO usuarios_senha_resets (email, token, expira_em) VALUES (?, ?, ?)")->execute([$email, $token, $expira_em]);

        $link = "https://login.petflow.pro/recuperar-senha/index.php?token=$token";
        $mensagem = "Olá,\n\nClique no link abaixo para redefinir sua senha:\n$link\n\nEste link expira em 30 minutos.";
        $assunto = "Recuperação de Senha - PetFlow";
        $headers = "From: suporte@petflow.pro\r\nContent-Type: text/plain; charset=UTF-8";

        if (!mail($email, $assunto, $mensagem, $headers)) resposta('erro', 'Erro ao enviar e-mail.');
        resposta('ok', 'Link de recuperação enviado para seu e-mail.');
    }

    if ($_POST['tipo'] === 'resetar_senha') {
        $token = $_POST['token'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $confirmar = $_POST['confirmar'] ?? '';

        if (strlen($senha) < 6) resposta('erro', 'A senha deve ter no mínimo 6 caracteres.');
        if ($senha !== $confirmar) resposta('erro', 'As senhas não coincidem.');

        $agora = date('Y-m-d H:i:s');
        $stmt = $pdo->prepare("SELECT email, expira_em, usado_em FROM usuarios_senha_resets WHERE token = ?");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if (!$reset) resposta('erro', 'Token inválido.');
        if ($reset['usado_em']) resposta('erro', 'Este link de recuperação já foi utilizado.');
        if ($reset['expira_em'] < $agora) resposta('erro', 'O link expirou. Solicite um novo.');

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?")->execute([$senhaHash, $reset['email']]);
        $pdo->prepare("UPDATE usuarios_senha_resets SET usado_em = ? WHERE token = ?")->execute([$agora, $token]);

        resposta('ok', 'Senha redefinida com sucesso.');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Recuperar Senha - PetFlow</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- Header -->
  <header class="bg-white py-4 px-4 flex justify-center items-center shadow-sm">
    <div class="flex items-center space-x-3">
      <img src="https://app.petflow.pro/assets/images/logo.png" alt="Logo PetFlow" class="w-8 h-auto">
      <span class="text-indigo-700 text-lg font-bold">PetFlow</span>
    </div>
  </header>

  <!-- Conteúdo -->
  <main class="flex-1 flex items-center justify-center px-4">
  <div class="bg-white shadow-xl rounded-xl w-full max-w-md p-8 my-10">
    
    <div class="mb-6 text-center">
      <h2 id="titulo" class="text-2xl sm:text-3xl font-extrabold text-indigo-700">Recuperar Senha</h2>
      <p class="mt-2 text-sm text-gray-600">
        Informe seu e-mail e, caso exista uma conta associada, enviaremos um link para redefinir sua senha.
        <br class="hidden sm:inline"> Verifique também sua caixa de spam.
      </p>
    </div>

    <!-- Mensagem -->
    <div id="mensagem" class="hidden text-sm text-center mb-4"></div>

    <!-- Formulário: solicitar token -->
    <form id="formEmail" class="space-y-4">
      <input type="email" name="email" id="email" placeholder="seu@email.com"
        class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      <button type="submit"
        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md font-medium text-sm transition duration-150">
        Enviar link de recuperação
      </button>
    </form>

    <!-- Formulário: redefinir senha -->
    <form id="formNovaSenha" class="space-y-4 hidden mt-6">
      <input type="hidden" name="token" id="token">
      <input type="password" name="senha" id="senha" placeholder="Nova senha"
        class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      <input type="password" name="confirmar" id="confirmar" placeholder="Confirmar senha"
        class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
      <button type="submit"
        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-md font-medium text-sm transition duration-150">
        Redefinir Senha
      </button>
    </form>
  </div>
</main>


  <!-- Footer -->
  <footer class="bg-gray-100 text-gray-700 py-10 border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-sm">
      <div>
        <h3 class="text-base font-semibold mb-3 text-indigo-700 uppercase tracking-wide">PetFlow.PRO</h3>
        <p class="mb-2">Sistema completo para petshops e serviços de banho & tosa, com agendamento online e integração ao WhatsApp.</p>
        <p class="text-xs text-gray-500">&copy; 2025 PetFlow — Todos os direitos reservados.</p>
      </div>
      <div>
        <h3 class="text-base font-semibold mb-3 text-indigo-700 uppercase tracking-wide">Contato</h3>
        <ul class="space-y-2">
          <li><span class="font-medium">E-mail:</span> suporte@petflow.pro</li>
          <li><span class="font-medium">WhatsApp:</span> +55 (51) 99999-9999</li>
          <li><span class="font-medium">CNPJ:</span> 51.027.815/0001-03</li>
        </ul>
      </div>
      <div>
        <h3 class="text-base font-semibold mb-3 text-indigo-700 uppercase tracking-wide">Links úteis</h3>
        <ul class="space-y-2">
          <li><a href="#" class="hover:text-indigo-600 transition-colors">Termos de Uso</a></li>
          <li><a href="#" class="hover:text-indigo-600 transition-colors">Política de Privacidade</a></li>
          <li><a href="https://pineapplelab.net" class="hover:text-indigo-600 transition-colors" target="_blank">Pineapple Lab</a></li>
        </ul>
      </div>
    </div>
    <div class="mt-8 text-center text-xs text-gray-400">
      Desenvolvido com 💜 por <a href="https://pineapplelab.net" class="text-indigo-500 hover:underline">Pineapple Lab</a>.
    </div>
  </footer>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const temToken = urlParams.has('token');
    const token = urlParams.get('token');
    const formEmail = document.getElementById('formEmail');
    const formNovaSenha = document.getElementById('formNovaSenha');
    const msg = document.getElementById('mensagem');
    const titulo = document.getElementById('titulo');

    if (temToken) {
      formEmail.classList.add('hidden');
      formNovaSenha.classList.remove('hidden');
      titulo.innerText = "Redefinir Senha";
      document.getElementById('token').value = token;
    }

    function mostrarMensagem(texto, tipo = 'ok') {
      msg.textContent = texto;
      msg.classList.remove('hidden', 'text-red-600', 'text-green-600');
      msg.classList.add(tipo === 'ok' ? 'text-green-600' : 'text-red-600');
    }

    formEmail.addEventListener('submit', async (e) => {
      e.preventDefault();
      const dados = new FormData(formEmail);
      dados.append('tipo', 'solicitar_token');

      const res = await fetch('', { method: 'POST', body: dados });
      const json = await res.json();
      mostrarMensagem(json.mensagem, json.status);
    });

    formNovaSenha.addEventListener('submit', async (e) => {
      e.preventDefault();
      const dados = new FormData(formNovaSenha);
      dados.append('tipo', 'resetar_senha');

      const res = await fetch('', { method: 'POST', body: dados });
      const json = await res.json();
      mostrarMensagem(json.mensagem, json.status);

      if (json.status === 'ok') {
        formNovaSenha.reset();
        setTimeout(() => window.location.href = "https://login.petflow.pro", 2500);
      }
    });
  </script>
</body>
</html>

<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - PetFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex flex-col">

    <header class="bg-white py-4 px-4 flex justify-center items-center shadow-md">
    <div class="flex items-center space-x-3">
        <img src="https://app.petflow.pro/assets/images/logo.png" alt="Logo PetFlow" class="w-10 h-auto drop-shadow-md" />
        <span class="text-black-700 text-xl sm:text-2xl font-bold tracking-wide">PetFlow</span>
    </div>
</header>

    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 lg:px-8" style="margin: 20px;">
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Acesse sua conta
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Ou <a href="https://cadastro.petflow.pro" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">crie uma nova conta</a>
                </p>
            </div>

            <?php if (!empty($_SESSION['login_error'])): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                    <?= $_SESSION['login_error']; ?>
                </div>
                <?php unset($_SESSION['login_error']); ?>
            <?php endif; ?>

            <form class="space-y-6" action="autenticar.php" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="seu@email.com">
                    </div>
                </div>

                <div>
                    <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                    <div class="mt-1 relative">
                        <input id="senha" name="senha" type="password" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm pr-10"
                            placeholder="••••••••">
                        <button type="button" id="toggleSenha"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-700">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <span class="ml-2">Lembrar-me</span>
                    </label>
                    <a href="https://login.petflow.pro/recuperar-senha" class="text-sm text-indigo-600 hover:text-indigo-500">Esqueceu sua senha?</a>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Entrar
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300"></div></div>
                    <div class="relative flex justify-center text-sm">
                    </div>
                </div>
            </div>
        </div>
    </main>

<footer class="bg-gray-100 text-gray-700 py-10 mt-12 border-t border-gray-200">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 text-sm">

      <div>
        <h3 class="text-base font-semibold mb-3 text-indigo-700 uppercase tracking-wide">PetFlow</h3>
        <p class="mb-2">A solução definitiva para petshops que buscam agilidade, organização e atendimento automatizado em um só lugar.</p>
        <p class="text-xs text-gray-500 mt-4">&copy; 2025 PetFlow.pro  — Todos os direitos reservados.</p>
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

    <div class="mt-8 border-t pt-4 text-center text-xs text-gray-400">
      Desenvolvido com <span class="text-indigo-500">💜</span> por <a href="https://pineapplelab.net" class="text-indigo-500 hover:underline">Pineapple Lab Brasil</a>.
    </div>
  </div>
</footer>

    <script>
        const senhaInput = document.getElementById('senha');
        const toggleSenhaButton = document.getElementById('toggleSenha');
        const eyeIcon = `<svg xmlns="http://www.w3.org/2000/svg"...></svg>`;
        const eyeSlashIcon = `<svg xmlns="http://www.w3.org/2000/svg"...></svg>`;
        toggleSenhaButton.addEventListener('click', () => {
            const isPassword = senhaInput.type === 'password';
            senhaInput.type = isPassword ? 'text' : 'password';
            toggleSenhaButton.innerHTML = isPassword ? eyeIcon : eyeSlashIcon;
        });
    </script>
</body>
</html>

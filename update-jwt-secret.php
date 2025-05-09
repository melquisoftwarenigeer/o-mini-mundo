<?php

// Caminho para o arquivo .env.testing
$envFilePath = __DIR__ . '/.env.testing';

// Gerar um novo JWT_SECRET
$jwtSecret = bin2hex(random_bytes(32));

// Verificar se o arquivo .env.testing existe
if (file_exists($envFilePath)) {
    // Ler o conteúdo do arquivo .env.testing
    $envContent = file_get_contents($envFilePath);

    // Substituir o valor do JWT_SECRET no arquivo .env.testing
    $envContent = preg_replace('/^JWT_SECRET=.*$/m', "JWT_SECRET=$jwtSecret", $envContent);

    // Salvar o conteúdo atualizado no arquivo .env.testing
    file_put_contents($envFilePath, $envContent);

    echo "JWT_SECRET foi atualizado com sucesso no .env.testing.\n";
} else {
    echo "O arquivo .env.testing não foi encontrado.\n";
}

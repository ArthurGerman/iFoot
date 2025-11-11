<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Cadastro de Quadra</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px;background:#f7f7f7}
        form{background:#fff;padding:20px;border-radius:6px;max-width:480px;margin:0 auto;box-shadow:0 2px 8px rgba(0,0,0,.08)}
        label{display:block;margin-top:12px;font-weight:600}
        input,select,button{width:100%;padding:8px;margin-top:6px;border:1px solid #ccc;border-radius:4px}
        button{background:#2d89ef;color:#fff;border:0;margin-top:16px;cursor:pointer}
        small{color:#666}
    </style>
</head>
<body>
    <h2>Cadastro de Quadra</h2>

    <form action="cadastra_quadra_action.php" method="post">
        <label for="PRECO_HORA_QUAD">Preço por hora</label>
        <input id="PRECO_HORA_QUAD" name="PRECO_HORA_QUAD" type="text" placeholder="Ex: 120.00" required>

        <label for="ENDERECO_QUAD">Endereço</label>
        <input id="ENDERECO_QUAD" name="ENDERECO_QUAD" type="text" placeholder="Rua, número, bairro" required>

        <label for="CIDADE_QUAD">Cidade</label>
        <input id="CIDADE_QUAD" name="CIDADE_QUAD" type="text" placeholder="Cidade" required>

        <label for="ID_PROP">ID do Proprietário</label>
        <input id="ID_PROP" name="ID_PROP" type="number" min="1" placeholder="Ex: 1" required>

        <label for="ID_UF">ID da UF</label>
        <input id="ID_UF" name="ID_UF" type="number" min="1" placeholder="Ex: 25 (SP)" required>
        <small>Use o ID da tabela uf</small>

        <label for="ID_MODAL">ID da Modalidade</label>
        <input id="ID_MODAL" name="ID_MODAL" type="number" min="1" placeholder="Ex: 1" required>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
```// filepath: c:\Users\Notebook\Downloads\iFoot\src\php\cadastro\cadastra_quadra.php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Cadastro de Quadra</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px;background:#f7f7f7}
        form{background:#fff;padding:20px;border-radius:6px;max-width:480px;margin:0 auto;box-shadow:0 2px 8px rgba(0,0,0,.08)}
        label{display:block;margin-top:12px;font-weight:600}
        input,select,button{width:100%;padding:8px;margin-top:6px;border:1px solid #ccc;border-radius:4px}
        button{background:#2d89ef;color:#fff;border:0;margin-top:16px;cursor:pointer}
        small{color:#666}
    </style>
</head>
<body>
    <h2>Cadastro de Quadra</h2>

    <form action="cadastra_quadra_action.php" method="post">
        <label for="PRECO_HORA_QUAD">Preço por hora</label>
        <input id="PRECO_HORA_QUAD" name="PRECO_HORA_QUAD" type="text" placeholder="Ex: 120.00" required>

        <label for="ENDERECO_QUAD">Endereço</label>
        <input id="ENDERECO_QUAD" name="ENDERECO_QUAD" type="text" placeholder="Rua, número, bairro" required>

        <label for="CIDADE_QUAD">Cidade</label>
        <input id="CIDADE_QUAD" name="CIDADE_QUAD" type="text" placeholder="Cidade" required>

        <label for="ID_PROP">ID do Proprietário</label>
        <input id="ID_PROP" name="ID_PROP" type="number" min="1" placeholder="Ex: 1" required>

        <label for="ID_UF">ID da UF</label>
        <input id="ID_UF" name="ID_UF" type="number" min="1" placeholder="Ex: 25 (SP)" required>
        <small>Use o ID da tabela uf</small>

        <label for="ID_MODAL">ID da Modalidade</label>
        <input id="ID_MODAL" name="ID_MODAL" type="number" min="1" placeholder="Ex: 1" required>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
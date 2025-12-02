<?php

$senha_para_criar = "1234"; 


$hash = password_hash($senha_para_criar, PASSWORD_DEFAULT);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Hash</title>
    <style>
        body { font-family: sans-serif; padding: 20px; text-align: center; }
        .box { background: #f0f0f0; padding: 20px; border-radius: 8px; display: inline-block; }
        input { padding: 10px; width: 300px; text-align: center; font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="box">
        <h3>Copie o código abaixo e cole na coluna 'senha' do banco:</h3>
        <p>Senha original: <strong><?php echo $senha_para_criar; ?></strong></p>
        
        <input type="text" value="<?php echo $hash; ?>" readonly onclick="this.select();">
        
        <br><br>
        <small>Cada vez que atualizar a página, o código muda, mas todos funcionam para "1234".</small>
    </div>
</body>
</html>
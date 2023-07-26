<?php
    // Validação de dados
    $formulario['nome'] = isset($_POST['txtNome']) ? $_POST['txtNome'] : '';
    $formulario['qtdEstoque'] = isset($_POST['txtQtdEstoque']) ? $_POST['txtQtdEstoque'] : '';
    $formulario['preco'] = isset($_POST['txtPreco']) ? strtr($_POST['txtPreco'], ',', '.') : '';

    if (in_array('', $formulario)) {
        echo "<script> 
                alert('Existem campos vazios. Verifique!'); 
                window.location = '../sistema.php';
            </script>";
        exit;
    }

    // Se passou pela validação, continua a partir daqui

    // Enviar imagem para o servidor
    date_default_timezone_set('America/Sao_Paulo');
    $nomeImagem = 'foto_produto_' . date('Y-m-d_H-i-s') . '.jpg';
    $origemImagem = $_FILES['fileFotoProduto']['tmp_name'];
    move_uploaded_file($origemImagem, "uploads/$nomeImagem");
    
    // Conexão com Banco de Dados
    $strConnection = "mysql:host=localhost;dbname=db_produtos";
    $db_usuario = 'root';
    $db_senha = 'senai';
    $conexao = new PDO($strConnection, $db_usuario, $db_senha);

    // SQL
    $sql = "INSERT INTO produtos (
                nome, 
                qtd_estoque, 
                preco_venda,
                likes,
                dislikes,
                imagem)
            VALUES (
                :nome, 
                :qtd_estoque,
                :preco_venda, 
                0,
                0,
                :imagem
            )";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':nome', $formulario['nome']);
    $stmt->bindParam(':qtd_estoque', $formulario['qtdEstoque']);
    $stmt->bindParam(':preco_venda', $formulario['preco']);
    $stmt->bindParam(':imagem', $nomeImagem);
    
    // Execuntado o sql no bd
    if ($stmt->execute()) {
        $mensagem = "Dados cadastrados com sucesso!";
    } else {
        $mensagem = "Não foi possível cadastrar os dados!";
    }

    // Alerta js com o resultado
    echo "<script>
        alert('$mensagem');
        window.location = '../sistema.php';
    </script>";
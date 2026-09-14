<?php

include 'conexao.php';

$mensagem = "";
$tipo_mensagem = "";

if (isset($_POST["cadastrar_produto"])) 
{
    $descricao = $_POST["campo_descricao"];
    $categoria = $_POST["campo_categoria"];
    $valor_compra = $_POST["campo_valor_compra"];
    $valor_venda = $_POST["campo_valor_venda"];
    $quantidade = $_POST["campo_quantidade"];

    if ($quantidade > 0)
    {
        $sql_cadastra_produto = mysql_query(
            "INSERT INTO produtos 
            (descricao, categoria, valor_compra, valor_venda, quantidade) 
            VALUES 
            ('$descricao', '$categoria', '$valor_compra', '$valor_venda', '$quantidade')"
        );

        $mensagem = "Produto cadastrado com sucesso!";
        $tipo_mensagem = "sucesso";
    }
    else
    {
        $mensagem = "Por favor, digite uma quantidade válida para o produto!";
        $tipo_mensagem = "erro";
    }
}

if (isset($_POST["vender_produto"]))
{
    $descricao = $_POST["campo_descricao"];
    $quantidade = $_POST["campo_quantidade"];

    $sql_consulta_produto = mysql_query(
        "SELECT * FROM produtos 
        WHERE descricao = '$descricao'"
    );

    $produto = mysql_fetch_assoc($sql_consulta_produto);

    if ($produto)
    {
        if ($quantidade > 0)
        {
            if ($produto["quantidade"] >= $quantidade)
            {
                $sql_baixa_estoque = mysql_query(
                    "UPDATE produtos 
                    SET quantidade = quantidade - $quantidade 
                    WHERE descricao = '$descricao'"
                );

                $mensagem = "Venda realizada com sucesso!";
                $tipo_mensagem = "sucesso";
            }
            else
            {
                $mensagem = "Quantidade indisponível no estoque!";
                $tipo_mensagem = "erro";
            }
        }
        else
        {
            $mensagem = "Por favor, digite a quantidade vendida!";
            $tipo_mensagem = "erro";
        }
    }
    else
    {
        $mensagem = "Produto não encontrado!";
        $tipo_mensagem = "erro";
    }
}

if (isset($_POST["buscar_produto"]))
{
    $busca = $_POST["campo_busca"];

    $produtos = mysql_query(
        "SELECT * FROM produtos 
        WHERE descricao LIKE '%$busca%' 
        OR categoria LIKE '%$busca%' 
        ORDER BY descricao"
    );
}
else
{
    $produtos = mysql_query(
        "SELECT * FROM produtos 
        ORDER BY descricao"
    );
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Produto</title>

    <link rel="stylesheet" href="produto.css">

</head>


<body>

<?php if ($mensagem != "") { ?>

    <div class="fundo-mensagem">

        <div class="caixa-mensagem <?php echo $tipo_mensagem; ?>">

            <div class="icone">

                <?php

                if ($tipo_mensagem == "sucesso")
                {
                    echo "✓";
                }
                else
                {
                    echo "!";
                }

                ?>

            </div>


            <h3>

                <?php

                if ($tipo_mensagem == "sucesso")
                {
                    echo "Sucesso!";
                }
                else
                {
                    echo "Atenção!";
                }

                ?>

            </h3>


            <p>
                <?php echo $mensagem; ?>
            </p>


            <button type="button" onclick="fecharMensagem()">
                OK
            </button>

        </div>

    </div>

<?php } ?>

<div class="titulo">

    <h1>Cadastro de Produto</h1>

</div>

<form method="POST" action="" id="formulario-produto">


    <div class="formulario">


        <div class="campo">

            <label>Descrição</label>

            <input
                type="text"
                name="campo_descricao"
            >

        </div>


        <div class="campo">

            <label>Categoria</label>

            <input
                type="text"
                name="campo_categoria"
            >

        </div>


        <div class="campo">

            <label>Valor Compra (R$)</label>

            <input
                type="number"
                step="0.01"
                name="campo_valor_compra"
            >

        </div>


        <div class="campo">

            <label>Valor Venda (R$)</label>

            <input
                type="number"
                step="0.01"
                name="campo_valor_venda"
            >

        </div>


        <div class="campo quantidade">

            <label>Quantidade Estoque</label>

            <input
                type="number"
                name="campo_quantidade"
            >

        </div>


    </div>

    <div class="botoes">

        <button
            type="submit"
            name="cadastrar_produto"
            class="botao-cadastrar"
        >
            Cadastrar Produto
        </button>


        <button
            type="submit"
            name="vender_produto"
            class="botao-vender"
        >
            Vender Produto
        </button>

    </div>


</form>

<div class="cabecalho-inventario">


    <h2>Inventário</h2>

    <div class="pesquisa">

        <input
            type="text"
            name="campo_busca"
            form="formulario-produto"
            placeholder="Pesquisar por descrição ou categoria"
        >


        <button
            type="submit"
            name="buscar_produto"
            form="formulario-produto"
        >
            Pesquisar
        </button>

    </div>


</div>

<table>


    <thead>

        <tr>

            <th>ID</th>

            <th>Descrição</th>

            <th>Categoria</th>

            <th>Valor Compra</th>

            <th>Valor Venda</th>

            <th>Estoque</th>

        </tr>

    </thead>


    <tbody>


<?php

while ($produto = mysql_fetch_assoc($produtos))
{

?>

        <tr>

            <td>
                <?php echo $produto["id_produto"]; ?>
            </td>


            <td>
                <?php echo $produto["descricao"]; ?>
            </td>


            <td>
                <?php echo $produto["categoria"]; ?>
            </td>


            <td>
                R$ <?php echo $produto["valor_compra"]; ?>
            </td>


            <td>
                R$ <?php echo $produto["valor_venda"]; ?>
            </td>


            <td>
                <?php echo $produto["quantidade"]; ?>
            </td>

        </tr>


<?php

}

?>


    </tbody>


</table>

<script>

function fecharMensagem()
{
    document.querySelector(".fundo-mensagem").style.display = "none";
}

</script>


</body>

</html>
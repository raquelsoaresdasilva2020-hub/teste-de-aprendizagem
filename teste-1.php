
<?php
session_start();

$nome = "";
$email = "";
$tel = "";
$data_nascimento = "";
$cpf = "";
$endereco = "";

$salario_mensal = 0.0;
$total_gastos = 0.0;
$saldo = 0.0;
$mostrar_calculo = false;

if (($_SERVER["REQUEST_METHOD"] ?? "") === "POST") {
    $acao = $_POST["acao"] ?? "";

    if ($acao === "cadastro") {
        $nome = trim($_POST["nome"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $tel = trim($_POST["telefone"] ?? "");
        $data_nascimento = trim($_POST["data_nascimento"] ?? "");
        $cpf = trim($_POST["cpf"] ?? "");
        $endereco = trim($_POST["endereco"] ?? "");

        if (
            $nome === "" ||
            $email === "" ||
            $tel === "" ||
            $data_nascimento === "" ||
            $cpf === "" ||
            $endereco === ""
        ) {
            echo "<p>Todos os campos do cadastro devem ser preenchidos.</p>";
        } else {
            $_SESSION["cadastro"] = [
                "nome" => $nome,
                "email" => $email,
                "telefone" => $tel,
                "data_nascimento" => $data_nascimento,
                "cpf" => $cpf,
                "endereco" => $endereco,
            ];
            echo "<p>Cadastro salvo com sucesso!</p>";
        }
    }

    if ($acao === "calculo") {
        $salario_mensal = (float) ($_POST["salario_mensal"] ?? 0);
        $total_gastos = 0.0;
        $semanas = [];

        for ($semana = 1; $semana <= 4; $semana++) {
            $valor_semana = (float) ($_POST["semana_{$semana}"] ?? 0);
            $semanas[$semana] = $valor_semana;
            $total_gastos += $valor_semana;
        }

        $saldo = $salario_mensal - $total_gastos;

        $_SESSION["calculo"] = [
            "salario_mensal" => $salario_mensal,
            "semanas" => $semanas,
            "total_gastos" => $total_gastos,
            "saldo" => $saldo,
        ];

        $mostrar_calculo = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste 1 - Cadastro e Cálculo</title>
</head>
<body>
    <h1>Formulário de Cadastro</h1>
    <h3>Preencha os campos abaixo:</h3>

    <form method="post" action="teste-1.php">
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Nome" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>

        <div>
            <label for="telefone">Telefone:</label>
            <input type="tel" id="telefone" name="telefone" placeholder="Telefone" required>
        </div>

        <div>
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" placeholder="CPF" required>
        </div>

        <div>
            <label for="data_nascimento">Data de nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required>
        </div>

        <div>
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" placeholder="Endereço" required>
        </div>

        <button type="submit" name="acao" value="cadastro">Salvar Cadastro</button>
    </form>

    <hr>

    <h3>Cálculo Mensal</h3>
    <h4>Gastos por semana:</h4>

    <form method="post" action="teste-1.php">
        <div>
            <label for="salario_mensal">Salário Mensal:</label>
            <input type="number" step="0.01" id="salario_mensal" name="salario_mensal" placeholder="Salário Mensal" required>
        </div>

        <?php
        for ($semana = 1; $semana <= 4; $semana++) {
            echo "<div>";
            echo "<label for='semana_{$semana}'>Semana {$semana}:</label>";
            echo "<input type='number' step='0.01' id='semana_{$semana}' name='semana_{$semana}' placeholder='Gasto da semana {$semana}' required>";
            echo "</div>";
        }
        ?>

        <button type="submit" name="acao" value="calculo">Calcular</button>
    </form>

    <?php if ($mostrar_calculo): ?>
        <div>
            <p><strong>Salário Mensal:</strong> R$ <?php echo number_format($salario_mensal, 2, ",", "."); ?></p>
            <p><strong>Total de Gastos:</strong> R$ <?php echo number_format($total_gastos, 2, ",", "."); ?></p>
            <p><strong>Saldo (Salário - Gastos):</strong> R$ <?php echo number_format($saldo, 2, ",", "."); ?></p>
        </div>
    <?php endif; ?>
</body>
</html>
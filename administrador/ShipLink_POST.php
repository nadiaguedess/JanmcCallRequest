<?php
if (session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}
if (!isset($_SESSION['LOGIN']) && $_SESSION['donoDaSessao'] != "wavequantum.uk" && $_SESSION['donoDaSessao'] != "tester.wavequantum.uk" && $_SESSION['donoDaSessao'] != "localhost") { //validou sessão (deve conter em todas telas no início do código, logo após o start)
    echo "<script>alert('Ended Session!');</script>";
    echo "<script>location.href='login.php';</script>";
    exit;
} // iniciou sessão (deve ser usado em TODAS as telas sempre na primeira linha do php) // iniciou sessão (deve ser usado em TODAS as telas sempre na primeira linha do php)
?>

<?php
    require_once "../banco/ConexaoVFBCompany.php";
    $var = new Mysql();
    $connect = $var->dbConnect();

    if (!isset($_POST['idtxtShipLink'])) {
        $message[0] = "Atenção";
        $message[1] = "Código de rastreio não preenchido.";
        $message[2] = "warning";
        echo json_encode($message);
        
        exit;
    }

    $idtxtShipLink = $_POST['idtxtShipLink'];
    $idtxtCarrinho = $_POST['idtxtCarrinho'];

    $updateCorreio = $var->freeRun("
    update
        carrinhoshopping
    set
        shiplink='$idtxtShipLink'
    where
        idcarrinhoshopping = $idtxtCarrinho    
    ");

    if ($updateCorreio) {
        $message[0] = "Success";
        $message[1] = "Código lançado com sucesso.";
        $message[2] = "success";
        echo json_encode($message);
        
        exit;
    } else {
        $message[0] = "Attention";
        $message[1] = "Não foi possivel lançar o código.";
        $message[2] = "danger";
        echo json_encode($message);
        
        exit;
    }

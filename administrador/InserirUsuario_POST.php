<?php
require_once("../banco/ConexaoVFBCompany.php");
    //PEGANDO VALORES DA TELA

    $idtxtLogin = $_POST['idtxtLogin'];
    $idtxtNome = $_POST['idtxtNome'];
    $idtxtemail = $_POST['idtxtemail'];
    $idtxtTelefone = $_POST['idtxtTelefone'];
    $idtxtCidade = $_POST['idtxtCidade'];
    $idtxtTipoUsu = $_POST['idtxtTipoUsu'];
    $idtxtSenha = $_POST['idtxtSenha'];

    $var = new Mysql();
    $connect = $var->dbConnect();

    $result = $var->freeRun("CALL PROC_INSERT_BEGINNIG_USER(
		'".$idtxtNome."',         
		'".$idtxtLogin."', 
		'".$idtxtemail."', 
		'".$idtxtTelefone."',
		'".$idtxtCidade."',
		'',
		$idtxtTipoUsu,
		'".sha1($idtxtSenha)."'
		)");

    if ($result == true) {
        header("Location: DashBoard.php?op=InserirUsuario&tipo=sucess&bold=Sucesso&message=Usuario ".$idtxtLogin." inserido com sucesso!");
        exit;
    } else {
        header("Location: DashBoard.php?op=InserirUsuario&tipo=danger&bold=Atenção&message=Não foi possível inserir o usuário ".$idtxtLogin."!");
        exit;
    }

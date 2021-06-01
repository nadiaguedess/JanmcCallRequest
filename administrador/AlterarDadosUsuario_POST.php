<?php
require_once("../banco/ConexaoVFBCompany.php");
	//PEGANDO VALORES DA TELA

	$idtxtNome = $_POST['idtxtNome'];

	$idtxtemail  =$_POST['idtxtemail'];

	$idtxtTelefone  =$_POST['idtxtTelefone'];
	$idtxtUsuario = $_POST['idtxtUsuario'];

	
	$var = new Mysql();
	$connect = $var->dbConnect();
	
	if(empty($idtxtUsuario)){
		header("Location: DashBoard.php?op=AlterarDadosUsuario&tipo=danger&bold=SELECIONE O USUÁRIO&message=Selecione o usuário clicando em 'Alterar'!");
		exit();
	}
	
	if(empty($idtxtNome)){
		header("Location: DashBoard.php?op=AlterarDadosUsuario&tipo=danger&bold=Campos Obrigatórios&message=O nome do usuário não pode estar em branco!");
		exit();
	}

	if(empty($idtxtTelefone)){
		$idtxtTelefone ="";
	}

		$result = $var->freeRun("UPDATE
									usuario
								SET
									nome = '".$idtxtNome."',
									telefone_um = '".$idtxtTelefone."',
									email = '$idtxtemail'
								WHERE
									idUSUARIO = ".$idtxtUsuario.";");

	
	if ($result == true) {
		header("Location: DashBoard.php?op=AlterarDadosUsuario&tipo=sucess&bold=SUCESSO&message=Dados alterados com sucesso!");
		
	} else{
		header("Location: DashBoard.php?op=AlterarDadosUsuario&tipo=danger&bold=ATENÇÃO&message=Não foi possível alterar os dados!");
	}
			
?>
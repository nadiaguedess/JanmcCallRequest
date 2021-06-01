<?php
require_once("../banco/ConexaoVFBCompany.php");
	//PEGANDO VALORES DA TELA
	
	$idtxtSenhaAcesso = sha1($_POST['idtxtSenhaAcesso']); //"0"; //isset($_GET['tipo']);
	$idtxtSenhaFinanceira = sha1($_POST['idtxtSenhaFinanceira']);
	$idUsuario  =$_POST['idtxtUsuario'];
	
	$var = new Mysql();
	$connect = $var->dbConnect();
	
	if(empty($idUsuario)){
		header("Location: DashBoard.php?op=TrocarSenhaUsuario&tipo=danger&bold=SELECIONE O USUÁRIO&message=Selecione o usuário clicando em 'Alterar Senha'!");
		exit();
	}else if(empty($idtxtSenhaAcesso) && empty($idtxtSenhaFinanceira)){
		header("Location: DashBoard.php?op=TrocarSenhaUsuario&tipo=danger&bold=Campos Obrigatórios&message=Digite uma das senhas para alterar!");
		exit();
	}

	$buscarSenhaAcesso = $var->freeRun("SELECT
									*
								FROM
									senha 
								WHERE
									TIPO = 1 and
									USUARIO_idUSUARIO = ".$idUsuario.";");
	$buscarSenhaAcesso_count = mysqli_num_rows($buscarSenhaAcesso);
	
	if(!empty($idtxtSenhaAcesso) && $buscarSenhaAcesso_count > 0){
		$result = $var->freeRun("UPDATE
									senha
								SET
									senha = '".$idtxtSenhaAcesso."'
								WHERE
									TIPO = 1 and
									USUARIO_idUSUARIO = ".$idUsuario.";");
	}
	

	
	
	if ($result == true) {
		//header("Location: DashBoard.php?op=IndicadosPendentes?tipo=sucess&bold=SUCESSO&message=Lado alterado do indicado!");
		header("Location: DashBoard.php?op=TrocarSenhaUsuario&tipo=sucess&bold=SUCESSO&message=Senha alterada com sucesso!");
		
	} else{
		header("Location: DashBoard.php?op=TrocarSenhaUsuario&tipo=danger&bold=ATENÇÃO&message=Não foi possível alterar a senha!");
	}
			
?>
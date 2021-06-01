<?php
require_once("../banco/ConexaoVFBCompany.php");
	//PEGANDO VALORES DA TELA
	
	$idtxticket = $_POST['idtxticket']; //"0"; //isset($_GET['tipo']);
	
	$var = new Mysql();
	$connect = $var->dbConnect();
	
	$suportResolvido = $var->freeRun("update
                                        suporte
                                   set
	                                    atendido = 1,
	                                    resolvido = 1
                                   where
                                        idmensagem=".$idtxticket.";");
	
	if ($suportResolvido == true) {
		header("Location: DashBoard.php?op=Suporte&tipo=sucess&bold=SUCESSO&message=Resolvido com sucesso!");
		
	} else{
		header("Location: DashBoard.php?op=Suporte&tipo=danger&bold=ATENÇÃO&message=Não foi possível resolver senha!");
	}
			
?>
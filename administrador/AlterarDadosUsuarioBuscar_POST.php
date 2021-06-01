<?php
require_once("../banco/ConexaoVFBCompany.php");
	//PEGANDO VALORES DA TELA

	$idtxtLoginBuscar = $_POST['idtxtLoginBuscar'];
	$idtxtNomeBuscar  =$_POST['idtxtNomeBuscar'];
	
	$var = new Mysql();
	$connect = $var->dbConnect();
	
	if(!empty($idtxtLoginBuscar) && !empty($idtxtNomeBuscar)){
	    header("Location: DashBoard.php?op=AlterarDadosUsuario&idtxtLoginBuscar=$idtxtLoginBuscar&idtxtNomeBuscar=$idtxtNomeBuscar");
		exit();
	}else if(!empty($idtxtLoginBuscar)){
	    header("Location: DashBoard.php?op=AlterarDadosUsuario&idtxtLoginBuscar=$idtxtLoginBuscar");
		exit();
	} else if(!empty($idtxtNomeBuscar)){
	    header("Location: DashBoard.php?op=AlterarDadosUsuario&idtxtNomeBuscar=$idtxtNomeBuscar");
		exit();
	}else{
	    header("Location: DashBoard.php?op=AlterarDadosUsuario");
		exit();
	}

?>
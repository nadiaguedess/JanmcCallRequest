<?php
if (!isset($_SESSION)) {
	session_start();
}
if (!isset($_SESSION['LOGIN'])) //validou sessão (deve conter em todas telas no início do código, logo após o start)
{
	echo "<script>alert('Favor fazer login!');</script>";
	echo "<script>location.href='index.php';</script>";
}
include "../banco/ConexaoVFBCompany.php";

$var = new Mysql();
$connect = $var->dbConnect();

$idtxtmensagem = $_POST['idtxtmensagem'];

if($idtxtmensagem != null){
    
    $selecionarUsuarios = $var->freeRun("select idusuario from usuario where idusuario > 0");
    $row_cnt = mysqli_num_rows($selecionarUsuarios);
    
    for($x = 0; $x < $row_cnt; $x++) {
         $linha = mysqli_fetch_assoc($selecionarUsuarios);
      
         $registrarRecebimento = $var->freeRun(
            						"insert into 
            						    mensagem (mensagem, usuario_idusuario)
                                    select
                                        '$idtxtmensagem',
                                        idusuario
                                    from 
                                        usuario
                                    where
                                        idusuario = ".$linha['idusuario']." ");	
    }
    
   
   header("Location: DashBoard.php?op=InserirMensagem&tipo=sucess&bold=Sucesso&message=Mensagem enviada aos doadores com sucesso!");
   exit();
}else{
   header("Location: DashBoard.php?op=InserirMensagem&tipo=danger&bold=ATENÇÃO&message=Não foi possível enviar a mensagem!");
   exit();
}

            				      



?>
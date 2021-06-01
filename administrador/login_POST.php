<?php
session_cache_expire(10);
session_start();
include "../banco/ConexaoVFBCompany.php";

$idtxtUsername = $_POST['idtxtUsername'];
$idtxtSenha = sha1($_POST['idtxtSenha']);

$var = new Mysql();
$connect = $var->dbConnect();
$result = $var->freeRun(
    "
		SELECT 
			LOGIN, 
			NOME,
			EMAIL,
			USU.IDUSUARIO AS ID,
			USU.TIPOUSUARIO_idTIPOUSUARIO
		FROM 
			senha SEN
			
			INNER JOIN usuario USU ON 
				USU.idUSUARIO = SEN.USUARIO_idUSUARIO 
		WHERE
			SENHA = '".$idtxtSenha."'
			AND (LOGIN = '".$idtxtUsername."' OR EMAIL = '".$idtxtUsername."')
			AND TIPOUSUARIO_idTIPOUSUARIO in (1,2,3);"
);

/* determine number of rows result set */
$row_cnt = mysqli_num_rows($result);

$dados_usuario = mysqli_fetch_row($result);
if ($row_cnt == 0) {
    header("Location: index.php?tipo=danger&bold=ATENÇÃO&message=Username ou senha incorretos!");
} else {
    $_SESSION['IP'] = getUserIpAddr();
    $_SESSION['EMAIL'] = $idtxtUsername; // adicionou variável na sessão (logo após fazer login)
    $_SESSION['LOGIN'] = $dados_usuario[0];
    $_SESSION['ID'] = $dados_usuario[3];
    $_SESSION['TIPUSU'] = $dados_usuario[4];
    $notification =  $var->freeRun("INSERT INTO `notification`(`message`, `usuario_idusuario`, ip) VALUES ('Login no Admin',".$_SESSION['ID'].", '".$_SESSION['IP']."')");
    header("Location: DashBoard.php");
}
?>
<?php
function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

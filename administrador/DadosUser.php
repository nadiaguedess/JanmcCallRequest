<?php
if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}
if (!isset($_SESSION['LOGIN']) && !isset($_GET['CODE']) && $_SESSION['LOGIN'] <> "masterforexpro") //validou sessão (deve conter em todas telas no início do código, logo após o start)
{
  echo "<script>alert('Favor fazer login!');</script>";
  echo "<script>location.href='https://bitmoney.app/administrador/';</script>";
} // iniciou sessão (deve ser usado em TODAS as telas sempre na primeira linha do php)
?>

<?php



require_once '../classe/Alerta.php';
require_once "../banco/ConexaoVFBCompany.php";

$var     = new Mysql();
$connect = $var->dbConnect();

if(isset($_SESSION['LOGIN']))
{

if(isset($_GET['idtxtLoginBuscar']) && isset($_GET['idtxtNomeBuscar'])){
    $condicao = "and (usu.login like '%".$_GET['idtxtLoginBuscar']."%' or usuUP.LOGIN like '%".$_GET['idtxtLoginBuscar']."%') and usu.nome like '%".$_GET['idtxtNomeBuscar']."%' ";
}
else if(isset($_GET['idtxtLoginBuscar'])){
   $condicao = "and (usu.login like '%".$_GET['idtxtLoginBuscar']."%' or usuUP.LOGIN like '%".$_GET['idtxtLoginBuscar']."%') "; 
}
else if(isset($_GET['idtxtNomeBuscar'])){
    $condicao = "and usu.nome like '%".$_GET['idtxtNomeBuscar']."%' ";
}
else{
    $condicao = "";
}

if(!empty($_GET['idtxtAConfirmar'])){
    $condicao = $condicao." and up.DATAATIVACAO is null ";
}
if(!empty($_GET['idtxtConfirmado'])){
    $condicao = $condicao." and up.DATAATIVACAO is not null ";
}



$result  = $var->freeRun("SELECT
                            usu.idusuario,
                        	usu.nome,
                        	usu.EMAIL,
                        	usu.login,	
                        	up.VALORUSD AS VALOR,
                        	DATE_FORMAT(up.DATAUPGRADE, '%d/%m/%Y %H:%i') AS DATAUPGRADE,
                        	DATE_FORMAT(up.DATAATIVACAO, '%d/%m/%Y %H:%i') AS DATAATIVACAO,
                        	up.idupgrade,
                        	usuUP.LOGIN LOGINUP
                        FROM 
                        	upgrade up
                        	
                        	INNER JOIN pacote pct on 
                        		pct.idPACOTE = up.PACOTE_idPACOTE
                        		
                        	INNER JOIN usuario usu on 
                        		usu.idUSUARIO = up.USUARIO_idUSUARIO
                        		
                        	left join usuario usuUP on 
                        	  usuUP.idusuario =  usu.UPLINE
                        where
                        	DATAUPGRADE is not null
                            $condicao	
                        order by 
                        	up.dataativacao asc
                        limit 
                        	100");

/* determine number of rows result set */
$row_cnt = mysqli_num_rows($result);

}

?>



      <div class="col-lg-12">
         <?php
if (isset($_GET['tipo']) && isset($_GET['bold']) && isset($_GET['message'])) {
    $tipo    = $_GET['tipo'];
    $bold    = $_GET['bold'];
    $message = $_GET['message'];
    if ($tipo == "warning") {
        warningAlert($bold, $message);
    } elseif ($tipo == "danger") {
        dangerAlert($bold, $message);
    } elseif ($tipo == "sucess") {
        successAlert($bold, $message);
    } elseif ($tipo == "info") {
        infoAlert($bold, $message);
    }
}
?>
     </div>

	<div class="card" >
         <div class="card-header" >
            <i class="fa fa-table"></i> Pacotes Pendentes de Ativação
         </div>
         <div class="card-body">
                 <form method="POST" action="DoacoesBuscar_POST.php">
		        	<div class="row">
			    	<div class="form-group col-lg-4">
						<label for="exampleInputEmail1">Login</label>
						<input class="form-control" id="idtxtLoginBuscar" name="idtxtLoginBuscar" type="text">
					</div>
					<div class="form-group col-lg-6">
					    <label for="exampleInputEmail1">Nome</label>
					    <input class="form-control" id="idtxtNomeBuscar" name="idtxtNomeBuscar" type="text">
					</div>
					<div class="form-group col-lg-2">
					    <label for="exampleInputEmail1" style="color:transparent;">a</label>
					    <button type="submit" class="btn btn-primary btn-block" >Buscar</button>
					</div>
					</div>
					<div class="row">
					    <div class="form-group col-lg-2">
					        <input type="checkbox" id="idtxtAConfirmar" name="idtxtAConfirmar" checked> A Confirmar
					        
					    </div>
					    <div class="form-group col-lg-2">
					       
					        <input type="checkbox" id="idtxtConfirmado" name="idtxtConfirmado"> Confirmado
					    </div>
					</div>   
				</form>
             <div class="row align-items-center mb-3">
            <div class="col-lg-auto d-flex align-items-center">
              <span class="text-muted mr-2">Show:</span>

              <select id="datatableEntries" class="js-custom-select"
                      data-classes="custom-select-without-bordered">
                <option value="10" selected>10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
              </select>
            </div>

            <div class="col-lg-auto ml-md-auto">
              <div class="input-group input-group-merge">
                <input id="datatableSearch" class="form-control bg-lighter border-0 pr-6" placeholder="Enter search term" type="text">
                <div class="input-group-append-merge">
                  <a class="text-muted" href="#!">
                    <i class="nova-search"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
            <div class="table-responsive datatable">
                <table class="js-datatable-export table text-nowrap mb-0"
                   data-dt-info="#datatableWithExport"
                   data-dt-search="#datatableSearch"
                   data-dt-entries="#datatableEntries"
                   data-dt-is-show-paging="true"
                   data-dt-pagination="datatableExport"
                   data-dt-page-length="10"
                   data-dt-is-responsive="false"
                   data-dt-pagination-classes="pagination justify-content-end font-weight-semi-bold mb-0"
                   data-dt-pagination-items-classes="page-item d-none d-md-block"
                   data-dt-pagination-links-classes="page-link"
                   data-dt-pagination-next-classes="page-item"
                   data-dt-pagination-next-link-classes="page-link"
                   data-dt-pagination-next-link-markup='<i class="nova-angle-right icon-text icon-text-xs d-inline-block"></i>'
                   data-dt-pagination-prev-classes="page-item"
                   data-dt-pagination-prev-link-classes="page-link"
                   data-dt-pagination-prev-link-markup='<i class="nova-angle-left icon-text icon-text-xs d-inline-block"></i>'>
                  <thead>
                     <tr>
                         <th>COMPRA <i class="nova-info" data-toggle="tooltip" data-placement="top" title="Data da geração da fatura do pacote."></i></th>
                             <th>TIPO</th>
                             
                        <th>COMPRA <i class="nova-info" data-toggle="tooltip" data-placement="top" title="Data da geração da fatura do pacote"></i></th>
                        <th>ID FATURA</th>
                        <th>LOGIN</th>
                        <th>UPLINE</th>
                        <th>VALOR</th>
                		<th>E-MAIL</th>
                	
                		<th>ATIVAÇÃO <i class="nova-info" data-toggle="tooltip" data-placement="top" title="Data da ativação do pacote."></i></th>
                     </tr>
                  </thead>
                  <tfoot>
                     <tr>
                         <th>COMPRA <i class="nova-info" data-toggle="tooltip" data-placement="top" title="Data da geração da fatura do pacote."></i></th>
                         <th>TIPO</th>
                             
                        <th>COMPRA <i class="nova-info" data-toggle="tooltip" data-placement="top" title="Data da geração da fatura do pacote"></i></th>
                        <th>ID FATURA</th>
                        <th>LOGIN</th>
                        <th>UPLINE</th>
                        <th>VALOR</th>
                		<th>E-MAIL</th>
                	
                		<th>ATIVAÇÃO <i class="nova-info" data-toggle="tooltip" data-placement="top" title="Data da ativação do pacote."></i></th>
                     </tr>
                  </tfoot>
                  <tbody>
                     	<?php

			for($x = 0; $x < $row_cnt; $x++) /*estrutura de repeticao FOR*/
			{
			$linha = mysqli_fetch_assoc($result);
				
			?>	
				<tr>
				<form method="POST" action="DadosUser_POST.php">
				  <input type="hidden" name="idtxtIDUsuario" id="idtxtIDUsuario" value="<?php    echo $linha['idusuario'];?>">
				  <input type="hidden" name="idtxtUpgrade" id="idtxtUpgrade" value="<?php    echo $linha['idupgrade'];?>">
				  <td><?php
				  
				  if($linha['DATAATIVACAO'] == null){
				      echo '<button type="submit" class="btn btn-primary btn-block btn-sm" id="btnAtivar" name="btnAtivar" value="'.$linha['idupgrade'].'">ATIVAR</button>';
				  }else {
				     echo  "ATIVO";
				  }
				  
				  ?></td>
                  <td>
                      
                 <div class="form-group">
                    <select class="form-control form-control-sm" id="idtxtTipoUsuario<?php echo $linha['idupgrade'];?>" name="idtxtTipoUsuario<?php echo $linha['idupgrade'];?>" >
                        <option value="1" selected>1 -GERA BONUS</option> 
                        <option value="2" >2 -NAO GERA BONUS</option> 
                    </select>
                 </div>
                  </td>
                    
                    <td><?php echo $linha['DATAUPGRADE'];  ?></td>	
                    <td><?php echo $linha['idupgrade'];  ?></td>
                    
					<td><?php echo $linha['login'];  ?></td>	
					<td><?php echo $linha['LOGINUP'];  ?></td>
					<td>$ <?php echo $linha['VALOR'];  ?></td>
					<td style="font-size:12px;"><?php echo $linha['EMAIL'];  ?></td>
					
					<td><?php 
					
				        if($linha['DATAATIVACAO'] == null){
				            echo "-";
				        }else{
				            echo $linha['DATAATIVACAO'];      
				        }
					        
					    ?>
					</td>
					
					
				
					
				</form>	
				</tr>
				
		<?php
		}
		?>
                  </tbody>
               </table>
            </div>
         </div>
          <div class="card-footer d-block d-md-flex align-items-center">
          <div id="datatableInfo" class="d-flex mb-2 mb-md-0"></div>
          <nav id="datatablePagination" class="d-flex ml-md-auto d-print-none" aria-label="Pagination"></nav>
        </div>
      </div>
	</br>
	


   
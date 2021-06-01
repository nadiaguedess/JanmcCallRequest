<?php
if (!isset($_SESSION['LOGIN'])) { //validou sessão (deve conter em todas telas no início do código, logo após o start)
    echo "<script>alert('Favor fazer login!');</script>";
    echo "<script>location.href='login.php';</script>";
    exit;
} // iniciou sessão (deve ser usado em TODAS as telas sempre na primeira linha do php)
?>
<?php
include '../classe/Alerta.php';
require_once("../banco/ConexaoVFBCompany.php");

$var = new Mysql();
$connect = $var->dbConnect();

//Buscar Status
$buscarStatusChamado = $var->freeRun("select * from status where idstatus not in (1,2)");

//Buscar Cliente
$buscarCliente = $var->freeRun("select * from usuario where TIPOUSUARIO_idTIPOUSUARIO in (3)");

//Buscar Nivel Criticidade
$buscarCriticidade = $var->freeRun("select * from nivelcriticidade where status_idstatus = 2");

//Buscar Analista
$buscarAnalista= $var->freeRun("select * from usuario where TIPOUSUARIO_idTIPOUSUARIO in (2)");

?>

<div class="container">
  <div class="card-body">
    <div class="col-lg-12">
      <?php
        if (isset($_GET['tipo']) && isset($_GET['bold']) && isset($_GET['message'])) {
            $tipo = $_GET['tipo'];
            $bold = $_GET['bold'];
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
    <form method="POST" action="NovoChamado_POST.php">
      <div class="row">
        <div class="form-group col-lg-3">
          <input class="form-control" id="idtxtIdChamadoHidden" name="idtxtIdChamadoHidden" type="hidden"
            value="<?php echo(isset($_GET['idtxtIdChamadoHidden']) ? $_GET['idtxtIdChamadoHidden']: "")?>">

          <label for="exampleInputEmail1">ID</label>
          <input class="form-control" id="idtxtIdChamado" name="idtxtIdChamado" type="text" placeholder=""
            value="<?php echo(isset($_GET['idtxtIdChamado']) ? $_GET['idtxtIdChamado']: "")?>"
            readonly>
        </div>
        <div class="form-group col-lg-6">
          <label for="exampleInputEmail1">Assunto</label>
          <input class="form-control" id="idtxtAssunto" name="idtxtAssunto" type="text"
            value="<?php echo(isset($_GET['idtxtAssunto']) ? $_GET['idtxtAssunto']: "")?>"
            required>
        </div>
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Status:</label>
          <select class="custom-select my-1 mr-sm-2" id="idtxtStatus" name="idtxtStatus" required>
            <?php
          
          for ($i=0; $i < mysqli_num_rows($buscarStatusChamado) ; $i++) {
              $linhaStatus = mysqli_fetch_assoc($buscarStatusChamado); ?>
            <option
              value="<?php echo $linhaStatus['idSTATUS']; ?>">
              <?php echo $linhaStatus['DESCRICAO']; ?>
            </option>

            <?php
          }
          ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-lg-12">
          <label for="exampleInputEmail1">Descrição</label>
          <textarea class="form-control" id="idtxtDesc" rows="4" cols="50" name="idtxtDesc" type="date"
            value="<?php echo(isset($_GET['idtxtDesc']) ? $_GET['idtxtDesc']: "")?>"
            required></textarea>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Usuário Afetado</label>
          <select class="custom-select my-1 mr-sm-2" id="idtxtUsuAfe" name="idtxtUsuAfe" required>
            <?php
          
          for ($i=0; $i < mysqli_num_rows($buscarCliente) ; $i++) {
              $linhabuscarCliente = mysqli_fetch_assoc($buscarCliente); ?>
            <option
              value="<?php echo $linhabuscarCliente['idUsuario']; ?>">
              <?php echo $linhabuscarCliente['NOME']; ?>
            </option>

            <?php
          }
          ?>
          </select>
        </div>
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Início da Ocorrência</label>
          <input class="form-control" id="idtxtDataInicio" name="idtxtDataInicio" type="datetime-local"
            value="<?php echo(isset($_GET['idtxtDataInicio']) ?$_GET['idtxtDataInicio'] :  date('Y-m-d\TH:i')); ?>"
            required>
        </div>
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Conclusão</label>
          <input class="form-control" id="idtxtConclusao" name="idtxtConclusao" type="date"
            value="<?php echo(isset($_GET['idtxtConclusao']) ?$_GET['idtxtConclusao'] : "")?>">
        </div>
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Cancelamento</label>
          <input class="form-control" id="idtxtDataCancelamento" name="idtxtDataCancelamento" type="date"
            value="<?php echo(isset($_GET['idtxtDataCancelamento']) ?$_GET['idtxtDataCancelamento'] : "")?>">
        </div>
      </div>

      <div class="row">
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Nível de Criticidade</label>
          <select class="custom-select my-1 mr-sm-2" id="idtxtNivel" name="idtxtNivel" required>
            <?php
          
          for ($i=0; $i < mysqli_num_rows($buscarCriticidade) ; $i++) {
              $linhabuscarCriticidade = mysqli_fetch_assoc($buscarCriticidade); ?>
            <option
              value="<?php echo $linhabuscarCriticidade['idnivelcriticidade']; ?>">
              <?php echo $linhabuscarCriticidade['descricao']; ?>
            </option>

            <?php
          }
          ?>
          </select>
        </div>
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Atribuído a:</label>
          <select class="custom-select my-1 mr-sm-2" id="idtxtAnalista" name="idtxtAnalista" required>
            <?php
          
          for ($i=0; $i < mysqli_num_rows($buscarAnalista) ; $i++) {
              $linhabuscarAnalista = mysqli_fetch_assoc($buscarAnalista); ?>
            <option
              value="<?php echo $linhabuscarAnalista['idUsuario']; ?>">
              <?php echo $linhabuscarAnalista['NOME']; ?>
            </option>

            <?php
          }
          ?>
          </select>
        </div>

      </div>

      <div class="row">
        <div class="form-group col-lg-12">
          <label for="exampleInputEmail1">Descrição da solução</label>
          <textarea class="form-control" id="idtxtDescConclusao" rows="4" cols="50" name="idtxtDescConclusao"
            value="<?php echo(isset($_GET['idtxtDescConclusao']) ?$_GET['idtxtDescConclusao'] : "")?>"
            type="text"></textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-block col-lg-3">Salvar</button>

      <form method="POST" action="AlterarDadosUsuarioBuscar_POST.php">
        <div class="row">
          <div class="form-group col-lg-4">
            <label for="exampleInputEmail1">Código Chamado</label>
            <input class="form-control" id="idtxtIdChamado" name="idtxtIdChamado" type="number">
          </div>
          <div class="form-group col-lg-6">
            <label for="exampleInputEmail1">Assunto/Descrição</label>
            <input class="form-control" id="idtxtAssDesc" name="idtxtAssDesc" type="text">
          </div>
          <div class="form-group col-lg-2">
            <label for="exampleInputEmail1" style="color:transparent;">a</label>
            <button type="submit" class="btn btn-primary btn-block">Buscar</button>
          </div>
        </div>
      </form>


      <?php
                require_once("../banco/ConexaoVFBCompany.php");
                $var = new Mysql();
                $connect = $var->dbConnect();
                
                if (isset($_GET['idtxtIdChamado']) && isset($_GET['idtxtIdChamado'])) {
                    $condicao = "and idchamado = ".$_GET['idtxtIdChamado']." ";
                } elseif (isset($_GET['idtxtLoginBuscar'])) {
                    $condicao = "and (assuntochamado like '%".$_GET['idtxtAssDesc']."%' or  descricao like '%".$_GET['idtxtLogiidtxtAssDescnBuscar']."%') ";
                } else {
                    $condicao = "";
                }

                $result_Chamados = $var->freeRun("
                SELECT 
                      c.`idchamado`,
                      c.`usuario_idusuarioafetado`,
                      usuarioAfetado.NOME usuAfeNome,
                      c.assuntochamado,
                      c.`descricao`,
                      c.`datainicioproblema`,
                      c.`dataconclusao`,
                      c.`datacancelamento`,
                      c.`analistaatendendo`,
                      usuarioAnalista.NOME usuAnaNome,
                      c.`descricaoconclusao`,
                      c.`nivelcriticidade_idnivelcriticidade`,
                      c.`usuario_idUsuario`,
                      c.status_idstatus,
                      st.descricao statusdesc
                  FROM
                      `chamado` c
                      
                    INNER JOIN status st ON 
                      st.idstatus = c.`status_idSTATUS`
                          
                    INNER JOIN usuario usuarioAnalista ON 
                      usuarioAnalista.idusuario = c.`usuario_idUsuario`
                          
                    INNER JOIN usuario usuarioAfetado ON 
                      usuarioAfetado.idusuario = c.`analistaatendendo`
                  WHERE
                      c.status_idstatus = 3

                  $condicao
                ;
                ");

                $qtdelinhas = mysqli_num_rows($result_Chamados);
                ?>
      <div class="card mb-3 mb-md-4">
        <div class="card-body py-3">
          <div class="row align-items-center mb-3">
            <div class="col-md-auto d-flex align-items-center">
              <span class="text-muted mr-2">Show:</span>

              <select id="datatableEntries" class="js-custom-select" data-classes="custom-select-without-bordered">
                <option value="10" selected>10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
              </select>
            </div>

            <div class="col-md-auto ml-md-auto">
              <div class="input-group input-group-merge">
                <input id="datatableSearch" class="form-control bg-lighter border-0 pr-6"
                  placeholder="Enter search term" type="text">
                <div class="input-group-append-merge">
                  <a class="text-muted" href="#!">
                    <i class="nova-search"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="table-responsive-xl datatable">
            <table class="js-datatable-export table text-nowrap mb-0" data-dt-info="#datatableWithExport"
              data-dt-search="#datatableSearch" data-dt-entries="#datatableEntries" data-dt-is-show-paging="true"
              data-dt-pagination="datatableExport" data-dt-page-length="10" data-dt-is-responsive="false"
              data-dt-pagination-classes="pagination justify-content-end font-weight-semi-bold mb-0"
              data-dt-pagination-items-classes="page-item d-none d-md-block"
              data-dt-pagination-links-classes="page-link" data-dt-pagination-next-classes="page-item"
              data-dt-pagination-next-link-classes="page-link"
              data-dt-pagination-next-link-markup='<i class="nova-angle-right icon-text icon-text-xs d-inline-block"></i>'
              data-dt-pagination-prev-classes="page-item" data-dt-pagination-prev-link-classes="page-link"
              data-dt-pagination-prev-link-markup='<i class="nova-angle-left icon-text icon-text-xs d-inline-block"></i>'>
              <thead>

                <tr class="small">
                  <th class="font-weight-semi-bold py-2">
                    <div class="media align-items-center">
                      <div class="d-flex mr-2">ID</div>
                      <a class="d-flex ml-auto link-sorting-asc" href="#!">
                        <i class="nova-arrow-down icon-text icon-text-xs"></i>
                      </a>
                      <a class="d-flex link-sorting-desc" href="#!">
                        <i class="nova-arrow-up icon-text icon-text-xs"></i>
                      </a>
                    </div>
                  </th>
                  <th class="font-weight-semi-bold py-2">
                    <div class="media align-items-center">
                      <div class="d-flex mr-2">Afetado</div>

                      <a class="d-flex ml-auto link-sorting-asc" href="#!">
                        <i class="nova-arrow-down icon-text icon-text-xs"></i>
                      </a>
                      <a class="d-flex link-sorting-desc" href="#!">
                        <i class="nova-arrow-up icon-text icon-text-xs"></i>
                      </a>
                    </div>
                  </th>
                  <th class="font-weight-semi-bold py-2" style="width: 190px;">
                    <div class="media align-items-center">
                      <div class="d-flex mr-2">Assunto</div>

                      <a class="d-flex ml-auto link-sorting-asc" href="#!">
                        <i class="nova-arrow-down icon-text icon-text-xs"></i>
                      </a>
                      <a class="d-flex link-sorting-desc" href="#!">
                        <i class="nova-arrow-up icon-text icon-text-xs"></i>
                      </a>
                    </div>
                  </th>
                  <th class="font-weight-semi-bold py-2">
                    <div class="media align-items-center">
                      <div class="d-flex mr-2">Status</div>

                      <a class="d-flex ml-auto link-sorting-asc" href="#!">
                        <i class="nova-arrow-down icon-text icon-text-xs"></i>
                      </a>
                      <a class="d-flex link-sorting-desc" href="#!">
                        <i class="nova-arrow-up icon-text icon-text-xs"></i>
                      </a>
                    </div>
                  </th>
                  <th class="font-weight-semi-bold py-2">
                    <div class="media align-items-center">
                      <div class="d-flex mr-2">Atribuído</div>

                      <a class="d-flex ml-auto link-sorting-asc" href="#!">
                        <i class="nova-arrow-down icon-text icon-text-xs"></i>
                      </a>
                      <a class="d-flex link-sorting-desc" href="#!">
                        <i class="nova-arrow-up icon-text icon-text-xs"></i>
                      </a>
                    </div>
                  </th>
                  <th class="font-weight-semi-bold py-2">Ação</th>
                </tr>
              </thead>
              <tbody>
                <?php
                      for ($x = 0; $x < $qtdelinhas; $x++) /*estrutura de repeticao FOR*/ {
                          $linha = mysqli_fetch_assoc($result_Chamados);

                ?>
                <tr>

                  <td class="align-middle py-3">
                    <?php echo $linha['idchamado'];?>
                  </td>
                  <td class="align-middle py-3">
                    <?php echo $linha['usuAfeNome'];?>
                  </td>
                  <td class="align-middle py-3">
                    <?php echo $linha['assuntochamado'];?>
                  </td>
                  <td class="align-middle py-3">
                    <?php echo $linha['statusdesc'];?>
                  </td>
                  <td class="align-middle py-3">
                    <?php echo $linha['usuAnaNome'];?>
                  </td>
                  <td class="align-middle py-3">
                    <?php
                    $dateInicio = new DateTime($linha['datainicioproblema']);
                    $dateConclusao= new DateTime($linha['dataconclusao']);
                    $dateCancelamento = new DateTime($linha['datacancelamento']);
                    
                    echo "<a href='DashBoard.php?op=NovoChamado&idtxtIdChamadoHidden=".$linha['idchamado']."&idtxtIdChamado=".$linha['idchamado']."&idtxtAssunto=".$linha['assuntochamado']."&idtxtDesc=".$linha['descricao']."&idtxtUsuAfe=".$linha['usuario_idusuarioafetado']."&idtxtDataInicio=".$dateInicio->format('Y-m-d\TH:i')."&idtxtConclusao=".$dateConclusao->format('Y-m-d\TH:i')."&idtxtDataCancelamento=".$dateCancelamento->format('Y-m-d\TH:i')."&idtxtNivel=".$linha['nivelcriticidade_idnivelcriticidade']."&idtxtAnalista=".$linha['analistaatendendo']."&idtxtDescConclusao=".$linha['descricaoconclusao']."&idtxtStatus=".$linha['status_idstatus']."'>Selecionar</a>";
                      
                    ?>
                  </td>
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
  </div>

</div>

</form>

</div>


</div>

<!-- End Table with searching -->
<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
<script>
  $(document).ready(function() {
    $('#bodyTag').addClass('side-nav-mini-mode side-nav-closed side-nav-minified');
  });
</script>
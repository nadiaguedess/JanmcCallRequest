<?php

require_once '../classe/Alerta.php';
if (empty($var)) {
    require_once "../banco/ConexaoVFBCompany.php";
    $var     = new Mysql();
    $connect = $var->dbConnect();
}
      

$qtdUsuario = $var->freeRun("select count(*) qtd from usuario;");
$qtdUsuario = mysqli_fetch_assoc($qtdUsuario);

$buscarUltimosChamados = $var->freeRun("select idchamado, assuntochamado from chamado order by idchamado desc limit 7;");

$buscarChamadosConcluidos = $var->freeRun("select idchamado from chamado where status_idstatus = 6");

$buscarChamadosAndamento = $var->freeRun("select idchamado from chamado where status_idstatus = 5");


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
</br>

<div class="row">
  <div class="col-md-4 col-xl-4 mb-3 mb-md-4 mb-xl-5">
    <!-- Card -->
    <div class="card flex-row align-items-center p-3 p-md-4">
      <div class="icon icon-lg bg-soft-info rounded-circle mr-3">
        <i class="nova-user icon-text d-inline-block text-info"></i>
      </div>

      <div>
        <h4 class="lh-1 mb-1"><?php echo $qtdUsuario['qtd']?>
        </h4>
        <small>Total de Usuários</small>
      </div>

    </div>
    <!-- End Card -->
  </div>

  <div class="col-md-6 col-xl-4 mb-3 mb-md-4 mb-xl-5">
    <!-- Card -->
    <div class="card flex-row align-items-center p-3 p-md-4">
      <div class="icon icon-lg bg-soft-info rounded-circle mr-3">
        <i class="nova-check icon-text d-inline-block text-info"></i>
      </div>
      <div>
        <h4 class="lh-1 mb-1"><?php echo mysqli_num_rows($buscarChamadosConcluidos);?>
        </h4>
        <small>CHAMADOS CONCLUÍDOS</small>
      </div>

    </div>
    <!-- End Card -->
  </div>
  <div class="col-md-6 col-xl-4 mb-3 mb-md-4 mb-xl-5">
    <!-- Card -->
    <div class="card flex-row align-items-center p-3 p-md-4">
      <div class="icon icon-lg bg-soft-info rounded-circle mr-3">
        <i class="nova-plus icon-text d-inline-block text-info"></i>
      </div>
      <div>
        <h4 class="lh-1 mb-1"><?php echo mysqli_num_rows($buscarChamadosAndamento);?>
        </h4>
        <small>EM ANDAMENTO</small>
      </div>
    </div>
    <!-- End Card -->
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3 mb-md-4">
    <!-- Card -->
    <div class="card mb-3 mb-md-4 h-100">
      <div class="card-header d-flex">
        <h5 class="h6 text-uppercase font-weight-semi-bold mb-0"></h5>

        <div class="position-relative ml-auto">
          <a id="dropDownRoadmap" class="unfold-invoker d-flex text-muted" href="#" aria-controls="drop4Roadmap"
            aria-haspopup="true" aria-expanded="false" data-unfold-target="#drop4Roadmap" data-unfold-event="click"
            data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn"
            data-unfold-animation-out="fadeOut">
            <i class="nova-more"></i>
          </a>
        </div>
      </div>
      <div class="card-body p-0">

        <div class="border-top d-flex align-items-center py-3 px-3 px-md-4">
          <div>
            <h6 class="mb-0">
            </h6>
            <small class="text-muted"></small>
          </div>

          <div class="ml-auto">
            <i class="nova-check icon-text icon-text-xs text-secondary ml-2"></i>
          </div>
        </div>




      </div>
    </div>
    <!-- End Card -->
  </div>
  <!-- Card -->
  <div class="col-md-4 col-xl-4 mb-3 mb-md-4 mb-xl-5">
    <div class="card mb-3 mb-md-4 h-100">
      <div class="card-header">
        <h5 class="h6 text-uppercase font-weight-semi-bold mb-0">Últimos Chamados Abertos <a
            href="DashBoard.php?op=Saque"></a></h5>
      </div>

      <div class="card-body p-0">

        <?php
            $buscarUltimosChamadosCount = mysqli_num_rows($buscarUltimosChamados);
        ?>

        <?php

            for ($x = 0; $x < $buscarUltimosChamadosCount; $x++) /*estrutura de repeticao FOR*/ {
                $linhaUltiCham = mysqli_fetch_assoc($buscarUltimosChamados);

        ?>

        <div class="row align-items-center justify-content-between border-top p-3 mb-md-2 p-md-4 mx-0">
          <div class="col-4">
            <div class="d-inline-block icon icon-xs bg-soft-success rounded-circle mr-2">

              <i class="nova-check icon-text icon-text-xs d-inline-block text-success"></i>
            </div>
            #<?php echo $linhaUltiCham['idchamado'];?>
          </div>


          <div class="col-8">
            <?php echo $linhaUltiCham['assuntochamado'];?>
          </div>


        </div>
        <?php
          }
        ?>


      </div>

      <div class="card-footer d-print-none border-top p-3 p-md-4">
        <a class="font-weight-semi-bold text-primary" href="DashBoard.php?op=Saque">Mostrar Todos os Pedidos <i
            class="nova-angle-right icon-text icon-text-xs d-inline-block ml-1"></i></a>
      </div>
    </div>
  </div>
  <!-- End Card -->

  <!-- End Card -->
  <div class="col-xs-12 col-md-12 col-xl-4 mb-3 mb-md-4">
    <!-- Card -->
    <div class="card h-100">
      <div class="card-header d-flex">
        <h5 class="h6 font-weight-semi-bold text-uppercase mb-0">CHAMADOS CONCLUÍDOS</h5>

        <div class="position-relative ml-auto">
          <a id="dropDown2Invoker" class="unfold-invoker d-flex text-muted" href="#" aria-controls="dropDown2"
            aria-haspopup="true" aria-expanded="false" data-unfold-target="#dropDown2" data-unfold-event="click"
            data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn"
            data-unfold-animation-out="fadeOut">
            <i class="nova-more" data-toggle="tooltip" data-placement="top" title="Usuários cadastros no sistema."></i>
          </a>

        </div>
      </div>
      <div class="card-body p-0">
        <div class="row mb-3 mb-md-9">
          <div class="col-6 text-right pr-3 pr-md-4">
            <div class="h3 mb-0">
              <span class="text-info"></span>
            </div>
            <small class="text-muted">
            </small>
          </div>

          <div class="col-6 border-left pl-3 pl-md-4">
            <div class="h3 mb-0">
              <span class="text-success"></span>
            </div>
            <small class="text-muted">
            </small>
          </div>
        </div>


        <div class="border-bottom media align-items-center p-3">

          <div class="media-body d-flex align-items-center mr-2">
            <span>SEMANA</span>
            <span class="ml-auto"></span>
          </div>

          <i class="nova-user icon-text icon-text-xs d-flex text-success ml-auto"> </i>
        </div>

        <div class="media align-items-center p-3">

          <div class="media-body d-flex align-items-center mr-2">
            <span>MÊS</span>
            <span class="ml-auto"></span>
          </div>

          <i class="nova-user icon-text icon-text-xs d-flex text-success ml-auto"> </i>
        </div>
      </div>
    </div>
    <!-- End Card -->
  </div>
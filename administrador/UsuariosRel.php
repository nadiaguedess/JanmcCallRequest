<?php
if (!isset($_SESSION['LOGIN'])) { //validou sessão (deve conter em todas telas no início do código, logo após o start)
    echo "<script>alert('Favor fazer login!');</script>";
    echo "<script>location.href='login.php';</script>";
} // iniciou sessão (deve ser usado em TODAS as telas sempre na primeira linha do php)
?>
<?php include '../classe/Alerta.php'; ?>
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
<?php


require_once("../banco/ConexaoVFBCompany.php");
$var = new Mysql();
$connect = $var->dbConnect();

$result_Depositos = $var->freeRun("SELECT 
usu.NOME,
usu.LOGIN,
usu.EMAIL,
usu.TELEFONE_UM,
usu.DATACADASTRO
FROM 
`usuario` usu
;
                                ");

$qtdelinhas = mysqli_num_rows($result_Depositos);
?>
<div class="card mb-3 mb-md-4">
  <div class="card-header">
    <h5 class="font-weight-semi-bold mb-0">Usuários</h5>
  </div>

  <div class="card-body py-0">
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
          <input id="datatableSearch" class="form-control bg-lighter border-0 pr-6" placeholder="Enter search term"
            type="text">
          <div class="input-group-append-merge">
            <a class="text-muted" href="#!">
              <i class="nova-search"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive datatable">
      <table class="js-datatable-export table text-nowrap mb-0" data-dt-info="#datatableWithExport"
        data-dt-search="#datatableSearch" data-dt-entries="#datatableEntries" data-dt-is-show-paging="true"
        data-dt-pagination="datatableExport" data-dt-page-length="10" data-dt-is-responsive="false"
        data-dt-pagination-classes="pagination justify-content-end font-weight-semi-bold mb-0"
        data-dt-pagination-items-classes="page-item d-none d-md-block" data-dt-pagination-links-classes="page-link"
        data-dt-pagination-next-classes="page-item" data-dt-pagination-next-link-classes="page-link"
        data-dt-pagination-next-link-markup='<i class="nova-angle-right icon-text icon-text-xs d-inline-block"></i>'
        data-dt-pagination-prev-classes="page-item" data-dt-pagination-prev-link-classes="page-link"
        data-dt-pagination-prev-link-markup='<i class="nova-angle-left icon-text icon-text-xs d-inline-block"></i>'>
        <thead>

          <tr class="small">

            <th class="font-weight-semi-bold py-2">
              <div class="media align-items-center">
                <div class="d-flex mr-2">NOME</div>

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
                <div class="d-flex mr-2">LOGIN</div>

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
                <div class="d-flex mr-2">E-MAIL</div>

                <a class="d-flex ml-auto link-sorting-asc" href="#!">
                  <i class="nova-arrow-down icon-text icon-text-xs"></i>
                </a>
                <a class="d-flex link-sorting-desc" href="#!">
                  <i class="nova-arrow-up icon-text icon-text-xs"></i>
                </a>
              </div>
            </th>
            <th class="font-weight-semi-bold py-2">TELEFONE</th>
            <th class="font-weight-semi-bold py-2">DATA DE CADASTRO</th>

          </tr>
        </thead>
        <tbody>
          <?php

                for ($x = 0; $x < $qtdelinhas; $x++) /*estrutura de repeticao FOR*/ {
                    $linha = mysqli_fetch_assoc($result_Depositos);

            ?>
          <tr>
            <td class="align-middle py-3">
              <h6 class="mb-0"> <?php echo $linha['NOME'];  ?>
              </h6>

            </td>

            <td class="align-middle py-3">
              <h6 class="mb-0"><?php echo $linha['LOGIN'];  ?>
              </h6>

            </td>
            <td class="align-middle py-3">
              <h6 class="mb-0"><?php echo $linha['EMAIL'];  ?>
              </h6>

            </td>
            <td class="align-middle py-3">
              <h6 class="mb-0"><?php echo $linha['TELEFONE_UM'];  ?>
              </h6>

            </td>
            <td class="align-middle py-3">
              <h6 class="mb-0"><?php echo $linha['DATACADASTRO'];  ?>
              </h6>

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
<!-- End Table with searching -->
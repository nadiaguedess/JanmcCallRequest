<?php
if (!isset($_SESSION['LOGIN'])) { //validou sessão (deve conter em todas telas no início do código, logo após o start)
    echo "<script>alert('Favor fazer login!');</script>";
    echo "<script>location.href='login.php';</script>";
    exit;
} // iniciou sessão (deve ser usado em TODAS as telas sempre na primeira linha do php)
if ($_SESSION['TIPUSU'] != 1) {
    echo "<script>alert('Você não tem permissão!');</script>";
    echo "<script>location.href='DashBoard.php';</script>";
    exit;
}
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
<div class="container">

  <div class="card-body">
    <form method="POST" action="AlterarDadosUsuario_POST.php">
      <div class="row">
        <div class="form-group col-lg-5">
          <input class="form-control" id="idtxtUsuario" name="idtxtUsuario" type="hidden"
            placeholder="Selecione o usuário abaixo" value="<?php if (isset($_GET['id'])) {
            echo $_GET['id'];
        }?>" required>
          <label for="exampleInputEmail1">Login</label>
          <input class="form-control" id="idtxtLogin" name="idtxtLogin" type="text"
            placeholder="Selecione o usuário abaixo" value="<?php if (isset($_GET['login'])) {
            echo $_GET['login'];
        }?>" readonly>
        </div>
        <div class="form-group col-lg-7">
          <label for="exampleInputEmail1">Nome</label>
          <input class="form-control" id="idtxtNome" name="idtxtNome" type="text" value="<?php if (isset($_GET['nome'])) {
            echo $_GET['nome'];
        }?>" required>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-lg-6">
          <label for="exampleInputEmail1">E-mail</label>
          <input class="form-control" id="idtxtemail" name="idtxtemail" type="text" value="<?php if (isset($_GET['email'])) {
            echo $_GET['email'];
        }?>" required>
        </div>
        <div class="form-group col-lg-6">
          <label for="exampleInputEmail1">Telefone</label>
          <input class="form-control" id="idtxtTelefone" name="idtxtTelefone" type="text" value="<?php if (isset($_GET['telefone'])) {
            echo $_GET['telefone'];
        }?>" required>

        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block col-lg-3">Salvar</button>
    </form>

  </div>


</div>

<form method="POST" action="AlterarDadosUsuarioBuscar_POST.php">
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
      <button type="submit" class="btn btn-primary btn-block">Buscar</button>
    </div>
  </div>
</form>


<?php
                require_once("../banco/ConexaoVFBCompany.php");
                $var = new Mysql();
                $connect = $var->dbConnect();
                
                if (isset($_GET['idtxtLoginBuscar']) && isset($_GET['idtxtNomeBuscar'])) {
                    $condicao = "and login like '%".$_GET['idtxtLoginBuscar']."%' and nome like '%".$_GET['idtxtNomeBuscar']."%' ";
                } elseif (isset($_GET['idtxtLoginBuscar'])) {
                    $condicao = "and login like '%".$_GET['idtxtLoginBuscar']."%' ";
                } elseif (isset($_GET['idtxtNomeBuscar'])) {
                    $condicao = "and nome like '%".$_GET['idtxtNomeBuscar']."%' ";
                } else {
                    $condicao = "";
                }

                $result_Depositos = $var->freeRun("SELECT 
                                					* 
                                				FROM 
                                					usuario 
                                				WHERE 	
                                					idUSUARIO > 19
                                					$condicao
                                				order by 
                                				    idusuario desc
                                				limit 
                                				    100");

                $qtdelinhas = mysqli_num_rows($result_Depositos);
                ?>
<div class="card mb-3 mb-md-4">
  <div class="card-body py-0">
    <div class="row align-items-center mb-3">
      <div class="col-md-auto d-flex align-items-center">
        <span class="text-muted mr-2">Show:</span>

        <select id="datatableEntries" class="js-custom-select" data-classes="custom-select-without-bordered">
          <option value="6" selected>6 entries</option>
          <option value="12">12 entries</option>
          <option value="18">18 entries</option>
          <option value="24">24 entries</option>
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

    <div class="table-responsive-xl datatable">
      <table class="js-search-sorting-col-disable table text-nowrap mb-0" data-dt-info="#datatableInfo"
        data-dt-search="#datatableSearch" data-dt-entries="#datatableEntries" data-dt-is-show-paging="true"
        data-dt-pagination="datatablePagination" data-dt-page-length="6" data-dt-is-responsive="false"
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
                <div class="d-flex mr-2">Username</div>

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
                <div class="d-flex mr-2">Nome</div>

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
                <div class="d-flex mr-2">Email</div>

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
                    $linha = mysqli_fetch_assoc($result_Depositos);

            ?>
          <tr>
            <td class="align-middle py-3">
              <div class="media align-items-center">

                <div class="media-body">
                  <h6 class="mb-0"><?php echo $linha['idUsuario'];  ?>
                  </h6>

                </div>
              </div>
            </td>
            <td class="align-middle py-3">
              <h6 class="mb-0"> <?php echo $linha['LOGIN'];  ?>
              </h6>

            </td>
            <td class="align-middle py-3">
              <?php echo $linha['NOME'];  ?>
            </td>
            <td class="align-middle py-3">
              <h6 class="mb-0"><?php echo $linha['EMAIL'];  ?>
              </h6>

            </td>
            <td class="align-middle py-3">
              <?php
                                if ($linha['TIPO'] == 1) {
                                    $tipo =  "<span class='badge badge-pill badge-primary mb-2 mr-2'></span>";
                                } else {
                                    $tipo =  "<span class='badge badge-pill badge-warning mb-2 mr-2'></span>";
                                }
                                
                                echo "<a href='DashBoard.php?op=AlterarDadosUsuario&id=".$linha['idUsuario']."&login=".$linha['LOGIN']."&nome=".$linha['NOME']."&cpf=".$linha['CPF']."&email=".$linha['EMAIL']."&rg=".$linha['RG']."&telefone=".$linha['TELEFONE_UM']."'>Alterar ".$tipo." </a>";
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
<!-- End Table with searching -->
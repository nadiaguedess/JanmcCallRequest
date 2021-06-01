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

require_once("../banco/ConexaoVFBCompany.php");

$var = new Mysql();
$connect = $var->dbConnect();

//Buscar Tipo de Usuario
$buscarTipoUsuario = $var->freeRun("select * from tipousuario");

?>
</div>
<div class="container">

  <div class="card-body">
    <form method="POST" action="InserirUsuario_POST.php">
      <div class="row">
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Login</label>
          <input class="form-control" id="idtxtLogin" name="idtxtLogin" type="text" required>
        </div>
        <div class="form-group col-lg-4">
          <label for="exampleInputEmail1">Nome</label>
          <input class="form-control" id="idtxtNome" name="idtxtNome" type="text" required>
        </div>
        <div class="form-group col-lg-5">
          <label for="exampleInputEmail1">E-mail</label>
          <input class="form-control" id="idtxtemail" name="idtxtemail" type="email" required>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Telefone de Contato</label>
          <input class="form-control" id="idtxtTelefone" name="idtxtTelefone" type="text" required>
        </div>
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Cidade</label>
          <input class="form-control" id="idtxtCidade" name="idtxtCidade" type="text" required>
        </div>

        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Tipo de Usuário:</label>
          <select class="custom-select my-1 mr-sm-2" id="idtxtTipoUsu" name="idtxtTipoUsu" required>
            <?php
          
          for ($i=0; $i < mysqli_num_rows($buscarTipoUsuario) ; $i++) {
              $linhabuscarTipoUsuario = mysqli_fetch_assoc($buscarTipoUsuario); ?>
            <option
              value="<?php echo $linhabuscarTipoUsuario['idTIPOUSUARIO']; ?>">
              <?php echo $linhabuscarTipoUsuario['TIPOUSUARIODESCRICAO']; ?>
            </option>

            <?php
          }
          ?>
          </select>

        </div>
        <div class="form-group col-lg-3">
          <label for="exampleInputEmail1">Senha</label>
          <input class="form-control" id="idtxtSenha" name="idtxtSenha" type="password" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary btn-block col-lg-3">Salvar</button>
    </form>

  </div>


</div>

<!-- End Table with searching -->
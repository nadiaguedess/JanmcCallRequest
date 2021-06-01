<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Title -->
  <title>Admin | JanmcCallRequest</title>

  <!-- Required Meta Tags Always Come First -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <!-- Favicon -->

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700" rel="stylesheet">
  <link href="assets/vendor/nova-icons/nova-icons.css" rel="stylesheet">

  <!-- CSS Nova Template -->
  <link rel="stylesheet" href="assets/css/theme.css">

  <!-- DEMO Only -->
  <style>
    .arrow-back {
      position: absolute;
      display: flex;
      align-items: center;
      top: 6%;
      left: 5%;
    }
  </style>
  <!-- End DEMO Only -->
</head>

<body>
  <main class="d-flex flex-column u-hero u-hero--end min-vh-100 bg-dark">
    <!-- Arrow Back (DEMO Only) 
  <a href="../widgets.html" class="arrow-back d-none d-md-block"><i class="nova-arrow-left mr-2"></i> Back</a>
  <!-- End Arrow Back (DEMO Only) -->

    <div class="container py-7 my-auto">
      <div class="d-flex align-items-center justify-content-center">

        <!-- Card -->
        <div class="card" style="width: 460px; max-width: 100%;">
          <div class="card-body p-4 p-lg-7">
            <div class="col-lg-12">
              <?php
            require_once '../classe/Alerta.php';
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
            <h2 class="text-center mb-4 h3">Admin | JanmcCallRequest</h2>

            <form class="js-validate" method="POST" action="login_POST.php">
              <input class="form-control" id="idatd" name="idatd" type="hidden" value="<?php if (isset($_GET['CODE'])) {
                echo $_GET['CODE'];
            } ?>">

              <div class="form-group js-form-message js-focus-state">
                <label for="email">Usuário</label>
                <div class="input-group input-group-merge">
                  <!-- Email Field Icon -->
                  <div class="input-group-prepend-merge">
                    <i class="nova-email"></i>
                  </div>
                  <!-- End Email Field Icon -->

                  <!-- Email Field -->
                  <input type="text" id="idtxtUsername" class="form-control form-control-prepend-icon"
                    placeholder="Seu username" name="idtxtUsername" required data-msg="Por favor insire seu Username."
                    data-error-class="u-has-error" data-success-class="u-has-success">
                  <!-- End Email Field -->
                </div>
              </div>
              <!-- End Email -->

              <!-- Password -->
              <div class="form-group js-form-message js-focus-state">
                <label for="password">Senha</label>
                <div class="input-group input-group-merge">
                  <!-- Password Field Icon -->
                  <div class="input-group-prepend-merge">
                    <i class="nova-lock"></i>
                  </div>
                  <!-- End Password Field Icon -->

                  <!-- Password Field -->
                  <input type="password" id="idtxtSenha" class="form-control form-control-prepend-icon"
                    placeholder="Digite sua senha" name="idtxtSenha" <?php if (!isset($_GET['CODE'])) {
                echo "required";
            } ?>
                  data-error-class="u-has-error"
                  data-success-class="u-has-success">
                  <!-- End Password Field -->
                </div>
              </div>
              <!-- End Password -->
              <div class="text-center">
                <div class="g-recaptcha" data-sitekey="6LfTlqMaAAAAABaRfgEG5_RVDu-ozkqeCeSbIHfo"></div>
              </div>
              <!-- Submit Button -->
              <button type="submit" class="btn btn-block btn-wide btn-primary text-uppercase">Entrar</button>
              <!-- End Submit Button -->


              <!-- End Link -->
            </form>
          </div>
        </div>
        <!-- End Card -->
      </div>
    </div>

    <!-- Footer -->
    <footer class="small bg-white p-3 px-md-4 mt-auto bg-transparent">
      <div class="row justify-content-between">
        <div class="col-lg text-center text-lg-left  mb-3 mb-lg-0">
          &copy; 2020 TRUSTMLM. All Rights Reserved.
        </div>

        <div class="col-lg text-center text-lg-right">
          <ul class="list-dot list-inline mb-0">
            <li class="list-dot-item list-dot-item-not list-inline-item mr-lg-2"><a class="link-dark" href="#"></a></li>
            <li class="list-dot-item list-inline-item mr-lg-2"><a class="link-dark" href="#"></a></li>
            <li class="list-dot-item list-inline-item mr-lg-2"><a class="link-dark" href="#"></a></li>
          </ul>
        </div>
      </div>
    </footer>
    <!-- End Footer -->
  </main>

  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <!-- JS Global Compulsory -->
  <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
  <script src="assets/vendor/popper.js/dist/umd/popper.min.js"></script>
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- JS Implementing Libraries -->
  <script src="assets/vendor/jquery-validation/dist/jquery.validate.min.js"></script>

  <!-- JS Nova  -->
  <script src="assets/js/hs.core.js"></script>
  <script src="assets/js/components/hs.jquery-validation.js"></script>

  <!-- JS Libraries Init. -->
  <script>
    $(document).on('ready', function() {
      // initialization of form validation
      $.HSCore.components.HSValidation.init('.js-validate');
    });
  </script>
</body>

</html>
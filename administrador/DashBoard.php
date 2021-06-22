<?php
session_start();// iniciou sess�0�0o (deve ser usado em TODAS as telas sempre na primeira linha do php)// adicionou vari��vel na sess�0�0o (logo ap��s fazer login)
if (!isset($_SESSION['LOGIN'])) { //validou sess�0�0o (deve conter em todas telas no in��cio do c��digo, logo ap��s o start)
    echo "<script>alert('Ended Session!');</script>";
    echo "<script>location.href='index.php';</script>";
} // iniciou sess�0�0o (deve ser usado em TODAS as telas sempre na primeira linha do php)
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- Title -->
  <title>Admin | JanmcCallRequest</title>

  <!-- Required Meta Tags Always Come Firast -->

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700" rel="stylesheet">
  <link href="assets/vendor/nova-icons/nova-icons.css" rel="stylesheet">

  <!-- CSS Implementing Libraries -->
  <link rel="stylesheet" href="assets/vendor/animate.css/animate.min.css">
  <link rel="stylesheet" href="assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
  <link rel="stylesheet" href="assets/vendor/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="assets/vendor/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="assets/vendor/chartist/dist/chartist.min.css">
  <link rel="stylesheet" href="assets/vendor/chartist-plugin-tooltip/dist/chartist-plugin-tooltip.css">
  <link rel="stylesheet" href="assets/vendor/jquery-shorten/src/jquery.shorten.css">


  <!-- CSS Nova Template -->
  <link rel="stylesheet" href="assets/css/theme.css">

  <style>
    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      background-color: red;
      color: white;
      text-align: center;
    }
  </style>
</head>

<body class="has-sidebar has-fixed-sidebar-and-header" id="bodyTag">
  <!-- Header -->
  <header class="header header-light bg-white">
    <nav class="navbar flex-nowrap p-0">
      <div class="navbar-brand-wrapper col-auto" style="background-color: black;">
        <!-- Logo For Desktop View padding-left: 40px;-->
        <a class="navbar-brand navbar-brand-desktop side-nav-hide-on-closed" href="DashBoard.php"
          style="padding-left: 40px;">

          JanmcCallRequest


        </a>
        <a class="navbar-brand navbar-brand-desktop side-nav-show-on-closed" href="DashBoard.php">
          JCR
        </a>
        <!-- End Logo For Desktop View -->
      </div>

      <div class="header-content col px-md-3 px-md-3">
        <div class="d-flex align-items-center">
          <!-- Side Nav Toggle -->
          <a class="js-side-nav header-invoker mr-md-2" href="#" data-close-invoker="#sidebarClose"
            data-target="#sidebar" data-target-wrapper="body">
            <i class="nova-align-left"></i>
          </a>
          <!-- End Side Nav Toggle -->

          <!-- Header Search -->
          <div class="js-header-search position-relative" data-search-target="#headerSearchResults"
            data-search-mobile-invoker="#headerSearchMobileInvoker" data-search-form="#headerSearchForm"
            data-search-field="#headerSearchField" data-search-clear="#headerSearchResultsClear">
            <a id="headerSearchMobileInvoker" class="header-search-invoker header-invoker" href="#">
              <i class="nova-search"></i>
            </a>


          </div>
          <!-- End Header Search -->

          <!-- Header Dropdown -->
          <div class="dropdown ml-auto">
            <!-- <a id="messagesInvoker" class="header-invoker" href="#" aria-controls="messages" aria-haspopup="true" aria-expanded="false"
             data-unfold-event="click"
             data-unfold-target="#messages"
             data-unfold-type="css-animation"
             data-unfold-duration="300"
             data-unfold-animation-in="fadeIn"
             data-unfold-animation-out="fadeOut">
            <span class="indicator indicator-bordered indicator-top-right indicator-secondary rounded-circle"></span>
            <i class="nova-email"></i>
          </a> -->
            <?php
                require_once "../banco/ConexaoVFBCompany.php";
                if (!isset($var)) {
                    $var     = new Mysql();
                    $connect = $var->dbConnect();
                }
                
               $resultMensagem  = $var->freeRun("SELECT
                                                  mensagem,
                                                  USUARIO_idUSUARIO,
                                                  idMensagem,
                                                  datamensagem,
                                                  count(idMensagem) count
                                              FROM
                                                 mensagem
                                             WHERE
                                                 USUARIO_idUSUARIO = ".$_SESSION['ID']."
                                              ORDER BY
                                               idMensagem");
                                               
                $resultCount  = $var->freeRun("SELECT
                                                  count(idMensagem) count
                                              FROM
                                                 mensagem
                                             WHERE
                                                 USUARIO_idUSUARIO = ".$_SESSION['ID'].";");
                
                /* determine number of rows result set */
                $row_cnt = mysqli_num_rows($resultMensagem);
                 $count = mysqli_fetch_assoc($resultCount);
        
        ?>

            <div id="messages" class="dropdown-menu dropdown-menu-center py-0 mt-4 w-18_75rem w-md-22_5rem"
              aria-labelledby="messagesInvoker">

            </div>
          </div>
          <!-- End Header Dropdown -->

          <!-- Header Dropdown -->
          <div class="dropdown ml-2">
            <a id="notificationsInvoker" class="header-invoker" href="#" aria-controls="notifications"
              aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#notifications"
              data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn"
              data-unfold-animation-out="fadeOut">
              <span class="indicator indicator-bordered indicator-top-right indicator-primary rounded-circle"></span>
              <i class="nova-bell"></i>
            </a>

            <div id="notifications" class="dropdown-menu dropdown-menu-center py-0 mt-4 w-18_75rem w-md-22_5rem"
              aria-labelledby="notificationsInvoker">
              <div class="card">
                <div class="card-header d-flex align-items-center border-bottom py-3">
                  <h5 class="mb-0">Notifications</h5>
                  <a class="link small ml-auto" href="#">Clear All</a>
                </div>

                <div class="card-body p-0">
                  <div class="list-group list-group-flush">
                    <div class="list-group-item list-group-item-action">
                      <div class="d-flex align-items-center text-nowrap mb-2">
                        <i class="nova-star icon-text text-primary mr-2"></i>
                        <?php
                                for ($x = 0; $x < $row_cnt; $x++) /*estrutura de repeticao FOR*/ {
                                $linha = mysqli_fetch_assoc($resultMensagem);
                        ?>
                        <h6 class="font-weight-semi-bold mb-0"></h6>
                        <span class="list-group-item-date text-muted ml-auto"><?php echo $linha['datamensagem'];?></span>
                      </div>

                      <p class="mb-0">


                        <?php echo $linha['mensagem'];?>

                        <?php
                            }
                        ?>
                      </p>
                      <a class="list-group-item-closer text-muted" href="#"><i class="nova-close"></i></a>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Header Dropdown -->

          <!-- Header User Dropdown -->
          <div class="dropdown mx-3">
            <a id="profileMenuInvoker" class="header-complex-invoker" href="#" aria-controls="profileMenu"
              aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#profileMenu"
              data-unfold-type="css-animation" data-unfold-duration="300" data-unfold-animation-in="fadeIn"
              data-unfold-animation-out="fadeOut">
              <img class="avatar rounded-circle mr-md-2" src="" alt="Icon">
              <span class="d-none d-md-block"><?php echo $_SESSION['LOGIN'];?></span>
              <i class="nova-angle-down d-none d-md-block ml-2"></i>
            </a>

            <ul id="profileMenu"
              class="unfold unfold-user unfold-light unfold-top unfold-centered position-absolute pt-2 pb-1 mt-4"
              aria-labelledby="profileMenuInvoker">
              <li class="unfold-item">
                <a class="unfold-link d-flex align-items-center text-nowrap" href="index_logout.php">
                  <span class="unfold-item-icon mr-3">
                    <i class="nova-power-off"></i>
                  </span>
                  Sign Out
                </a>
              </li>
            </ul>
          </div>
          <!-- End Header User Dropdown -->

          <!-- Info Sidebar Toggle -->
          <a id="activityInvoker" class="header-invoker" href="#" aria-controls="activity" aria-haspopup="true"
            aria-expanded="false" data-unfold-event="click" data-unfold-target="#activity"
            data-unfold-type="css-animation" data-unfold-animation-in="fadeInRight"
            data-unfold-animation-out="fadeOutRight" data-unfold-duration="300">
            <i class="nova-align-right"></i>
          </a>
          <!-- End Info Sidebar Toggle -->

          <!-- Info Sidebar -->
          <div id="activity"
            class="js-custom-scroll sidebar sidebar-light sidebar-right sidebar-full-height unfold-css-animation unfold-hidden position-fixed"
            aria-labelledby="activityInvoker">
            <div class="border-bottom d-flex align-items-center text-nowrap px-3 px-md-4 py-3">
              <h5 class="mb-0">Activity</h5>
              <a id="activityClose" class="text-muted small ml-auto" href="#" aria-controls="activity"
                aria-haspopup="true" aria-expanded="false" data-unfold-event="click" data-unfold-target="#activity"
                data-unfold-type="css-animation" data-unfold-animation-in="fadeInRight"
                data-unfold-animation-out="fadeOutRight" data-unfold-duration="300">
                <i class="nova-close icon-text"></i>
              </a>
            </div>

            <form>
              <section class="border-bottom p-3 p-md-4">
                <h6 class="d-none font-weight-semi-bold mb-0">Activity</h6>

              </section>


              <section class="border-bottom p-3 p-md-4">
                <?php
        
            $result = $var->freeRun("SELECT
										message
										,DATE_FORMAT(DATANOT, '%d/%m/%y %H:%m:%s') as DATANOT
										,LOGIN
										,IP
									
									FROM 
										notification
										
										INNER JOIN usuario on 
										    usuario.idusuario = notification.usuario_idusuario
								    where
	                                    message = 'Login no Admin'
	                                order by 
										DATE_FORMAT(DATANOT, '%y/%m/%d') desc,
										DATE_FORMAT(DATANOT, '%H:%m:%s') desc
									LIMIT 15");
            
                        
            $qtdelinhas = mysqli_num_rows($result);
        ?>


                <?php

            for ($x = 0; $x < $qtdelinhas; $x++) /*estrutura de repeticao FOR*/
            {
            $linha = mysqli_fetch_assoc($result);
                
            ?>
                <div
                  class="alert alert-dismissible alert-left-bordered border-primary bg-soft-primary d-flex align-items-center rounded-0 p-3 fade show"
                  role="alert" style="margin-bottom: 1px;">
                  <i class="nova-pin-alt icon-text text-warning mr-2"></i>
                  <span><?php echo $linha['message']."|".$linha['LOGIN'];?></span>
                  <strong class="font-weight-semi-bold ml-auto"><?php echo $linha['IP']." - ".$linha['DATANOT'];?></strong>
                </div>

                <?php
        }
        ?>

              </section>

            </form>
          </div>
          <!-- End Info Sidebar -->
        </div>
      </div>
    </nav>
  </header>
  <!-- End Header -->

  <main class="main">
    <!-- Sidebar Nav -->
    <aside id="sidebar" class="js-custom-scroll side-nav">
      <ul id="sideNav" class="side-nav-menu side-nav-menu-top-level mb-0">

        <!-- Dashboards -->
        <li class="side-nav-menu-item active">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=Home">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-dashboard"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Dashboard</span>
          </a>
        </li>

        <li class="sidebar-heading h6">Chamados</li>
        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=NovoChamado">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Controle de Chamados</span>
          </a>
        </li>
        <li class="sidebar-heading h6">Relatórios</li>
        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=ChamadosAberto">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Chamados em aberto</span>
          </a>
        </li>
        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=ChamadosAndamentoAtribuido">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Chamados em Andam./Atrib.</span>
          </a>
        </li>
        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=ChamadosConcluidos">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Chamados concluídos</span>
          </a>
        </li>
        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=ChamadosCancelados">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Chamados cancelados</span>
          </a>
        </li>

        <!-- Sidebar Sub Title -->
        <li class="sidebar-heading h6">Usuários</li>

        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=InserirUsuario">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Inserir Usuário</span>
          </a>
        </li>

        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=AlterarDadosUsuario">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Alterar Dados Usuários</span>
          </a>
        </li>

        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=TrocarSenhaUsuario">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-angle-double-right"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Trocar Senha Usuários</span>
          </a>
        </li>
        <!-- End My Wallet -->
        <li class="sidebar-heading h6">Relatórios</li>

        <!-- Affiliate Program -->
        <li class="side-nav-menu-item">
          <a class="side-nav-menu-link media align-items-center" href="DashBoard.php?op=UsuariosRel">
            <span class="side-nav-menu-icon d-flex mr-3">
              <i class="nova-user"></i>
            </span>
            <span class="side-nav-fadeout-on-closed media-body">Relação de Usuários</span>
          </a>
        </li>
        <!-- End My Wallet -->


      </ul>
    </aside>
    <!-- End Sidebar Nav -->

    <div class="content">
      <div class="container-fluid">
        <div class="col-lg-12">
          <div>

            <?php
                        if (isset($_GET['op'])) {
                            $op = $_GET['op'];
                            switch ($op) {
                                case 'TrocarSenhaUsuario':
                                    include 'TrocarSenhaUsuario.php';
                                    break;
                                case 'UsuariosRel':
                                    include 'UsuariosRel.php';
                                    break;
                                case 'Suporte':
                                    include 'Suporte.php';
                                    break;
                                case 'AlterarDadosUsuario':
                                    include 'AlterarDadosUsuario.php';
                                    break;
                                case 'InserirMensagem':
                                    include 'InserirMensagem.php';
                                    break;
                                case 'NovoChamado':
                                      include 'NovoChamado.php';
                                      break;
                                case 'ChamadosAberto':
                                        include 'ChamadosAberto.php';
                                        break;
                                case 'ChamadosCancelados':
                                  include 'ChamadosCancelados.php';
                                  break;
                                case 'ChamadosConcluidos':
                                    include 'ChamadosConcluidos.php';
                                    break;
                                case 'ChamadosAndamentoAtribuido':
                                  include 'ChamadosAndamentoAtribuido.php';
                                  break;
                                case 'InserirUsuario':
                                  include 'InserirUsuario.php';
                                  break;
                                default:
                                     include 'Home.php';
                                    break;                            }
                        } else {
                            include 'Home.php';
                            //include 'Principal.php';
                        }
                    ?>
          </div>
        </div>
      </div>


    </div>

  </main>
  <!-- Footer -->
  <footer class="large bg-white p-3 px-md-4 mt-auto d-print-none">

    <div class="col-lg text-center text-lg-right">
      &copy; 2020 TRUSTMLM. All Rights Reserved.
    </div>
    </div>
  </footer>
  <!-- End Footer -->

  <!-- JS Global Compulsory -->
  <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="assets/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
  <script src="assets/vendor/popper.js/dist/umd/popper.min.js"></script>
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- JS Implementing Libraries -->
  <script src="assets/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="assets/vendor/select2/dist/js/select2.full.min.js"></script>
  <script src="assets/vendor/table-edits/build/table-edits.min.js"></script>
  <script src="assets/vendor/datatables/media/js/jquery.dataTables.min.js"></script>
  <script src="assets/vendor/datatables.net-select/js/dataTables.select.js"></script>
  <script src="assets/vendor/jquery-ui/jquery-ui.core.min.js"></script>
  <script src="assets/vendor/jquery-ui/ui/widgets/menu.js"></script>
  <script src="assets/vendor/jquery-ui/ui/widgets/mouse.js"></script>
  <script src="assets/vendor/jquery-ui/ui/widgets/autocomplete.js"></script>
  <script src="assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="assets/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="assets/vendor/jszip/dist/jszip.min.js"></script>
  <script src="assets/vendor/pdfmake/build/pdfmake.min.js"></script>
  <script src="assets/vendor/pdfmake/build/vfs_fonts.js"></script>
  <script src="assets/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="assets/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="assets/vendor/datatables.net-buttons/js/buttons.colVis.min.js"></script>
  <script src="assets/vendor/chartist/dist/chartist.min.js"></script>
  <script src="assets/vendor/chartist-bar-labels/src/scripts/chartist-bar-labels.js"></script>
  <script src="assets/vendor/chartist-plugin-tooltip/dist/chartist-plugin-tooltip.min.js"></script>
  <script src="assets/vendor/clipboard/dist/clipboard.min.js"></script>


  <!-- JS Nova -->
  <script src="assets/js/hs.core.js"></script>
  <script src="assets/js/components/hs.malihu-scrollbar.js"></script>
  <script src="assets/js/components/hs.side-nav.js"></script>
  <script src="assets/js/components/hs.unfold.js"></script>
  <script src="assets/js/components/hs.select2.js"></script>
  <script src="assets/js/components/hs.chartist-bar.js"></script>
  <script src="assets/js/components/hs.autocomplete.js"></script>
  <script src="assets/js/components/hs.datatables.js"></script>
  <script src="assets/js/components/hs.chartist-area.js"></script>
  <script src="assets/js/components/hs.clipboard.js"></script>
  <script src="assets/js/components/hs.chartist-pie.js"></script>
  <script src="assets/js/components/hs.chartist-donut.js"></script>

  <!-- JS Libraries Init. -->
  <script>
    $(document).on('ready', function() {
      // initialization of custom scroll
      $.HSCore.components.HSMalihuScrollBar.init($('.js-custom-scroll'));

      // initialization of sidebar navigation component
      $.HSCore.components.HSSideNav.init('.js-side-nav');

      // initialization of dropdown component
      $.HSCore.components.HSUnfold.init($('[data-unfold-target]'));

      // initialization of show on type module
      $.HSCore.components.HSSelect2.init('.js-custom-select');


      // initialization of editable table
      $('.js-editable-table tbody tr').editable({
        keyboard: true,
        dblclick: true,
        button: true,
        buttonSelector: '.js-edit',
        maintainWidth: true,
        edit: function(values) {
          $('.js-edit i', this).removeClass('nova-pencil').addClass('nova-check');
          $(this).find('td[data-field] input').addClass('form-control form-control-sm');
        },
        save: function(values) {
          $('.js-edit i', this).removeClass('nova-check').addClass('nova-pencil');
        },
        cancel: function(values) {
          $('.js-edit i', this).removeClass('nova-check').addClass('nova-pencil');
        }
      });

      // initialization of datatables
      $.HSCore.components.HSDatatables.init('.js-datatable');

      $.HSCore.components.HSDatatables.init('.js-search-sorting-col-disable', {
        "columnDefs": [{
          "orderable": true
        }]
      });

      $.HSCore.components.HSDatatables.init('.js-sorting-col-disable', {
        "columnDefs": [{
          "orderable": true
        }]
      });

      $.HSCore.components.HSDatatables.init('.js-datatable-export', {
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copy',
            className: 'btn btn-sm btn-outline-primary mb-4 mr-1',
            init: function(api, node, config) {
              $(node).removeClass('btn-secondary')
            }
          },
          {
            extend: 'excel',
            className: 'btn btn-sm btn-outline-primary mb-4 mr-1',
            init: function(api, node, config) {
              $(node).removeClass('btn-secondary')
            }
          },
          {
            extend: 'csv',
            className: 'btn btn-sm btn-outline-primary mb-4 mr-1',
            init: function(api, node, config) {
              $(node).removeClass('btn-secondary')
            }
          },
          {
            extend: 'pdf',
            className: 'btn btn-sm btn-outline-primary mb-4 mr-1',
            init: function(api, node, config) {
              $(node).removeClass('btn-secondary')
            }
          },
          {
            extend: 'print',
            className: 'btn btn-sm btn-outline-primary mb-4 mr-1',
            init: function(api, node, config) {
              $(node).removeClass('btn-secondary')
            }
          },
        ]
      });
      // initialization of dropdown component
      $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
        unfoldHideOnScroll: false,
        afterOpen: function() {
          // initialization of charts
          $.HSCore.components.HSChartistBar.init('#activity .js-bar-chart');

          setTimeout(function() {
            $('#activity .js-bar-chart').css('opacity', 1);
          }, 100);

          // help function for accordions in hidden block
          $('#headerProjects .accordion-header').on('click', function() {
            // vars
            var target = $(this).data('target');

            $(target).collapse('show');
          });
        }
      });
      // initialization of charts
      $.HSCore.components.HSChartistArea.init('.js-area-chart');
      $.HSCore.components.HSChartistBar.init('.js-bar-chart');
      $.HSCore.components.HSChartistDonut.init('.js-donut-chart');
      // initialization of clipboard
      $.HSCore.components.HSClipboard.init('.js-clipboard');

    });
  </script>
</body>

</html>
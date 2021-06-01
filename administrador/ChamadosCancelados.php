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
                      c.status_idstatus = 7

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
                echo "<a href='DashBoard.php?op=NovoChamado&idtxtIdChamadoHidden=".$linha['idchamado']."&idtxtIdChamado=".$linha['idchamado']."&idtxtAssunto=".$linha['assuntochamado']."&idtxtDesc=".$linha['descricao']."&idtxtUsuAfe=".$linha['usuario_idusuarioafetado']."&idtxtDataInicio=".$linha['datainicioproblema']."&idtxtConclusao=".$linha['dataconclusao']."&idtxtDataCancelamento=".$linha['datacancelamento']."&idtxtNivel=".$linha['nivelcriticidade_idnivelcriticidade']."&idtxtAnalista=".$linha['analistaatendendo']."&idtxtDescConclusao=".$linha['descricaoconclusao']."&idtxtStatus=".$linha['status_idstatus']."'>Selecionar</a>";
                
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
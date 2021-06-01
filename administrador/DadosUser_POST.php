<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['LOGIN'])) { //validou sessão (deve conter em todas telas no início do código, logo após o start)
    echo "<script>alert('Favor fazer login!');</script>";
    echo "<script>location.href='index.php';</script>";
}
require_once "../banco/ConexaoVFBCompany.php";
require_once('../classe/InserirPlanoCarreira.php');
require_once('../classe/Util.php');

$var = new Mysql();
$connect = $var->dbConnect();

$btnAtivar = $_POST['btnAtivar'];

if (isset($_POST['btnAtivar'])) {
    $dadosUpgrade_result = $var->freeRun("
                            SELECT
                                login,
                                up.USUARIO_idUSUARIO,
                                up.idUPGRADE,
                                up.PACOTE_idPACOTE,
                                usu.UPLINE,
                                up.DATAUPGRADE,
                                usu.POSICAOREDEUSUARIO,
                                pct.nome,
                                up.VALORUSD,
                                usu.STATUS_idSTATUS as STATUS,
                                up.TIPO,
                                up.QTDPESSOA
                            FROM 
                                upgrade up
                                
                                inner join usuario usu on 
                                    usu.idUSUARIO =  up.USUARIO_idUSUARIO
                                inner join pacote pct on 
                                    pct.idPACOTE = up.PACOTE_idPACOTE
                            where
                                idUpgrade = " . $btnAtivar . "
                                and up.STATUS_idSTATUS = 1;");
                
    if (mysqli_num_rows($dadosUpgrade_result) == 0) {
        exit;
    }
                                        
    $dadosUpgrade = mysqli_fetch_assoc($dadosUpgrade_result);
    $idtxtLogin = $dadosUpgrade['login'];
    $idtxtIDUsuario = $dadosUpgrade['USUARIO_idUSUARIO'];
    $idtxtUpgrade = $dadosUpgrade['idUPGRADE'];
    $idtxtIDPacote = $dadosUpgrade['PACOTE_idPACOTE'];
    $idtxtUpline = $dadosUpgrade['UPLINE'];
    $idtxtDataUpgrade = $dadosUpgrade['DATAUPGRADE'];
    $idtxtLado = $dadosUpgrade['POSICAOREDEUSUARIO'];
    $idtxtNomePacote = $dadosUpgrade['nome'];
    $idtxtValorUSDpct = $dadosUpgrade['VALORUSD'];
    $statusUsuario = $dadosUpgrade['STATUS'];
    $tipoUsuario  = $_POST['idtxtTipoUsuario'.$btnAtivar];
    $tipoPeriodo = $dadosUpgrade['TIPO'];
    $qtdPessoas = $dadosUpgrade['QTDPESSOA'];

    //*****************************************PACOTE MMN*************************************************** */
    if ($idtxtIDPacote == 1 || $idtxtIDPacote == 4) {
        if ($tipoPeriodo == "M") {
            $periodoVencimento = "adddate(now(), interval 30 day)";
        } elseif ($tipoPeriodo == "S") {
            $periodoVencimento = "adddate(cast(now() as date), interval 6 month)";
        } else {
            $periodoVencimento = "adddate(cast(now() as date), interval 1 year)";
        }

        if ($statusUsuario == 2) {
            $incluso = true;
            $inclusoAgora = false;
        } else {
            $inclusoAgora = true;
            $incluso = false;
        }

        $UpdateUsuario = $var->freeRun("
            UPDATE
                usuario
            SET
                TIPOUSUARIO_idTIPOUSUARIO = 1
            WHERE 
                IDUSUARIO = " . $idtxtIDUsuario . " AND 
                LOGIN = '" . $idtxtLogin . "';
            ");

        $UpdateUsuario = $var->freeRun("
						
						UPDATE
							usuario
						SET
							STATUS_idSTATUS = 2
						WHERE 
							IDUSUARIO = " . $idtxtIDUsuario . " AND 
							LOGIN = '" . $idtxtLogin . "';
			
						");

        $UpdateTipoUsuario = $var->freeRun("
                                    UPDATE
                                    	usuario
                                    SET
                                    	TIPOUSUARIO_idTIPOUSUARIO =  " . $tipoUsuario . "
                                    WHERE 
                                    	IDUSUARIO = " . $idtxtIDUsuario . " AND 
                                    	LOGIN = '" . $idtxtLogin . "';
                                    
                                    ");

        $UpdateFatura = $var->freeRun("
                                    UPDATE
                                        upgrade
                                    SET
                                        STATUS_idSTATUS = 2,
                                        DATAATIVACAO = NOW(),
                                        DATAVENCIMENTO = $periodoVencimento,
                                        DIAMENSALIDADE = ".formartarDiaVencimento()."
                                    WHERE
                                        idupgrade = " . $idtxtUpgrade . " ;
                                    ");
                                

        $UpdateFaturaPendente = $var->freeRun("
                            UPDATE
                                upgrade
                            SET
                                STATUS_idSTATUS = 3,
                                DATACANCELAMENTO = NOW()
                            WHERE
                                USUARIO_idUSUARIO = " . $idtxtIDUsuario . " and
                                idupgrade not in ($idtxtUpgrade)  and
                                        status_idstatus <> 2 ;
                            ");
                                
        $UpdateSaldo = $var->freeRun("
                UPDATE
                    saldousuario
                SET
                    valor = valor + 100
                WHERE
                    USUARIO_idUSUARIO = " . $idtxtIDUsuario . " and
                    tiposaldo = 1;
                ");
                
        //Gerar Saldo Voucher Pacote de 50
        if ($idtxtIDPacote == 4) {
            GerarSaldoVoucher($idtxtIDUsuario);
            $buscarPacoteAntigo = $var->freeRun("
            select 
                idupgrade 
            from 
                upgrade 
            where 
                status_idstatus = 2 and
                pacote_idpacote = 1 and
                dataativacao is not null and
                usuario_idusuario = $idtxtIDUsuario
            ");

            $buscarPacoteAntigoR = mysqli_fetch_row($buscarPacoteAntigo);
            if (mysqli_num_rows($buscarPacoteAntigo) > 0) {
                upgradeParaCinquenta($buscarPacoteAntigoR[0], $idtxtUpgrade, $idtxtIDUsuario);
            }
        }

        // BUSCAR VALOR DO PACOTE DO USUARIO
        $VALOR_PACOTE_USUARIO = $var->freeRun("SELECT 
                                        VALORUSD AS VALOR_PACOTE
                                    FROM 
                                        pacote
                                    WHERE
                                        idPACOTE = " . $idtxtIDPacote . ";
                                    ");

        $result = $VALOR_PACOTE_USUARIO;
        $VALOR_PACOTE_USUARIO = mysqli_fetch_assoc($VALOR_PACOTE_USUARIO);
    
        $buscarRenovacao = $var->freeRun(
            "select
        ifnull(sum(valorusd),0) valorusd
    from 
        renovacao
    where
        status_idstatus = 1 and
        dataativacao is null and
        usuario_idusuario = " . $idtxtIDUsuario
        );
    
        $buscarRenovacaoResult = mysqli_fetch_assoc($buscarRenovacao);
    
        if ($VALOR_PACOTE_USUARIO['VALOR_PACOTE'] > $buscarRenovacaoResult["valorusd"]) {
            $atualizaRenovacao = $var->freeRun(
                "UPDATE
            renovacao
        SET
            STATUS_idSTATUS = 2,
            DATAATIVACAO = NOW()
        WHERE
            status_idstatus = 1 and
            dataativacao is null and
            valorusd < ".$VALOR_PACOTE_USUARIO['VALOR_PACOTE']." and
            USUARIO_idUSUARIO = " . $idtxtIDUsuario . " ;"
            );
        }
    
        //-- ******** INICIO INSERIR BONUS DE INDICACAO DIRETA CASO O USUARIO ESTEJA ATIVO ******************----
        $STATUS_UPLINE = $var->freeRun("SELECT COUNT(*) cnt FROM usuario WHERE TIPOUSUARIO_idTIPOUSUARIO <> 7 AND IDUSUARIO = " . $idtxtUpline . " AND STATUS_idSTATUS = 2");
        $STATUS_UPLINE = mysqli_fetch_assoc($STATUS_UPLINE);

        //-- BUSCAR PORCENTAGEM DE GANHO DE INDICAÇÃO DIRETO DE ACORDO COM O CÓDIGO DO UPLINE
        if ($STATUS_UPLINE['cnt'] > 0) {
    
    //****************************** INICIO INSERINDO BONUS DE INDICAÇÃO DIRETA *************************************
    
            $PORCENTAGEM_INDICACAO_DIRETA_RESULT = $var->freeRun("SELECT 
                                                            (PERCGANHOIDCDIRETA / 100) AS PERCGANHOIDCDIRETA
                                                        FROM 
                                                            upgrade UP
                                                            
                                                            INNER JOIN pacote TE ON
                                                                TE.idPACOTE = UP.PACOTE_idPACOTE
                                                        WHERE
                                                            UP.USUARIO_idUSUARIO = " . $idtxtUpline . " and
                                                            pacote_idpacote in (1,4)
                                                        order by 
                                                            UP.pacote_idpacote desc
                                                        limit 1;");

            $PORCENTAGEM_INDICACAO_DIRETA = mysqli_fetch_assoc($PORCENTAGEM_INDICACAO_DIRETA_RESULT);
            $valor = $PORCENTAGEM_INDICACAO_DIRETA["PERCGANHOIDCDIRETA"] * $idtxtValorUSDpct;

            $INSERIR_PAGAMENTO_PARA_UPLINE = $var->freeRun("INSERT INTO 
                    pagamento
                (
                    VALOR,
                    DATAPAGAMENTO,
                    DETALHES,
                    USUARIO_idUSUARIO,
                    TIPOPAGAMENTO_idTIPOPAGAMENTO,
                    TAXAS_idTAXAS
                )
                VALUES
                (
                    " . $valor . ",
                    NOW(),
                    'Indication bonus from " . $idtxtLogin . " package " . $idtxtNomePacote . "',
                    " . $idtxtUpline . ",
                    1,
                    1
                );");

            $commit = $var->freeRun("commit;");


    
            //****************************** FIM INSERINDO BONUS DE INDICAÇÃO DIRETA *************************************
    
            //****************************** INICIO INSERINDO NA REDE*************************************
    
            //pacotes de investidor não são inseridos na rede
            while (!$incluso) {
                $indicadoPosicaoConfigurada = $var->freeRun("SELECT 
                    *
                FROM 
                    rede
                WHERE
                    UPLINE = " . $idtxtUpline . "
                    AND POSICAOREDE = '" . $idtxtLado . "';
                ");
                $indicadoPosicaoConfiguradaResult = mysqli_fetch_assoc($indicadoPosicaoConfigurada);
    
                if ($indicadoPosicaoConfiguradaResult == null) {
                    $insert = $var->freeRun("INSERT INTO 
                            rede
                        (
                            POSICAOREDE,
                            UPLINE,
                            USUARIO_idUSUARIO,
                            USUARIO_LOGIN
                        )
                        VALUES
                        (
                            '" . $idtxtLado . "',
                            " . $idtxtUpline . ",
                            " . $idtxtIDUsuario . ",
                            '" . $idtxtLogin . "'
                        );");
                    $incluso = true;
                    break;
                } else {
                    $idtxtUpline = $indicadoPosicaoConfiguradaResult["USUARIO_idUSUARIO"];
                }
            }

    
            //****************************** FIM INSERINDO NA REDE*************************************
        }

        //****************************** INICIO MULTINIVEL*************************************
          
        //INSERIR PONTUACAO MULTINIVEL DE ACORDO COM O PACOTE DA PESSOA
        $nivel = 1;
        // buscar upline para inserir binario
        $result_uplineMultiNivel = $var->freeRun("SELECT 
                                                UPLINE
                                            FROM 
                                                rede 
                                            WHERE 
                                                usuario_idusuario = " . $idtxtIDUsuario . ";
                                        ");
        $result_uplineMultiNivelassoc = mysqli_fetch_assoc($result_uplineMultiNivel);
        $uplineMultiNivel =  $result_uplineMultiNivelassoc['UPLINE'];

        while ($nivel <= 9 && $uplineMultiNivel != 0) :
        // buscar nivel do usuario na rede deste upline
        $uplineMultiNivel3 = $var->freeRun("SELECT 
                                                nivel
                                            FROM 
                                                usuarionivel
                                            WHERE 
                                                IdUpline = " . $uplineMultiNivel . "
                                                AND idUsuario = ".$idtxtIDUsuario.";");
        
        $result_nivel = mysqli_fetch_assoc($uplineMultiNivel3);
        $nivel = $result_nivel['nivel'];
            
        // verificar se usuario esta ativo
        $usuStatus = $var->freeRun("SELECT 
                                        *
                                    FROM 
                                        usuario 
                                    WHERE 
                                        idusuario = ".$uplineMultiNivel." ;");
        
        $usuStatus = mysqli_fetch_assoc($usuStatus);
        
        $usuCount = $var->freeRun("SELECT 
                                        count(idusuario) qtddiretos
                                    FROM 
                                        usuario 
                                    WHERE 
                                        upline = ".$uplineMultiNivel." and
                                        status_idstatus = 2;");
        
        $usuCount = mysqli_fetch_assoc($usuCount);
        
        //VERIFICANDO O PERCENTUAL DE GANHO DE ACORDO COM O PACOTE DA PESSOA
        switch ($nivel) {
            case 2:
                $porcentagemNivel = 0.08;
                break;
            case 3:
                $porcentagemNivel = 0.04;
                break;
            case 4:
                $porcentagemNivel = 0.03;
                break;
            case 5:
                $porcentagemNivel = 0.03;
                break;
            case 6:
                $porcentagemNivel = 0.04;
                break;
            case 7:
                $porcentagemNivel = 0.05;
                break;
            case 8:
                $porcentagemNivel = 0.05;
                break;
            case 9:
                $porcentagemNivel = 0.05;
                break;
        }
        
        //VERIFICANDO SE ESTA QUALIFICADO DE ACORDO COM O NIVEL
        if (
             ($usuCount['qtddiretos'] >= 2 && $nivel == 2)||
             ($usuCount['qtddiretos'] >= 2 && $nivel == 3)||
             ($usuCount['qtddiretos'] >= 3 && $nivel == 4)||
             ($usuCount['qtddiretos'] >= 4 && $nivel == 5)||
             ($usuCount['qtddiretos'] >= 5 && $nivel == 6)||
             ($usuCount['qtddiretos'] >= 6 && $nivel == 7)||
             ($usuCount['qtddiretos'] >= 7 && $nivel == 8)||
             ($usuCount['qtddiretos'] >= 9 && $nivel == 9)
             ) {
            //NÃO INSERE PONTUAÇÃO PARA TIPO DE USUARIO 3 (NÃO GERA PONTUACAO NEM GANHO DIÁRIO)
            // 8 - INDIRECT INDICATION
            $verificarTipoUsuario = $var->freeRun("select TIPOUSUARIO_idTIPOUSUARIO from usuario where idusuario =".$uplineMultiNivel);
            $verificarTipoUsuario = mysqli_fetch_row($verificarTipoUsuario);

            if ($usuStatus['STATUS_idSTATUS'] == 2 && !empty($nivel) && $nivel < 10 && ($verificarTipoUsuario[0] == 1 || $verificarTipoUsuario[0] == 5)) {
                $INSERIR_PAGAMENTO_PARA_UPLINE = $var->freeRun("
                    INSERT INTO 
                            pagamento
                        (
                            VALOR,
                            DATAPAGAMENTO,
                            DETALHES,
                            USUARIO_idUSUARIO,
                            TIPOPAGAMENTO_idTIPOPAGAMENTO,
                            TAXAS_idTAXAS
                        )
                        VALUES
                        (
                            " .($idtxtValorUSDpct * $porcentagemNivel). ",
                            NOW(),
                            'Referral bonus ".$nivel."th level " . $idtxtLogin . " package " . $idtxtNomePacote . "',
                            " . $uplineMultiNivel . ",
                            8,
                            1
                        );");
            }
        }
        // buscar upline para inserir binario
        $result_uplineMultiNivel = $var->freeRun("SELECT 
                                                        UPLINE
                                                    FROM 
                                                        rede 
                                                    WHERE 
                                                        usuario_idusuario =  " . $uplineMultiNivel . ";");
        $result_uplineMultiNivel = mysqli_fetch_assoc($result_uplineMultiNivel);
        $uplineMultiNivel = $result_uplineMultiNivel['UPLINE'];
    
        endwhile;
                
                
        //****************************** FIM MULTINIVEL*************************************


        //****************************** INICIO INSERINDO PLANO DE CARREIRA USUARIO *************************************
            
        try {
                
        // buscar upline para inserir binario
            $result_UPLINE2 = $var->freeRun("SELECT 
                                            idUSUARIO,
                                            UPLINE,
                                            'U'
                                        FROM 
                                            usuario 
                                        WHERE 
                                            idUSUARIO = " .  $idtxtIDUsuario . ";
                                        ");
                        
            $UPLINE_2 = mysqli_fetch_assoc($result_UPLINE2);
            $countnivel = 1;
        
            //inserir pontuação para binário
            while ($UPLINE_2['UPLINE'] <> 0 && $countnivel < 10) :
            if ($inclusoAgora) {
                $resultBinario = $var->freeRun("UPDATE 
                                                pontuacaobinaria 
                                            SET 
                                                qtdIndicados = qtdIndicados + 1
                                            WHERE idUsuario = ".$UPLINE_2['UPLINE']."
                                                AND lado = 'U';");
                $commit = $var->freeRun("commit;");
                
                //usuario_idusuario que recebeu
                $inserindoDetalhePontos = $var->freeRun("INSERT INTO 
                                                        `pontuacaodetalhe`
                                                        (
                                                        `detalhe`,
                                                        `usuario_idusuario`
                                                        )
                                                        VALUES
                                                        (
                                                        'Usuário código ".$UPLINE_2['UPLINE']." recebeu +1 indicado',
                                                        ".$UPLINE_2['UPLINE']."
                                                        );");
            }
               
            $resultBinario = $var->freeRun("UPDATE 
                                            pontuacaobinaria 
                                        SET 
                                            pontuacao = pontuacao + $idtxtValorUSDpct
                                        WHERE idUsuario = ".$UPLINE_2['UPLINE']."
                                            AND lado = 'U';");
    
            $commit = $var->freeRun("commit;");
            
            //usuario_idusuario que recebeu
            $inserindoDetalhePontos = $var->freeRun("INSERT INTO 
                                                    `pontuacaodetalhe`
                                                    (
                                                    `detalhe`,
                                                    `usuario_idusuario`
                                                    )
                                                    VALUES
                                                    (
                                                    'Usuário código ".$UPLINE_2['UPLINE']." recebeu +20 pontos',
                                                    ".$UPLINE_2['UPLINE']."
                                                    );");
    
            $resultQualificacaoE = $var->freeRun("SELECT
                                                pontuacao pontos
                                            FROM 
                                                pontuacaobinaria
                                            WHERE 
                                                idUsuario = ".$UPLINE_2['UPLINE']." and
                                                lado = 'U'
                                            group by idUsuario;");
                                    
            $resultQualificacaoE = mysqli_fetch_assoc($resultQualificacaoE);
            $ptsQualif = $resultQualificacaoE['pontos'];
        
            //*****ATUALIZAR GRADUAÇÃO DO USUÁRIO
            
            //InserirPlanoCarreira($ptsQualif, $UPLINE_2['UPLINE']);
            
            //*****
      
            // buscar upline para inserir binario
            $result_UPLINE2 = $var->freeRun("SELECT 
                                        idUSUARIO,
                                        UPLINE,
                                        'U'
                                    FROM 
                                        usuario  
                                    WHERE 
                                        idUSUARIO = " .  $UPLINE_2['UPLINE'] . ";
                                    ");
                            
            $UPLINE_2 = mysqli_fetch_assoc($result_UPLINE2);
            $countnivel = $countnivel + 1;
    
            endwhile;
        } catch (Exception $e) {
            error_log('Caught exception: '.$e->getMessage());
        }


        //****************************** FIM INSERINDO PLANO DE CARREIRA USUARIO *************************************
 
        if ($result == true) {
            header("Location: DashBoard.php?op=InvoicePayment&tipo=sucess&bold=Success&message=" . $idtxtLogin . " successfully activated!");
        } else {
            header("Location: DashBoard.php?op=InvoicePayment&tipo=danger&bold=Attention&message=Unable to activate " . $idtxtLogin . "!");
        }
    }
    //********************************************************************************************************* */
    //*****************************************PACOTE FAMILY*************************************************** */
    //********************************************************************************************************* */
    elseif ($idtxtIDPacote == 2) {
        if ($tipoPeriodo == "M") {
            $periodoVencimento = "adddate(now(), interval 30 day)";
        } elseif ($tipoPeriodo == "S") {
            $periodoVencimento = "adddate(cast(now() as date), interval 6 month)";
        } else {
            $periodoVencimento = "adddate(cast(now() as date), interval 1 year)";
        }

        if ($statusUsuario == 2) {
            $incluso = true;
            $inclusoAgora = false;
        } else {
            $inclusoAgora = true;
            $incluso = false;
        }

        //Tipo Cliente
        $UpdateUsuario = $var->freeRun("
                                    UPDATE
                                        usuario
                                    SET
                                        TIPOUSUARIO_idTIPOUSUARIO = 8
                                    WHERE 
                                        IDUSUARIO = " . $idtxtIDUsuario . " AND 
                                        LOGIN = '" . $idtxtLogin . "';
");

        $UpdateUsuario = $var->freeRun("
                                    UPDATE
                                        usuario
                                    SET
                                        STATUS_idSTATUS = 2
                                    WHERE 
                                        IDUSUARIO = " . $idtxtIDUsuario . " AND 
                                        LOGIN = '" . $idtxtLogin . "';
");

        $UpdateFatura = $var->freeRun("
                                    UPDATE
                                        upgrade
                                    SET
                                        STATUS_idSTATUS = 2,
                                        DATAATIVACAO = NOW(),
                                        DATAVENCIMENTO = $periodoVencimento,
                                        DIAMENSALIDADE = ".formartarDiaVencimento()."
                                    WHERE
                                        USUARIO_idUSUARIO = " . $idtxtIDUsuario . " and
                                        idupgrade = $idtxtUpgrade
                                        ;
                                ");

        $UpdateFaturaPendente = $var->freeRun("
                                            UPDATE
                                                upgrade
                                            SET
                                                STATUS_idSTATUS = 3,
                                                DATACANCELAMENTO = NOW()
                                            WHERE
                                                USUARIO_idUSUARIO = " . $idtxtIDUsuario . " and
                                                idupgrade not in ($idtxtUpgrade)  and
                                                status_idstatus <> 2 ;
                                            ");

        //Credito Disponibilizado por pessoa
        $UpdateSaldo = $var->freeRun("
                                UPDATE
                                    saldousuario
                                SET
                                    valor = valor + (100 * $qtdPessoas)
                                WHERE
                                    USUARIO_idUSUARIO = " . $idtxtIDUsuario . " and
                                    tiposaldo = 1;
                                ");
                                
        $verificarFamilia = $var->freeRun("
        select
            idfamilia
        from 
            familia
        where
            usuario_idusuario = $idtxtIDUsuario and
            status_idstatus = 2
        ");

        if (mysqli_num_rows($verificarFamilia) == 0) {
            $inseriFamilia = $var->freeRun("
            INSERT INTO `familia`
            (
            `usuario_idusuario`,
            `status_idstatus`)
            VALUES
            (
                $idtxtIDUsuario,
                2);            
            ");
        }

        $buscarFamilia = $var->freeRun("
        select
            idfamilia
        from 
            familia
        where
            usuario_idusuario = $idtxtIDUsuario and
            status_idstatus = 2
        order by 
            idfamilia desc
        limit 1
        ");
            
        $buscarFamiliaResult = mysqli_fetch_assoc($buscarFamilia);
        $idfamilia = $buscarFamiliaResult['idfamilia'];

        for ($inmbm = 0; $inmbm < ($qtdPessoas - 1); $inmbm++) {
            $inserirMembroFamilia = $var->freeRun("
                INSERT INTO `familiamembro`
                (
                `familia_idfamilia`,
                `status_idstatus`)
                VALUES
                (
                    $idfamilia,
                    1
                );
            ");
            
            error_log("
                INSERT INTO `familiamembro`
                (
                `familia_idfamilia`,
                `status_idstatus`)
                VALUES
                (
                    $idfamilia,
                    1
                );
            ");
        }

        // BUSCAR VALOR DO PACOTE DO USUARIO
        $VALOR_PACOTE_USUARIO = $var->freeRun("SELECT 
                VALORUSD AS VALOR_PACOTE
            FROM 
                pacote
            WHERE
                idPACOTE = " . $idtxtIDPacote . ";
            ");

        $result = $VALOR_PACOTE_USUARIO;
        $VALOR_PACOTE_USUARIO = mysqli_fetch_assoc($VALOR_PACOTE_USUARIO);

        $buscarRenovacao = $var->freeRun(
            "select
        ifnull(sum(valorusd),0) valorusd
        from 
        renovacao
        where
        status_idstatus = 1 and
        dataativacao is null and
        usuario_idusuario = " . $idtxtIDUsuario
        );

        $buscarRenovacaoResult = mysqli_fetch_assoc($buscarRenovacao);

        if ($VALOR_PACOTE_USUARIO['VALOR_PACOTE'] > $buscarRenovacaoResult["valorusd"]) {
            $atualizaRenovacao = $var->freeRun(
                "UPDATE
            renovacao
            SET
            STATUS_idSTATUS = 2,
            DATAATIVACAO = NOW()
            WHERE
            status_idstatus = 1 and
            dataativacao is null and
            valorusd < ".$VALOR_PACOTE_USUARIO['VALOR_PACOTE']." and
            USUARIO_idUSUARIO = " . $idtxtIDUsuario . " ;"
            );
        }

        //-- ******** INICIO INSERIR BONUS DE INDICACAO DIRETA CASO O USUARIO ESTEJA ATIVO ******************----
        $STATUS_UPLINE = $var->freeRun("SELECT 
                    COUNT(*) cnt
                FROM
                    usuario usu
                    
                    inner join upgrade up on 
                        up.usuario_idusuario = usu.idusuario 
                WHERE
                    up.pacote_idpacote in (1,4) 
                    and up.dataativacao is not null
                    and up.status_idstatus = 2
                    and TIPOUSUARIO_idTIPOUSUARIO <> 7
                    AND IDUSUARIO = " . $idtxtUpline);
                    
        $STATUS_UPLINE = mysqli_fetch_assoc($STATUS_UPLINE);

        //-- BUSCAR PORCENTAGEM DE GANHO DE INDICAÇÃO DIRETO DE ACORDO COM O CÓDIGO DO UPLINE
        if ($STATUS_UPLINE['cnt'] > 0) {

//****************************** INICIO INSERINDO BONUS DE INDICAÇÃO DIRETA *************************************

            $PORCENTAGEM_INDICACAO_DIRETA_RESULT = $var->freeRun("SELECT 
                                    (PERCGANHOIDCDIRETA / 100) AS PERCGANHOIDCDIRETA
                                FROM 
                                    pacote
                                WHERE
                                   idpacote = $idtxtIDPacote
                                limit 1;");

            $PORCENTAGEM_INDICACAO_DIRETA = mysqli_fetch_assoc($PORCENTAGEM_INDICACAO_DIRETA_RESULT);
            $valor = $PORCENTAGEM_INDICACAO_DIRETA["PERCGANHOIDCDIRETA"] * $idtxtValorUSDpct;

            $INSERIR_PAGAMENTO_PARA_UPLINE = $var->freeRun("INSERT INTO 
                                                        pagamento
                                                        (
                                                        VALOR,
                                                        DATAPAGAMENTO,
                                                        DETALHES,
                                                        USUARIO_idUSUARIO,
                                                        TIPOPAGAMENTO_idTIPOPAGAMENTO,
                                                        TAXAS_idTAXAS
                                                        )
                                                        VALUES
                                                        (
                                                        " . $valor . ",
                                                        NOW(),
                                                        'Indication bonus from " . $idtxtLogin . " package " . $idtxtNomePacote . "',
                                                        " . $idtxtUpline . ",
                                                        1,
                                                        1
                                                        );");


            $commit = $var->freeRun("commit;");



            //****************************** FIM INSERINDO BONUS DE INDICAÇÃO DIRETA *************************************
        }

        //****************************** INICIO MULTINIVEL*************************************

        //INSERIR PONTUACAO MULTINIVEL DE ACORDO COM O PACOTE DA PESSOA
        $nivel = 1;
        // buscar upline para inserir binario
        $result_uplineMultiNivel = $var->freeRun("SELECT 
                        UPLINE
                    FROM 
                        usuario 
                    WHERE 
                        idusuario = " . $idtxtIDUsuario . ";
                ");
        $result_uplineMultiNivelassoc = mysqli_fetch_assoc($result_uplineMultiNivel);
        $uplineMultiNivel =  $result_uplineMultiNivelassoc['UPLINE'];

        while ($nivel <= 9 && $uplineMultiNivel != 0) :
// buscar nivel do usuario na rede deste upline
$uplineMultiNivel3 = $var->freeRun("SELECT 
                        nivel
                    FROM 
                        usuarionivel
                    WHERE 
                        IdUpline = " . $uplineMultiNivel . "
                        AND idUsuario = ".$idtxtIDUsuario.";");

        $result_nivel = mysqli_fetch_assoc($uplineMultiNivel3);
        $nivel = $result_nivel['nivel'];

        // verificar se usuario esta ativo
        $usuStatus = $var->freeRun("SELECT 
                *
            FROM 
                usuario 
            WHERE 
                idusuario = ".$uplineMultiNivel." ;");

        $usuStatus = mysqli_fetch_assoc($usuStatus);

        $usuCount = $var->freeRun("SELECT 
                count(idusuario) qtddiretos
            FROM 
                usuario 
            WHERE 
                upline = ".$uplineMultiNivel." and
                status_idstatus = 2;");

        $usuCount = mysqli_fetch_assoc($usuCount);

        //VERIFICANDO O PERCENTUAL DE GANHO DE ACORDO COM O PACOTE DA PESSOA
        switch ($nivel) {
        case 2:
        $porcentagemNivel = 0.06;
        break;
        case 3:
        $porcentagemNivel = 0.03;
        break;
        case 4:
        $porcentagemNivel = 0.03;
        break;
        case 5:
        $porcentagemNivel = 0.03;
        break;
        case 6:
        $porcentagemNivel = 0.03;
        break;
        case 7:
        $porcentagemNivel = 0.03;
        break;
        case 8:
        $porcentagemNivel = 0.03;
        break;
        case 9:
        $porcentagemNivel = 0.03;
        break;
        }

        //VERIFICANDO SE ESTA QUALIFICADO DE ACORDO COM O NIVEL
        if (
        ($usuCount['qtddiretos'] >= 2 && $nivel == 2)||
        ($usuCount['qtddiretos'] >= 2 && $nivel == 3)||
        ($usuCount['qtddiretos'] >= 3 && $nivel == 4)||
        ($usuCount['qtddiretos'] >= 4 && $nivel == 5)||
        ($usuCount['qtddiretos'] >= 5 && $nivel == 6)||
        ($usuCount['qtddiretos'] >= 6 && $nivel == 7)||
        ($usuCount['qtddiretos'] >= 7 && $nivel == 8)||
        ($usuCount['qtddiretos'] >= 9 && $nivel == 9)
        ) {
            //NÃO INSERE PONTUAÇÃO PARA TIPO DE USUARIO 3 (NÃO GERA PONTUACAO NEM GANHO DIÁRIO)
            // 8 - INDIRECT INDICATION
            $verificarTipoUsuario = $var->freeRun("select TIPOUSUARIO_idTIPOUSUARIO from usuario where idusuario =".$uplineMultiNivel);
            $verificarTipoUsuario = mysqli_fetch_row($verificarTipoUsuario);

            if ($usuStatus['STATUS_idSTATUS'] == 2 && !empty($nivel) && $nivel < 10 && ($verificarTipoUsuario[0] == 1 || $verificarTipoUsuario[0] == 5)) {
                $INSERIR_PAGAMENTO_PARA_UPLINE = $var->freeRun("
                                                        INSERT INTO 
                                                            pagamento
                                                        (
                                                            VALOR,
                                                            DATAPAGAMENTO,
                                                            DETALHES,
                                                            USUARIO_idUSUARIO,
                                                            TIPOPAGAMENTO_idTIPOPAGAMENTO,
                                                            TAXAS_idTAXAS
                                                        )
                                                        VALUES
                                                        (
                                                            " .($idtxtValorUSDpct * $porcentagemNivel). ",
                                                            NOW(),
                                                            'Referral bonus ".$nivel."th level " . $idtxtLogin . " package " . $idtxtNomePacote . "',
                                                            " . $uplineMultiNivel . ",
                                                            8,
                                                            1
                                                        );");
            }
        }
        // buscar upline para inserir binario
        $result_uplineMultiNivel = $var->freeRun("SELECT 
                        UPLINE
                    FROM 
                        usuario 
                    WHERE 
                        idusuario =  " . $uplineMultiNivel . ";");
        $result_uplineMultiNivel = mysqli_fetch_assoc($result_uplineMultiNivel);
        $uplineMultiNivel = $result_uplineMultiNivel['UPLINE'];

        endwhile;


        //****************************** FIM MULTINIVEL*************************************


        //****************************** INICIO INSERINDO PLANO DE CARREIRA USUARIO *************************************

        try {

// buscar upline para inserir binario
            $result_UPLINE2 = $var->freeRun("SELECT 
            idUSUARIO,
            UPLINE,
            'U'
        FROM 
            usuario 
        WHERE 
            idUSUARIO = " .  $idtxtIDUsuario . ";
        ");

            $UPLINE_2 = mysqli_fetch_assoc($result_UPLINE2);
            $countnivel = 1;
            //inserir pontuação para binário
            while ($UPLINE_2['UPLINE'] <> 0 && $countnivel < 10) :
if ($inclusoAgora) {
    $resultBinario = $var->freeRun("UPDATE 
                pontuacaobinaria 
            SET 
                qtdIndicados = qtdIndicados + 1
            WHERE idUsuario = ".$UPLINE_2['UPLINE']."
                AND lado = 'U';");
    $commit = $var->freeRun("commit;");
}

            $resultBinario = $var->freeRun("UPDATE 
                pontuacaobinaria 
            SET 
                pontuacao = pontuacao + $idtxtValorUSDpct
            WHERE idUsuario = ".$UPLINE_2['UPLINE']."
                AND lado = 'U';");

            $commit = $var->freeRun("commit;");

            $resultQualificacaoE = $var->freeRun("SELECT
                    pontuacao pontos
                FROM 
                    pontuacaobinaria
                WHERE 
                    idUsuario = ".$UPLINE_2['UPLINE']." and
                    lado = 'U'
                group by idUsuario;");
        
            $resultQualificacaoE = mysqli_fetch_assoc($resultQualificacaoE);
            $ptsQualif = $resultQualificacaoE['pontos'];

            //*****ATUALIZAR GRADUAÇÃO DO USUÁRIO

            //InserirPlanoCarreira($ptsQualif, $UPLINE_2['UPLINE']);

            //*****

            // buscar upline para inserir binario
            $result_UPLINE2 = $var->freeRun("SELECT 
            idUSUARIO,
            UPLINE,
            'U'
        FROM 
            usuario  
        WHERE 
            idUSUARIO = " .  $UPLINE_2['UPLINE'] . ";
        ");

            $UPLINE_2 = mysqli_fetch_assoc($result_UPLINE2);
            $countnivel = $countnivel + 1;

            endwhile;
        } catch (Exception $e) {
            error_log('Caught exception: '.$e->getMessage());
        }

        //****************************** FIM INSERINDO PLANO DE CARREIRA USUARIO *************************************
    }
    //*****************************************PACOTE SINGLE*************************************************** */
    elseif ($idtxtIDPacote == 3 || $idtxtIDPacote == 5) {
        if ($tipoPeriodo == "M") {
            $periodoVencimento = "adddate(now(), interval 30 day)";
        } elseif ($tipoPeriodo == "S") {
            $periodoVencimento = "adddate(cast(now() as date), interval 6 month)";
        } else {
            $periodoVencimento = "adddate(cast(now() as date), interval 1 year)";
        }

        if ($statusUsuario == 2) {
            $incluso = true;
            $inclusoAgora = false;
        } else {
            $inclusoAgora = true;
            $incluso = false;
        }

        //Tipo Cliente
        $UpdateUsuario = $var->freeRun("
    UPDATE
        usuario
    SET
        TIPOUSUARIO_idTIPOUSUARIO = 8
    WHERE 
        IDUSUARIO = " . $idtxtIDUsuario . " AND 
        LOGIN = '" . $idtxtLogin . "';
    ");
        
        $UpdateUsuario = $var->freeRun("
            UPDATE
                usuario
            SET
                STATUS_idSTATUS = 2
            WHERE 
                IDUSUARIO = " . $idtxtIDUsuario . " AND 
                LOGIN = '" . $idtxtLogin . "';
            ");

        $UpdateFatura = $var->freeRun("
            UPDATE
                upgrade
            SET
                STATUS_idSTATUS = 2,
                DATAATIVACAO = NOW(),
                DATAVENCIMENTO = $periodoVencimento,
                DIAMENSALIDADE = ".formartarDiaVencimento()."
            WHERE
                idupgrade = " . $idtxtUpgrade . " ;
            ");

        $UpdateFaturaPendente = $var->freeRun("
            UPDATE
                upgrade
            SET
                STATUS_idSTATUS = 3,
                DATACANCELAMENTO = NOW()
            WHERE
                USUARIO_idUSUARIO = " . $idtxtIDUsuario . " and
                idupgrade not in ($idtxtUpgrade)  and
                                        status_idstatus <> 2 ;
            ");
        
        //Credito Disponibilizado por pessoa
        $UpdateSaldo = $var->freeRun("
            UPDATE
                saldousuario
            SET
                valor = valor + 100
            WHERE
                USUARIO_idUSUARIO = " . $idtxtIDUsuario . " and
                tiposaldo = 1;
            ");
        
        // BUSCAR VALOR DO PACOTE DO USUARIO
        $VALOR_PACOTE_USUARIO = $var->freeRun("SELECT 
                                    VALORUSD AS VALOR_PACOTE
                                FROM 
                                    pacote
                                WHERE
                                    idPACOTE = " . $idtxtIDPacote . ";
                                ");

        $result = $VALOR_PACOTE_USUARIO;
        $VALOR_PACOTE_USUARIO = mysqli_fetch_assoc($VALOR_PACOTE_USUARIO);

        $buscarRenovacao = $var->freeRun(
            "select
            ifnull(sum(valorusd),0) valorusd
        from 
            renovacao
        where
            status_idstatus = 1 and
            dataativacao is null and
            usuario_idusuario = " . $idtxtIDUsuario
        );

        $buscarRenovacaoResult = mysqli_fetch_assoc($buscarRenovacao);

        if ($VALOR_PACOTE_USUARIO['VALOR_PACOTE'] > $buscarRenovacaoResult["valorusd"]) {
            $atualizaRenovacao = $var->freeRun(
                "UPDATE
                renovacao
            SET
                STATUS_idSTATUS = 2,
                DATAATIVACAO = NOW()
            WHERE
                status_idstatus = 1 and
                dataativacao is null and
                valorusd < ".$VALOR_PACOTE_USUARIO['VALOR_PACOTE']." and
                USUARIO_idUSUARIO = " . $idtxtIDUsuario . " ;"
            );
        }

        //-- ******** INICIO INSERIR BONUS DE INDICACAO DIRETA CASO O USUARIO ESTEJA ATIVO ******************----
        $STATUS_UPLINE = $var->freeRun("SELECT 
                                        COUNT(*) cnt
                                    FROM
                                        usuario usu
                                        
                                        inner join upgrade up on 
                                            up.usuario_idusuario = usu.idusuario 
                                    WHERE
                                        up.pacote_idpacote in (1,4) 
                                        and up.dataativacao is not null
                                        and up.status_idstatus = 2
                                        and TIPOUSUARIO_idTIPOUSUARIO <> 7
                                        AND IDUSUARIO = " . $idtxtUpline);
                                        
        $STATUS_UPLINE = mysqli_fetch_assoc($STATUS_UPLINE);

        //-- BUSCAR PORCENTAGEM DE GANHO DE INDICAÇÃO DIRETO DE ACORDO COM O CÓDIGO DO UPLINE
        if ($STATUS_UPLINE['cnt'] > 0) {

//****************************** INICIO INSERINDO BONUS DE INDICAÇÃO DIRETA *************************************

            $PORCENTAGEM_INDICACAO_DIRETA_RESULT = $var->freeRun("SELECT 
                                                        (PERCGANHOIDCDIRETA / 100) AS PERCGANHOIDCDIRETA
                                                    FROM 
                                                        pacote
                                                    WHERE
                                                       idpacote = $idtxtIDPacote
                                                    limit 1;");

            $PORCENTAGEM_INDICACAO_DIRETA = mysqli_fetch_assoc($PORCENTAGEM_INDICACAO_DIRETA_RESULT);
            $valor = $PORCENTAGEM_INDICACAO_DIRETA["PERCGANHOIDCDIRETA"] * $idtxtValorUSDpct;

            $INSERIR_PAGAMENTO_PARA_UPLINE = $var->freeRun("INSERT INTO 
                pagamento
            (
                VALOR,
                DATAPAGAMENTO,
                DETALHES,
                USUARIO_idUSUARIO,
                TIPOPAGAMENTO_idTIPOPAGAMENTO,
                TAXAS_idTAXAS
            )
            VALUES
            (
                " . $valor . ",
                NOW(),
                'Indication bonus from " . $idtxtLogin . " package " . $idtxtNomePacote . "',
                " . $idtxtUpline . ",
                1,
                1
            );");
            

            $commit = $var->freeRun("commit;");



            //****************************** FIM INSERINDO BONUS DE INDICAÇÃO DIRETA *************************************
        }

        //****************************** INICIO MULTINIVEL*************************************
      
        //INSERIR PONTUACAO MULTINIVEL DE ACORDO COM O PACOTE DA PESSOA
        $nivel = 1;
        // buscar upline para inserir binario
        $result_uplineMultiNivel = $var->freeRun("SELECT 
                                                UPLINE
                                            FROM 
                                                usuario 
                                            WHERE 
                                                idusuario = " . $idtxtIDUsuario . ";
                                    ");
        $result_uplineMultiNivelassoc = mysqli_fetch_assoc($result_uplineMultiNivel);
        $uplineMultiNivel =  $result_uplineMultiNivelassoc['UPLINE'];

        while ($nivel <= 9 && $uplineMultiNivel != 0) :
    // buscar nivel do usuario na rede deste upline
    $uplineMultiNivel3 = $var->freeRun("SELECT 
                                            nivel
                                        FROM 
                                            usuarionivel
                                        WHERE 
                                            IdUpline = " . $uplineMultiNivel . "
                                            AND idUsuario = ".$idtxtIDUsuario.";");
    
        $result_nivel = mysqli_fetch_assoc($uplineMultiNivel3);
        $nivel = $result_nivel['nivel'];
        
        // verificar se usuario esta ativo
        $usuStatus = $var->freeRun("SELECT 
                                    *
                                FROM 
                                    usuario 
                                WHERE 
                                    idusuario = ".$uplineMultiNivel." ;");
    
        $usuStatus = mysqli_fetch_assoc($usuStatus);
    
        $usuCount = $var->freeRun("SELECT 
                                    count(idusuario) qtddiretos
                                FROM 
                                    usuario 
                                WHERE 
                                    upline = ".$uplineMultiNivel." and
                                    status_idstatus = 2;");
    
        $usuCount = mysqli_fetch_assoc($usuCount);
    
        //VERIFICANDO O PERCENTUAL DE GANHO DE ACORDO COM O PACOTE DA PESSOA
        switch ($nivel) {
        case 2:
            $porcentagemNivel = 0.06;
            break;
        case 3:
            $porcentagemNivel = 0.03;
            break;
        case 4:
            $porcentagemNivel = 0.03;
            break;
        case 5:
            $porcentagemNivel = 0.03;
            break;
        case 6:
            $porcentagemNivel = 0.03;
            break;
        case 7:
            $porcentagemNivel = 0.03;
            break;
        case 8:
            $porcentagemNivel = 0.03;
            break;
        case 9:
            $porcentagemNivel = 0.03;
            break;
    }
    
        //VERIFICANDO SE ESTA QUALIFICADO DE ACORDO COM O NIVEL
        if (
         ($usuCount['qtddiretos'] >= 2 && $nivel == 2)||
         ($usuCount['qtddiretos'] >= 2 && $nivel == 3)||
         ($usuCount['qtddiretos'] >= 3 && $nivel == 4)||
         ($usuCount['qtddiretos'] >= 4 && $nivel == 5)||
         ($usuCount['qtddiretos'] >= 5 && $nivel == 6)||
         ($usuCount['qtddiretos'] >= 6 && $nivel == 7)||
         ($usuCount['qtddiretos'] >= 7 && $nivel == 8)||
         ($usuCount['qtddiretos'] >= 9 && $nivel == 9)
         ) {
            //NÃO INSERE PONTUAÇÃO PARA TIPO DE USUARIO 3 (NÃO GERA PONTUACAO NEM GANHO DIÁRIO)
            // 8 - INDIRECT INDICATION
            $verificarTipoUsuario = $var->freeRun("select TIPOUSUARIO_idTIPOUSUARIO from usuario where idusuario =".$uplineMultiNivel);
            $verificarTipoUsuario = mysqli_fetch_row($verificarTipoUsuario);

            if ($usuStatus['STATUS_idSTATUS'] == 2 && !empty($nivel) && $nivel < 10 && ($verificarTipoUsuario[0] == 1 || $verificarTipoUsuario[0] == 5)) {
                $INSERIR_PAGAMENTO_PARA_UPLINE = $var->freeRun("
                INSERT INTO 
                        pagamento
                    (
                        VALOR,
                        DATAPAGAMENTO,
                        DETALHES,
                        USUARIO_idUSUARIO,
                        TIPOPAGAMENTO_idTIPOPAGAMENTO,
                        TAXAS_idTAXAS
                    )
                    VALUES
                    (
                        " .($idtxtValorUSDpct * $porcentagemNivel). ",
                        NOW(),
                        'Referral bonus ".$nivel."th level " . $idtxtLogin . " package " . $idtxtNomePacote . "',
                        " . $uplineMultiNivel . ",
                        8,
                        1
                    );");
            }
        }
        // buscar upline para inserir binario
        $result_uplineMultiNivel = $var->freeRun("SELECT 
                                                    UPLINE
                                                FROM 
                                                    usuario 
                                                WHERE 
                                                    idusuario =  " . $uplineMultiNivel . ";");
        $result_uplineMultiNivel = mysqli_fetch_assoc($result_uplineMultiNivel);
        $uplineMultiNivel = $result_uplineMultiNivel['UPLINE'];

        endwhile;
            
            
        //****************************** FIM MULTINIVEL*************************************


        //****************************** INICIO INSERINDO PLANO DE CARREIRA USUARIO *************************************
        
        try {
            
// buscar upline para inserir binario
            $result_UPLINE2 = $var->freeRun("SELECT 
                                idUSUARIO,
                                UPLINE,
                                'U'
                            FROM 
                                usuario 
                            WHERE 
                                idUSUARIO = " .  $idtxtIDUsuario . ";
                            ");
                    
            $UPLINE_2 = mysqli_fetch_assoc($result_UPLINE2);
            $countnivel = 1;
            //inserir pontuação para binário
            while ($UPLINE_2['UPLINE'] <> 0 && $countnivel < 10) :
if ($inclusoAgora) {
    $resultBinario = $var->freeRun("UPDATE 
                                    pontuacaobinaria 
                                SET 
                                    qtdIndicados = qtdIndicados + 1
                                WHERE idUsuario = ".$UPLINE_2['UPLINE']."
                                    AND lado = 'U';");
    $commit = $var->freeRun("commit;");
}
       
            $resultBinario = $var->freeRun("UPDATE 
                                    pontuacaobinaria 
                                SET 
                                    pontuacao = pontuacao + $idtxtValorUSDpct
                                WHERE idUsuario = ".$UPLINE_2['UPLINE']."
                                    AND lado = 'U';");

            $commit = $var->freeRun("commit;");

            $resultQualificacaoE = $var->freeRun("SELECT
                                        pontuacao pontos
                                    FROM 
                                        pontuacaobinaria
                                    WHERE 
                                        idUsuario = ".$UPLINE_2['UPLINE']." and
                                        lado = 'U'
                                    group by idUsuario;");
                            
            $resultQualificacaoE = mysqli_fetch_assoc($resultQualificacaoE);
            $ptsQualif = $resultQualificacaoE['pontos'];

            //*****ATUALIZAR GRADUAÇÃO DO USUÁRIO
    
            //InserirPlanoCarreira($ptsQualif, $UPLINE_2['UPLINE']);
    
            //*****

            // buscar upline para inserir binario
            $result_UPLINE2 = $var->freeRun("SELECT 
                                idUSUARIO,
                                UPLINE,
                                'U'
                            FROM 
                                usuario  
                            WHERE 
                                idUSUARIO = " .  $UPLINE_2['UPLINE'] . ";
                            ");
                    
            $UPLINE_2 = mysqli_fetch_assoc($result_UPLINE2);
            $countnivel = $countnivel + 1;

            endwhile;
        } catch (Exception $e) {
            error_log('Caught exception: '.$e->getMessage());
        }
        
        //****************************** FIM INSERINDO PLANO DE CARREIRA USUARIO *************************************
    }

    if ($dadosUpgrade_result == true) {
        $notification =  $var->freeRun("INSERT INTO `notification`(`message`, `usuario_idusuario`, ip) VALUES ('Affiliate activeted ".$idtxtLogin."',".$_SESSION['ID'].", '".$_SESSION['IP']."')");
        header("Location: DashBoard.php?op=Ativar&tipo=sucess&bold=SUCCESS&message=Pacote de " . $idtxtLogin . " ativado com sucesso!");
    } else {
        header("Location: DashBoard.php?op=Ativar&tipo=danger&bold=ATENÇÃO&message=Confirmação não realizada!");
    }
}
?>


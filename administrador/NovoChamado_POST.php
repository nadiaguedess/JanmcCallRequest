<?php
session_start();
require_once("../banco/ConexaoVFBCompany.php");
    //PEGANDO VALORES DA TELA

    $idtxtIdChamadoHidden   = $_POST['idtxtIdChamadoHidden'];
    $idtxtIdChamado         = $_POST['idtxtIdChamado'];
    $idtxtAssunto           = $_POST['idtxtAssunto'];
    $idtxtDesc              = $_POST['idtxtDesc'];
    $idtxtUsuAfe            = $_POST['idtxtUsuAfe'];
    $idtxtDataInicio        = $_POST['idtxtDataInicio'];
    $idtxtConclusao         = $_POST['idtxtConclusao'];
    $idtxtDataCancelamento  = $_POST['idtxtDataCancelamento'];
    $idtxtNivel             = $_POST['idtxtNivel'];
    $idtxtAnalista          = $_POST['idtxtAnalista'];
    $idtxtDescConclusao     = $_POST['idtxtDescConclusao'];
    $idtxtStatus            = $_POST['idtxtStatus'];

    $var = new Mysql();
    $connect = $var->dbConnect();
    
    //inserir
    if ($idtxtIdChamadoHidden == null) {
        verificarCamposInsercao();

        $inserirChamado = $var->freeRun(
            "INSERT INTO `chamado`
            (
            usuario_idusuarioafetado, 
            assuntochamado,
            descricao,
            datainicioproblema,
            analistaatendendo,
            nivelcriticidade_idnivelcriticidade,
            status_idSTATUS,
            usuario_idUsuario)
            VALUES
            (
                $idtxtUsuAfe  ,
                '$idtxtAssunto',
                '$idtxtDesc',
                '$idtxtDataInicio',
                $idtxtAnalista ,
                $idtxtNivel ,
                $idtxtStatus,
                ".$_SESSION['ID']."
            );"
        );

        $buscarUltimoChamadoAberto = $connect->insert_id;

        if ($inserirChamado) {
            header("Location: DashBoard.php?op=NovoChamado&tipo=sucess&bold=Sucesso&message=Chamado $buscarUltimoChamadoAberto criado com sucesso!");
        } else {
            header("Location: DashBoard.php?op=NovoChamado&tipo=danger&bold=Atenção&message=Não foi possível criar o chamado!");
            exit;
        }
    } else {
        verificarCamposAlteracao();

        //editar
        $buscarChamado = $var->selectWhere("chamado", "idchamado", "=", $idtxtIdChamadoHidden, "int");
       
        if (mysqli_num_rows($buscarChamado) == 0) {
            header("Location: DashBoard.php?op=NovoChamado&tipo=danger&bold=Atenção&message=Não foi possível encontrar o chamado para edição!");
            exit;
        }
        $buscarChamadoR = mysqli_fetch_row($buscarChamado);

        //buscar usuario afetado
        $buscarUsuarioAfe = $var->selectWhere("usuario", "idusuario", "=", $idtxtUsuAfe, "int");
        $buscarUsuarioAfeR = mysqli_fetch_assoc($buscarUsuarioAfe);

        $updateChamado = $var->freeRun("        
        UPDATE `chamado`
            SET            
            `usuario_idusuarioafetado` = '".$buscarUsuarioAfeR['NOME']."',
            assuntochamado = ".(!isset($idtxtAssunto)? "null":"'$idtxtAssunto'").", 
            `descricao` = '$idtxtDesc',
            `datainicioproblema` = '$idtxtDataInicio',
            `dataconclusao` = ".(!isset($idtxtConclusao)? "null":"'$idtxtConclusao'").",
            `datacancelamento` = ".(!isset($idtxtDataCancelamento)? "null":"'$idtxtDataCancelamento'").",
            `analistaatendendo` = ".(!isset($idtxtAnalista)? "null":$idtxtAnalista).",
            `descricaoconclusao` = ".(!isset($idtxtDescConclusao)? "null":"'$idtxtDescConclusao'").",
            `nivelcriticidade_idnivelcriticidade` = $idtxtNivel,
            `status_idSTATUS` = $idtxtStatus,
            `usuario_idUsuario` = ".$_SESSION['ID']."
        WHERE `idchamado` = ".$buscarChamadoR[0]." ;
        ");

        if ($updateChamado) {
            header("Location: DashBoard.php?op=NovoChamado&tipo=sucess&bold=Sucesso&message=Chamado #".$buscarChamadoR[0]." alterado com sucesso!");
            exit;
        } else {
            header("Location: DashBoard.php?op=NovoChamado&tipo=danger&bold=Atenção&message=Não foi possível alterar o chamado!");
            exit;
        }
    }
?>


<?php
    //Validação de campos

    function verificarCamposInsercao()
    {
        $mensagemCritica = "<ul>";
        
        if (!IsNullOrEmptyString($_POST['idtxtIdChamadoHidden'])||!IsNullOrEmptyString($_POST['idtxtIdChamado'])) {
            $mensagemCritica .= "<li>O Campo do ID não deve estar preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtAssunto'])) {
            $mensagemCritica .= "<li>O Campo Assunto deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtDesc'])) {
            $mensagemCritica .= "<li>O Campo Descrição deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtUsuAfe'])) {
            $mensagemCritica .= "<li>O Campo Usuário Afetado deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtDataInicio'])) {
            $mensagemCritica .= "<li>O Campo Início da Ocorrência deve ser preenchido!</li>";
        }
        if (!IsNullOrEmptyString($_POST['idtxtConclusao'])) {
            $mensagemCritica .= "<li>O Campo Conclusão não deve ser preenchido!</li>";
        }
        if (!IsNullOrEmptyString($_POST['idtxtDataCancelamento'])) {
            $mensagemCritica .= "<li>O Campo Cancelamento não deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtNivel'])) {
            $mensagemCritica .= "<li>O Campo Nível de Criticidade deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtAnalista'])) {
            $mensagemCritica .= "<li>O Campo Atribuído a: deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtStatus']) || $_POST['idtxtStatus'] != 3) {
            $mensagemCritica .= "<li>O Campo Status deve ser preenchido como: NOVO!</li>";
        }

        $mensagemCritica .= "</ul>";

        if (strlen($mensagemCritica) > 9) {
            header("Location: DashBoard.php?op=NovoChamado&tipo=danger&bold=Atenção&message=$mensagemCritica");
        
            exit;
        }
    }

    function verificarCamposAlteracao()
    {
        $mensagemCritica = "<ul>";
        
        if (IsNullOrEmptyString($_POST['idtxtIdChamadoHidden']) && IsNullOrEmptyString($_POST['idtxtIdChamado'])) {
            $mensagemCritica .= "<li>O Campo do ID deve estar preenchido! Selecione um chamado.</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtAssunto'])) {
            $mensagemCritica .= "<li>O Campo Assunto deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtDesc'])) {
            $mensagemCritica .= "<li>O Campo Descrição deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtUsuAfe'])) {
            $mensagemCritica .= "<li>O Campo Usuário Afetado deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtDataInicio'])) {
            $mensagemCritica .= "<li>O Campo Início da Ocorrência deve ser preenchido!</li>";
        }
        if (!IsNullOrEmptyString($_POST['idtxtConclusao'])) {
            $mensagemCritica .= "<li>O Campo Conclusão não deve ser preenchido!</li>";
        }
        if (!IsNullOrEmptyString($_POST['idtxtDataCancelamento'])) {
            $mensagemCritica .= "<li>O Campo Cancelamento não deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtNivel'])) {
            $mensagemCritica .= "<li>O Campo Nível de Criticidade deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtAnalista'])) {
            $mensagemCritica .= "<li>O Campo Atribuído a: deve ser preenchido!</li>";
        }
        if (IsNullOrEmptyString($_POST['idtxtStatus'])) {
            $mensagemCritica .= "<li>O Campo Status deve ser preenchido!</li>";
        } else {
            if ($_POST['idtxtDataCancelamento'] != null && $_POST['idtxtStatus'] != 7) {
                $mensagemCritica .= "<li>Quando o Data de Cancelamento for preenchida o campo Status deve ser de cancelamento!</li>";
            }
            if ($_POST['idtxtConclusao'] != null && $_POST['idtxtStatus'] != 6) {
                $mensagemCritica .= "<li>Quando o Data de Cancelamento for preenchida o campo Status deve ser de cancelamento!</li>";
            }
        }

        $mensagemCritica .= "</ul>";

        if (strlen($mensagemCritica) > 9) {
            header("Location: DashBoard.php?op=NovoChamado&tipo=danger&bold=Atenção&message=$mensagemCritica");
        
            exit;
        }
    }

    function IsNullOrEmptyString($str)
    {
        return (!isset($str) || trim($str) === '');
    }

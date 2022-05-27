<?
include('../includes/session.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', 'formUsuario.php');
$tab->setTab('Pesquisar','fas fa-search', 'viewUsuario.php');
$tab->setTab('Gerenciar','fas fa-cog', $_SERVER['PHP_SELF']);
$tab->printTab($_SERVER['PHP_SELF']);

$query->exec("SELECT * FROM usuario WHERE login='" . $login ."'");
$query->proximo();
?>
<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF'];?>">
        <input type="hidden" name="login" value="<?=$login?>">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if(isset($edit)){
                            include "../class/class.valida.php";

                            $valida_login=new Valida($form_login,'Login');
                            $valida_login->TamMinimo(1);
                            $erro.=$valida_login->PegaErros();

                            $valida_senha=new Valida($form_senha,'Senha');
                            $valida_senha->TamMinimo(1);
                            $erro.=$valida_senha->PegaErros();

                            $valida_nome=new Valida($form_nome,'Nome');
                            $valida_nome->TamMinimo(1);
                            $erro.=$valida_nome->PegaErros();

                        
                        }

                        if (!$erro && isset($edit)) {
                            
                            if ($form_dt_inatividade_i=="") $form_dt_inatividade_i="NULL";                            
                            if ($form_dt_inatividade_f=="") $form_dt_inatividade_f="NULL";
                            $query->begin();
                            $itens =array(strtolower(trim($form_login)),$form_senha,$form_nome,strtolower(trim($form_email)),$form_habilitado,$form_dt_validade,$form_dt_inatividade_i,$form_dt_inatividade_f);
                            $where =array(0 => array('login', $login));
                            $query->updateTupla('usuario', $itens, $where);
                            $query->commit();
                        }
                        if($erro) echo callException($erro, 2);?>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="form-group">
                    <label for="form_login">Login</label>
                    <input type="text" class="form-control" name="form_login" id="form_login" value="<? if($edit) echo $form_login; else echo $query->record['login']; ?>">
                </div>
                <div class="form-group">
                    <label for="form_senha">Senha</label>
                    <input type="password" class="form-control" name="form_senha" id="form_senha" value="<? if($edit) echo $form_senha; else echo $query->record['senha']; ?>">
                </div>
                <div class="form-group">
                    <label for="form_nome">Nome</label>
                    <input type="text" class="form-control" name="form_nome" id="form_nome" value="<? if($edit) echo $form_nome; else echo $query->record['nome']; ?>">
                </div>
                <div class="form-group">
                    <label for="form_email">Email</label>
                    <input type="email" class="form-control" name="form_email" id="form_email" value="<? if($edit) echo $form_email; else echo $query->record['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="form_habilitado">Status</label>
                    <select class="form-control" name="form_habilitado" id="form_habilitado">
                        <option value='S' <?if($erro){if($form_habilitado=='S') echo 'selected';} else if ($query->record['habilitado']=='S') echo selected?>>Habilitado</option>
                        <option value='N' <?if($erro){if($form_habilitado=='N') echo 'selected';} else if ($query->record['habilitado']=='N') echo selected?>>Dasabilitado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="form_dt_validade">Validade</label>
                    <input type="date" class="form-control" name="form_dt_validade" id="form_dt_validade" value="<? if($edit) echo $form_dt_validade;else echo $query->record['dt_validade']; ?>">
                </div>
                <div class="form-group">
                    <label for="form_dt_inatividade_i">Período de Inatividade de </label>
                    <input type="date" class="form-control" name="form_dt_inatividade_i" id="form_dt_inatividade_i" value="<? if($edit) echo $form_dt_inatividade_i; else echo $query->record['dt_inatividade_inicial'];?>">
                    <label for="form_dt_inatividade_f">a</label>
                    <input type="date" class="form-control" name="form_dt_inatividade_f" id="form_dt_inatividade_f" value="<? if($edit) echo $form_dt_inatividade_f;else echo $query->record['dt_inatividade_final']; ?>">
                </div>

            </div>
            <div class="card-footer border-top-0 bg-transparent">
                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="edit" value="Salvar">
                </div>
            </div>
    </form>
</section>

<? include_once('../includes/dashboard/footer.php'); ?>

<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();
$tab->setTab('Adicionar','fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar','fas fa-search', 'viewUsuario.php');
$tab->printTab($_SERVER['PHP_SELF']);


?>
<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if(isset($add)){
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
/*
                            $valida_nome=new Valida($form_email,'Email');
                            $valida_nome->FormatoEmail();
                            $erro.=$valida_nome->PegaErros();
*/                        }

                        if (!$erro && isset($add)) {
                            $query->begin();
                            $form_dt_inatividade_i="NULL";                            
                            $form_dt_inatividade_f="NULL";
                            $form_senha = sha1($form_senha);
                            //insere usuario
                            $query->exec(
                                "INSERT INTO usuario(
                                    login,         senha,       nome,        email,        habilitado, dt_validade,
                                    dt_inatividade_inicial, dt_inatividade_final, alterou_senha, alteracao_login, alteracao_ip, alteracao_data)
                                VALUES(
                                    '$form_login','$form_senha','$form_nome','$form_email','S',        '$form_dt_validade',
                                    $form_dt_inatividade_i, $form_dt_inatividade_f,'N',          '$_login',       '$_ip',       '$_datahora')");

                            if(isset($form_grupo)){
                                $query_aux = new Query($bd);
                                foreach($form_grupo as $obj){
                                    $query->exec("INSERT INTO usuario_grupo(login, codgrupo) VALUES ('$form_login', '$obj')");
                                }
                            }

                            $query->commit();

                        }
                        if($erro) echo callException($erro, 2);?>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">

                <div class="form-group">
                    <label for="form_login">Login</label>
                    <input type="text" class="form-control" name="form_login" id="form_login" value="<? if($erro) echo $form_login; ?>">
                </div>
                <div class="form-group">
                    <label for="form_senha">Senha</label>
                    <input type="password" class="form-control" name="form_senha" id="form_senha" value="<? if($erro) echo $form_senha; ?>">
                </div>
                <div class="form-group">
                    <label for="form_nome">Nome</label>
                    <input type="text" class="form-control" name="form_nome" id="form_nome" value="<? if($erro) echo $form_nome; ?>">
                </div>
                <div class="form-group">
                    <label for="form_email">Email</label>
                    <input type="email" class="form-control" name="form_email" id="form_email" value="<? if($erro) echo $form_email; ?>">
                </div>
                <div class="form-group">
                    <label for="form_dt_validade">Validade</label>
                    <input type="date" class="form-control" name="form_dt_validade" id="form_dt_validade" value="<? if($erro) echo $form_dt_validade; ?>">
                </div>

                <div class="form-group">
                    <label for="form_dt_inatividade_i">Período de Inatividade de </label>
                    <input type="date" class="form-control" name="form_dt_inatividade_i" id="form_dt_inatividade_i" value="<? if($erro) echo $form_dt_inatividade_i; ?>">
                    <label for="form_dt_inatividade_f">a</label>
                    <input type="date" class="form-control" name="form_dt_inatividade_f" id="form_dt_inatividade_f" value="<? if($erro) echo $form_dt_inatividade_f; ?>">
                </div>

                <div class="form-group">
                    <label for="form_grupo">Grupo</label>
                    <select multiple class="form-control" name="form_grupo[]" id="form_grupo">
                        <?
                        $query->exec("SELECT codgrupo, descricao FROM grupo ORDER BY descricao");
                        $n=$query->rows();
                        while($n--){
                            $query->proximo();
                            echo"<option value='".$query->record[0]."'>".$query->record[1]."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="card-footer border-top-0 bg-transparent">
                <div class="text-center">
                    <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                    <input class="btn btn-info" type="submit" name="add" value="Salvar">
                </div>
            </div>
    </form>
</section>

<? include_once('../includes/dashboard/footer.php'); ?>

<?

include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
include('../function/function.date.php');

$query->exec("SELECT 
a.id_animal , a.nro_ficha , a.nro_chip , a.sexo , a.observacao, p. descricao , e.descricao
FROM 
animal a, pelagem p , especie e
 WHERE 
id_animal = $id_animal
AND 
a.id_pelagem = p.id_pelagem
AND 
a.id_especie = e.id_especie

");


//$query->exec("SELECT 
//a.id_animal , a.nro_ficha , a.nro_chip , a.sexo , a.observacao, p. descricao , e.descricao
//FROM 
//animal a, pelagem p , especie e
// WHERE 
//id_animal = $id_animal
//AND 
//a.id_pelagem = p.id_pelagem
//AND 
//a.id_especie = e.id_especie

//");



$query->result($query->linha);

$id_animal         = $query->record[0];
$nro_ficha         = $query->record[1];
$nro_chip          = $query->record[2];
$id_pelagem        = $query->record[3];
$id_especie        = $query->record[4];
$sexo              = $query->record[5];
$observacao        = $query->record[6];

$tab = new Tab();

$tab->setTab('Pesquisar', 'fas fa-search', 'ANIMAL_view.php?id_animal='                  . $id_animal);
$tab->setTab($query->record[1], $query->record[5], $_SERVER['PHP_SELF'] . '?id_animal='   . $id_animal);
$tab->setTab('Editar', 'fas fa-pencil-alt', 'ANIMAL_edit.php?id_animal='                 . $id_animal);
$tab->printTab($_SERVER['PHP_SELF'] . '?id_animal=' . $query->record[0]);

?>


<section class="content">

    <div class="card p-0">

        <div class="card-header border-bottom-1 mb-3 bg-light-2">

            <div class="text-center">
                <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
            </div>

            <div class="row text-center">
                <div class="col-12 col-sm-4 offset-sm-4">
                    <? if ($erro) echo callException($erro, 2); ?>
                </div>
            </div>

        </div>


        <div class="card-body pt-3">


            <div class="row">

                <div class="col-12">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card_dashboar_ANIMAL_FICHA.php"); ?>

                </div>

            </div>

            <div class="row">

                <div class="col-12 col-md-12">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card_dashboard_ANIMAL_RESPONSAVEL.php"); ?>


                </div>



                <div class="col-12 col-md-12">

                    <? include("../includes/Cards/Dashboard_ANIMAL/card-dashboard_ANIMAL_OCORRENCIA.php"); ?>

                </div>

            </div>

        </div>

    </div>

</section>


<?
include_once('../includes/dashboard/footer.php');

function isNum($val)
{
    if (is_numeric($val)) {
        if (strtoupper($val) != "NAN") {
            return intval($val);
        }
    }
    return 0;
}
?>

<?

$query_responsavel->insertTupla('responsavel', $dados);
$id_responsavel_last = $query_responsavel->last_insert[0];

if ($form_responsavel == "") {

    $query->exec("SELECT 
        r.id_responsavel , r.nome , r.cpf , r.rg , r.dt_nascimento, r.endereco, b.descricao 
        FROM 
            Responsavel r, Bairro b
        WHERE 
            id_responsavel = $id_responsavel
        AND 
            b.id_bairro = r.id_bairro  
    ");



    $query->result($query->linha);

    $id_responsavel         = $query->record[0];
    $nome                   = $query->record[1];
    $cpf                    = $query->record[2];
    $rg                     = $query->record[3];
    $dt_nascimento          = $query->record[4];
    $endereco               = $query->record[5];
    $bairro                 = $query->record[6];
}
?>

<div class="modal fade show" id="modal_add_responsavel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <form action="<? echo $_SERVER['PHP_SELF'] . "?id_responsavel=" . $id_responsavel ?>" method="post">

                <div class="modal-header bg-gradient-yellow-orange">
                    <h5 class="modal-title"><i class="fas fa-project-diagram"></i> Adicionar Responsavel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group col-12">

                        <div class="form-row">

                            <div class="form-group col-6">
                                <label for="form_responsavel"><span class="text-danger">*</span> Responsavel :</label>
                                <select name="form_responsavel" id="form_responsavel" class="form-control" required>
                                    <?
                                    $form_elemento = $erro ? $form_responsavel : "";
                                    include("../includes/inc_select_responsavel.php"); ?>
                                </select>
                                <div class="invalid-feedback">
                                    Escolha o responsavel.
                                </div>
                            </div>

                            <div class="form-group col-12 col-md-3">
                                <label for="form_mascara"><span class="text-danger">*</span>CPF: </label>
                                <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($erro) echo $form_mascara; ?>">
                                <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label for="form_rg"><span class="text-danger">*</span> RG :</label>
                                <input required autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100" value="<? if ($erro) echo $form_rg; ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12 col-md-2">
                                <label for="form_dt_nascimento"><span class="text-danger">*</span> Data de nascimento :</label>
                                <input type="date" class="form-control" name="form_dt_nascimento" id="form_dt_nascimento" maxlength="100" value="<? if ($erro) echo $form_dt_nascimento; ?>">
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="form_endereco"><span class="text-danger">*</span> Endereço :</label>
                                <input type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="100" value="<? if ($erro) echo $form_endereco; ?>">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="form_bairro"><span class="text-danger">*</span> Bairro :</label>
                                <select name="form_bairro" id="form_bairro" class="form-control" required>
                                    <?
                                    $form_elemento = $erro ? $form_bairro : "";
                                    include("../includes/inc_select_bairro.php"); ?>
                                </select>
                                <div class="invalid-feedback">
                                    Escolha o bairro.
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
        </div>
    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="button" id="add_escola_modal" class="btn btn-info">
        <i class="fas fa-check"></i>&nbsp;
        Salvar
    </button>
</div>
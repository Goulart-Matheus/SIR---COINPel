<?
include('../includes/session.php');
include('../includes/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');

$tab = new Tab();

$tab->setTab('Adicionar', 'fas fa-plus', $_SERVER['PHP_SELF']);
$tab->setTab('Pesquisar', 'fas fa-search', 'ANIMAL_view.php');

$tab->printTab($_SERVER['PHP_SELF']);
$link = isset($id_animal) && $id_animal != "" ? "?id_animal=$id_animal" : "";

?>
<section class="content">

    
    <form method="post" action="<? echo $_SERVER['PHP_SELF'] . $link ?>" enctype="multipart/form-data">

        <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                <div class="text-center">
                    <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                </div>

                <div class="row text-center">

                    <div class="col-12 col-sm-4 offset-sm-4">

                        <?
                        if (isset($add)) {
                            include "../class/class.valida.php";

                            $valida = new Valida($form_nro_ficha, 'Nro_ficha');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_nro_chip, 'Nro_chip');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_sexo, 'Sexo');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();


                            $valida = new Valida($form_id_pelagem, 'Id_pelagem');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();

                            $valida = new Valida($form_id_especie, 'Id_especie');
                            $valida->TamMinimo(1);
                            $erro .= $valida->PegaErros();
                        }

                        if (!$erro && isset($add)) {

                            $query->begin();

                            $query->insertTupla(
                                'animal',
                                array(
                                    trim($form_nro_ficha),
                                    $form_nro_chip,
                                    $form_id_pelagem,
                                    $form_id_especie,
                                    $form_sexo,
                                    $form_observacao,
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,

                                )
                            );


                           $query->commit();
                        }

                        if ($erro)

                            echo callException($erro, 2);

                        ?>

                    </div>

                </div>

            </div>

            <div class="card-body pt-0">

                <div class="form-row">

                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_ficha"><span class="text-danger">*</span> Numero Ficha</label>
                        <input type="text" class="form-control" name="form_nro_ficha" id="form_nro_ficha" maxlength="100" value="<? if ($erro) echo $form_nro_ficha; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_nro_chip"><span class="text-danger">*</span> Numero Chip</label>
                        <input type="text" class="form-control" name="form_nro_chip" id="form_nro_chip" maxlength="100" value="<? if ($erro) echo $form_nro_chip; ?>">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_sexo"><span class="text-danger">*</span> Sexo</label>
                        <select name="form_sexo" required id="form_sexo" class="form-control">
                            <option value="" selected>Selecione o sexo:</option>
                            <option value="M">Macho</option>
                            <option value="F">Fêmea</option>
                        </select>
                        <div class="invalid-feedback">
                            Escolha o sexo do animal.
                        </div>
                    </div>



                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_pelagem"><span class="text-danger">*</span>Pelagem</label>
                        <select name="form_id_pelagem" id="form_id_pelagem" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_id_pelagem : "";
                            include("../includes/inc_select_pelagem.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha a Pelagem
                        </div>
                    </div>


                    <div class="form-group col-12 col-md-6">
                        <label for="form_id_especie"><span class="text-danger">*</span>Espécie</label>
                        <select name="form_id_especie" id="form_id_especie" class="form-control" required>
                            <?
                            $form_elemento = $erro ? $form_especie : "";
                            include("../includes/inc_select_especie.php"); ?>
                        </select>
                        <div class="invalid-feedback">
                            Escolha Especie
                        </div>
                    </div>


                    <div class="form-group col-12">
                        <label for="form_observacao">Observação</label>
                        <input type="text" class="form-control" name="form_observacao" id="form_observacao" maxlength="200" value="<? if ($erro) echo $form_observacao; ?>">
                    </div>


                </div>

                <div class="card-footer border-top-0 bg-transparent">
                    <div class="text-center">
                        <input class="btn btn-secondary" type="reset" name="clear" value="Limpar">
                        <button type="button" class="btn btn-md text-light ad_duplicidade btn-info"> salvar
                        </button>

                   
                    </div>


                </div>

            </div>

    </form>

</section>


<script src="../assets/js/jquery.js"></script>

<script>

    $(document).on('click', '.ad_duplicidade', function() {
  var nome= 'boi';
  var cpf= '';
  console.log(nome);
        $.ajax({
            type: "GET",
            url: "../includes/ajax_valida_dup_responsavel_animal.php",
            data: {
                'nome': nome,
                'cpf': cpf
            },
            success: function(r) {

                if (r[0]["status"] == 1) {

                    var modal = '<div class="modal" tabindex="-1" role="dialog" id="modal_dup_responsavel_animal">';
                    modal += '<div class="modal-dialog modal-xl" role="document">';
                    modal += '<div class="modal-content">';
                    modal += '<div class="modal-header">';
                    modal += '<h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning"></i> Possível Duplicidade</h5>';
                    modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    modal += '<span aria-hidden="true">&times;</span>';
                    modal += '</button>';
                    modal += '</div>';
                    modal += '<div class="modal-body">';
                    modal += '<table class="table table-striped">';
                    modal += '<thead>';
                    modal += '<tr>';
                    modal += '<th>ID</th>';
                    modal += '<th>Nome</th>';
                    modal += '<th class="text-center">CPF</th>';
                    modal += '<th class="text-center">RG(a)</th>';
                    modal += '<th class="text-center">Dt_nascimento</th>';
                    modal += '<th class="text-center">Endereço</th>';
                    modal += '<th class="text-center">Id_bairro</th>';
                    modal += '<th class="text-center">Opções</th>';
                    modal += '<tr>';
                    modal += '<tbody>';

                    r.forEach(function(valor, chave) {

                        modal += '<tr>';
                        modal += '<td><b>' + valor['id_responsavel'] + '</b></td>';
                        modal += '<td>' + valor['nome'] + '</td>';
                        modal += '<td class="text-center">' + valor['cpf'] + '</td>';
                        modal += '<td class="text-center">' + valor['rg'] + '</td>';
                        modal += '<td class="text-center">' + valor['dt_nascimento'] + '</td>';
                        modal += '<td class="text-center">' + valor['endereco'] + '</td>';
                        modal += '<td class="text-center">' + valor['id_bairro'] + '</td>';


                        if (valor['duplicidade'] == "S") {
                            modal += '<td class="text-center">';
                            modal += '<button class="btn btn_green bg-green btn-sm ad_duplicidade" ';
                            modal += 'data-id-resp="' + valor['id_responsavel'] + '" ';
                            modal += 'data-id-nome="' + valor['nome'] + '" ';
                            modal += 'data-id-cpf="' + valor['cpf'] + '" ';
                            modal += 'data-id-rg="' + valor['rg'] + '" ';
                            modal += 'data-id-dt_nascimento="' + valor['dt_nascimento'] + '" ';
                            modal += 'data-id-endereco="' + valor['endereco'] + '" ';
                            modal += 'data-id-bairro="' + valor['bairro'] + '" ';
                            modal += '>Adicionar Duplicidade</button>';

                            modal += '</td>';
                        } else {
                            modal += '<td class="text-center">Sem Ações</td>';
                        }

                        modal += '</tr>';
                    });

                    modal += '</tbody>';
                    modal += '</table>';
                    modal += '</div>';
                    modal += '</div>';
                    modal += '</div>';
                    modal += '</div>';

                    if ($(".modal-backdrop").length > 0) {
                        $(".modal-backdrop").remove();
                    }

                    if ($("#modal_dup_responsavel_animal").length > 0) {
                        $("#modal_dup_responsavel_animal").remove();
                    }

                    $('body').append(modal);
                    $("#modal_dup_responsavel_animal").modal('show');
                    console.log(r);

                    return;
                }

            },
            error: function(r) {
                alert("Erro ao buscar os dados dos responsaveis");

                console.log(r);
            }
        });

    });

    </script>











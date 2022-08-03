<?


$query->exec(
    "SELECT
                ar.id_responsavel,
                ar.id_animal,
                r.nome,
                r.cpf,
                r.rg,
                r.dt_nascimento,
                r.endereco,
                b.descricao

            FROM
                responsavel r,
                animal_responsavel ar,
                animal a,
                bairro b
            WHERE
               a.id_animal = $id_animal
            AND
            ar.id_responsavel = r.id_responsavel
            AND
            ar.id_animal = a.id_animal
            AND
            b.id_bairro = r.id_bairro
             "

);
//$total_contato = $query->record[0];
$n = $query->rows();

//$js_Onclick = "OnClick=javascript:window.location=('ANIMAL_edit.php?search=true&id_animal=$id_animal";

?>

<div class="card border">

    <div class="card-header bg-green">

        <div class="row">

            <div class="col-md-6 text-left">

                <i class="fas fa-list"></i> Responsáveis Vinculados

            </div>


            <div class="col-md-6 text-right">
                <button type="button" class="btn bg-gray btn-light btn-sm text-light btn_modal_add_responsaveis" data-toggle="modal" data-target="#modal_add_responsavel" data-modal="VI">
                    <i class="fas fa-plus"></i>
                </button>
            </div>


        </div>

    </div>

    <div class="card-body p-0 m-0" style="height: 175px;">

        <div class="col-12 p-0 m-0" id="chart_info"></div>
        <!-- Inicio -->
        <?
        if ($n == 0) {
        ?>

            <div class="col-12 text-center pt-5 text-dark">

                <h5 class="mb-5">Este animal ainda não possue nenhum responsável vinculado</h5>



            </div>
        <?
        } else {
        ?>

            <table class="table p-0 m-0">

                <thead class="bg-light grey">

                    <tr>

                        <th style="width: 150px;" class="px-1">Nome</th>
                        <th style="width: 25px;" class="px-1">CPF</th>
                        <th style="width: 25px;" class="px-1">RG</th>


                    </tr>

                </thead>

                <tbody>

                    <?
                    while ($n--) {
                        $query->proximo();

                    ?>
                        <tr>
                            <td><?= $query->record[2]; ?></td>
                            <td><?= $query->record[3]; ?></td>
                            <td><?= $query->record[4]; ?></td>

                        </tr>
                    <?

                    }

                    ?>

                </tbody>

            </table>

        <?
        }

        ?>


        <!-- Fim-->

    </div>

    <div class="card-footer">

        <div class="row">

            <div class="col-9"><a href='RESPONSAVEL_form.php?id_animal=<?= $id_animal ?>'><i class="fa fa-plus"></i> Novo</a></div>

        </div>

    </div>

</div>




<div class="modal fade text-left" id="modal_add_responsavel" tabindex="-1" role="dialog" aria-hidden="true">

<div class="modal-dialog modal-xl" role="document">

    <div class="modal-content">

        <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">

            <div class="modal-header bg-light-2">
                <h5 class="modal-title">

                    <i class="fas fa-meh text-green"></i>

                    Registro de Responsavel
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-row">


                    <div class="form-group col-12 col-md-4">
                        <label for="form_responsavel"><span class="text-danger">*</span>Nome</label>
                        <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100">
                    </div>

                    <div class="form-group col-12 col-md-4">
                        <label for="form_mascara"><span class="text-danger">*</span>CPF</label>
                        <input type="text" class="form-control" name="form_mascara" id="form_mascara" maxlength="11">
                    </div>


                    <div class="form-group col-12 col-md-4">
                        <label for="form_rg"><span class="text-danger">*</span>RG</label>
                        <input type="text" class="form-control" name="form_rg" id="form_rg" maxlength="14">
                    </div>

                    
                </div>    
                
                <button type="button" id= "btn_ajax_responsavel" name="btn_ajax_responsavel" class="btn btn-light btn_ajax_responsavel">
                         <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                </button>
                   

                
                

            </div>


    </div>

    <div class="modal-footer bg-light-2 text-center ">
                 <button type="button" id= "btn_ajax_responsavel" name="btn_ajax_responsavel" class="btn btn-light btn_ajax_responsavel">
                         <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                </button>
    </div>

    </form>

</div>

</div>

<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/jquery.mask.js"></script>
<script type="text/javascript">
    $('#form_mascara').mask('000.000.000-00');
    $('#form_rg').mask('00000000000000');

</script> 
<script src="../../../assets/js/jquery.js"></script>

<script>
    $(document).ready(function() {

        $(".btn_ajax_responsavel").on('click', function() {


            var nome = $("#form_responsavel").val();
            var cpf  = $("#form_mascara").val();
            var rg   = $("#form_rg").val();
           
            console.log('oi');


            $.ajax({
                type: 'POST',
                url: '../../../includes/ajax_busca_responsavel.php',
                data: {
                       "nome"  : nome,
                       "cpf"   : cpf,
                       "rg"    : rg,
                       
                },
                beforeSend: function() {

                    console.log("Enviado");


                },
                success: function(ret) {

                    console.log(ret);

                },
                error: function(erro) {

                    console.log(erro);

                }
            });
        });
    });
</script>
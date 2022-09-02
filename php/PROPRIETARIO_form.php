<?
include('../includes/session.php');
include('../include/variaveisAmbiente.php');
include_once('../includes/dashboard/header.php');
include('../class/class.tab.php');
$tab = new Tab();

$tab->setTab('Proprietários','fas fa-user-circle', 'PROPRIETARIO_viewDados.php');
$tab->setTab('Novo Proprietário','fas fa-plus', $_SERVER['PHP_SELF']);

$tab->printTab($_SERVER['PHP_SELF']);
?>

<? require "../includes/mapbox.header.php"; ?>

<style>
    .image-preview-input {
        position: relative;
        overflow: hidden;
        margin: 0px;
        color: #333;
        background-color: #fff;
        border-color: #ccc;
    }
    .image-preview-input input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
    }
    .image-preview-input-title {
        margin-left:2px;
    }
</style>

<section class="content">
    <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
        <div class="card p-1">
            <div class="card-header border-bottom-0">
                <div class="text-center">
                    <h4><?=$auth->getApplicationDescription($_SERVER['PHP_SELF'])?></h4>
                </div>
                <div class="row text-center">
                    <div class="col-12 col-sm-4 offset-sm-4">
                        <?
                        if(isset($add))
                        {
                            include "../class/class.valida.php";

                            $valida_nome = new Valida($form_nome, 'Nome');
                            $valida_nome->TamMinimo(2);
                            $erro .= $valida_nome->PegaErros();

                            $valida_cpf = new Valida($form_cpf, 'CPF');
                            $valida_cpf->checacpf();
                            $erro .= $valida_cpf->PegaErros();

                            $valida_inscricao_estadual = new Valida($form_inscricao_estadual, 'Inscrição Estadual');
                            $valida_inscricao_estadual->ENumerico();
                            $erro .= $valida_inscricao_estadual->PegaErros();

                            /*
                            $valida_email = new Valida($form_email, 'Email');
                            $valida_email->FormatoEmail();
                            $erro .= $valida_email->PegaErros();
                            */
                            /*
                            $valida_data = new Valida($form_data, "Data de criação");
                            $valida_data->FormatoData();
                            $erro .= $valida_data->PegaErros();
                            */

                            if($form_registrar_propriedade == "S") {
                                $valida_propriedade_nome = new Valida($form_propriedade_nome, 'Nome da propriedade');
                                $valida_propriedade_nome->TamMinimo(2);
                                $erro .= $valida_nome->PegaErros();
                            }

                            if(!$erro) {
                                $diretorio = "assets/images/marcas";
                                include "../includes/uploadarquivo.php";
                            }
                        }

                        if (!$erro && isset($add)) {
                            require_once "../includes/variaveisAmbiente.php";

                            $query->begin();

                            $query->insertTupla(
                                'proprietario',
                                array(
                                    $form_nome,
                                    $form_cpf,
                                    $_data,
                                    $imagem_nome,
                                    $form_inscricao_estadual,
                                    $form_tipo,
                                    $form_email,
                                    $form_telefone,
                                    $form_ano_estimado,
                                    $_login,
                                    $_ip,
                                    $_datahora,
                                )
                            );

                            $query->commit();

                            if(!$erro && $form_registrar_propriedade == "S") {
                                $id_proprietario = $query->last_insert[0];
                                $form_bovino = $form_bovino ?: 'N';
                                $form_equino = $form_equino ?: 'N';
                                $form_bubalino = $form_bubalino ?: 'N';

                                $query->begin();
                                $query->insertTupla(
                                    'propriedade',
                                    array(
                                        $id_proprietario,
                                        $form_propriedade_nome,
                                        $form_coordenada,
                                        $form_bovino,
                                        $form_equino,
                                        $form_bubalino,
                                        $form_area,
                                        $_login, $_ip, $_datahora,
                                    )
                                );
                                $query->commit();
                            }
                        }

                        if($erro) echo callException($erro, 2);
                        ?>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form_cpf">CPF</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_cpf" id="form_cpf" value="<? if($erro) echo $form_cpf ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="form_nome">Nome</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_nome" id="form_nome" value="<? if($erro) echo $form_nome ?>">
                    </div>

                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="form_inscricao_estadual">Inscrição estadual</label>
                        <input
                            autocomplete="off"
                            required
                            type="number"
                            class="form-control"
                            name="form_inscricao_estadual"
                            id="form_inscricao_estadual"
                            value="<? if($erro) echo $form_inscricao_estadual; ?>"
                        >
                    </div>

                    <!-- Upload com preview
                    <div class="form-group col-md-3">
                        <div class="input-group image-preview">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span> Clear
                                </button>

                                <div class="btn btn-default image-preview-input">
                                    <span class="image-preview-input-title">Browse</span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="input-file-preview"/>
                                </div>
                            </span>
                        </div>
                    </div>
                    -->
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="form_imagem">Desenho da marca</label>
                            <input autocomplete="off" type="file" class="form-control" name="form_imagem" id="form_imagem">
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="form_tipo">Tipo de marca</label>
                        <select required class="form-control" name="form_tipo" id="form_tipo" >
                            <?
                                include "../includes/tipo_de_marca.php";
                                if($erro) print_options_for_tipo_de_marca($form_tipo);
                                else print_options_for_tipo_de_marca();
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="form_ano_estimado">Ano estimado</label>
                        <input
                            required
                            autocomplete="off"
                            type="number"
                            class="form-control"
                            name="form_ano_estimado"
                            id="form_ano_estimado"
                            value="<? if($erro) echo $form_ano_estimado; ?>"
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="form_email">Email</label>
                        <input autocomplete="off" type="text" class="form-control" name="form_email" id="form_email" value="<? if($erro) echo $form_email ?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="form_telefone">Telefone</label>
                        <input required autocomplete="off" type="text" class="form-control" name="form_telefone" id="form_telefone" value="<? if($erro) echo $form_telefone ?>">
                    </div>
                </div>

                <div class="form-row">                    
                    <div class="form-group bg-green col-12 font-weight-bold col-md-12 py-2 text-center">   
                    
                        <div class="form-check form-check-inline">
                            <label class="form-check-label mr-2" for="form_registrar_propriedade">CADASTRAR PROPRIEDADE</label>
                            <input type="hidden" value="N" name='form_registrar_propriedade'>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="form_registrar_propriedade"
                                id="form_registrar_propriedade"
                                value="S"
                                <? if(isset($form_registrar_propriedade)
                                    ? $form_registrar_propriedade == "S"
                                    : true)
                                    {
                                        echo "checked";
                                    }
                                ?>
                            >
                        </div>
                    </div>
                </div>

                <?
                    $coordenada = $form_propriedade_coordenada ?: "-31.760486508611237,-52.33785820699613";
                    $zoom = 9;
                ?>

                <div class="form-row">
                    <div class="form-group col-6">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="form_coordenada">Coordenada</label>
                                <input
                                    autocomplete="off"
                                    type="text"
                                    class="form-control form-propriedade"
                                    id="form_coordenada"
                                    name="form_coordenada"
                                    value="<?= $coordenada ?>" maxlength="100"
                                >
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="form_propriedade_nome">Nome da propriedade</label>
                                <input
                                    autocomplete="off"
                                    type="text"
                                    class="form-control form-propriedade"
                                    id="form_propriedade_nome"
                                    name="form_propriedade_nome"
                                    maxlength="50"
                                    value="<? if($erro) echo $form_propriedade_nome ?>"
                                >
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="form_area">Área (em Hectares)</label>
                                <input
                                    autocomplete="off"
                                    type="number"
                                    class="form-control form-propriedade"
                                    id="form_area"
                                    name="form_area"
                                    maxlength="10"
                                    step="0.01"
                                    value="<?= $erro ? $form_area : 0 ?>"
                                >
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <div class="form-check form-check-inline col-sm-3">
                                    <input
                                        type="checkbox"
                                        class="form-check-input form-propriedade"
                                        name="form_bovino"
                                        value="S"
                                        <? if($erro && $form_bovino) echo "checked"; ?>
                                    >
                                    <label for="form_bovino">Bovino</label>
                                </div>
                                <div class="form-check form-check-inline col-sm-3">
                                    <input
                                        type="checkbox"
                                        class="form-check-input form-propriedade"
                                        name="form_equino"
                                        value="S"
                                        <? if($erro && $form_equino) echo "checked"; ?>
                                    >
                                    <label for="form_equino">Equino</label>
                                </div>
                                <div class="form-check form-check-inline col-sm-3">
                                    <input
                                        type="checkbox"
                                        class="form-check-input form-propriedade"
                                        name="form_bubalino"
                                        value="S"
                                        <? if($erro && $form_bubalino) echo "checked"; ?>
                                    >
                                    <label for="form_bubalino">Bubalino</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-6">
                        <div id='map' style='min-height: 300px; height: 100%' meta="marker"></div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light-2">
                <?
                $btns = array('clean', 'save');
                include('../includes/dashboard/footer_forms.php');
                ?>
            </div>

        </div>
    </form>
</section>

<? include_once('../includes/dashboard/footer.php'); ?>

<script language="javascript">
    $(document).ready(function()
    {
        $('#form_registrar_propriedade').change(function()
        {
            let is_disabled = !this.checked;

            $('.form-propriedade').each(function(_, elem) {
                elem.disabled = is_disabled;
            })
        });
        $('#form_registrar_propriedade').trigger('change');

        /* Upload com preview
        $(document).on('click', '#close-preview', function(){
            $('.image-preview').popover('hide');
            // Hover befor close the preview
            $('.image-preview').hover(
                function () {$('.image-preview').popover('show');},
                function () {$('.image-preview').popover('hide');}
            );
        });

        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });

        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("Browse");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("Change");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
            }
            reader.readAsDataURL(file);
        });
        */
    });
</script>

<? require "../includes/mapbox.footer.php"; ?>

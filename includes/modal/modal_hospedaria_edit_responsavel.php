<div class="modal fade text-left" id="RESPONSAVEL_edit_modal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <div class="modal-header bg-light-2">
                <h5 class="modal-title"><i class="fas fa-person text-green"></i> Editar Responsavel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </div>

            <div class="modal-body">
                <form method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">

                    <div class="card p-0">
                        <div class="form-row">
                            <input type="hidden" name="form_id_responsavel" id="form_id_responsavel" value="<?=$id_responsavel;?>">
                            <div class="form-group col-12 col-md-6">
                                <label for="form_responsavel"><span class="text-danger">*</span> Nome :</label>
                                <input required autocomplete="off" type="text" class="form-control" name="form_responsavel" id="form_responsavel" maxlength="100" value="<? if ($edit) echo trim($form_responsavel);
                                                                                                                                                                            else echo trim($responsavel_nome); ?>">
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label for="form_mascara"><span class="text-danger">*</span>CPF: </label>
                                <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($edit) echo trim($form_mascara);
                                                                                                                                    else echo trim($cpf); ?>">
                            </div>
                            <div class="form-group col-12 col-md-3">
                                <label for="form_rg"><span class="text-danger">*</span> RG :</label>
                                <input required autocomplete="off" type="text" class="form-control" name="form_rg" id="form_rg" maxlength="100" value="<? if ($edit) echo trim($form_rg);
                                                                                                                                                        else echo trim($rg); ?>">
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-12 col-md-4">
                                <label for="form_dt_nascimento"><span class="text-danger">*</span> Data de nascimento :</label>
                                <input type="date" class="form-control" name="form_dt_nascimento" id="form_dt_nascimento" maxlength="100" value="<? if ($edit) echo trim($form_dt_nascimento);
                                                                                                                                                    else echo ($dt_nascimento_responavel); ?>">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="form_endereco"><span class="text-danger">*</span> Endereço :</label>
                                <input required autocomplete="off" type="text" class="form-control" name="form_endereco" id="form_endereco" maxlength="100" value="<? if ($edit) echo trim($form_endereco);
                                                                                                                                                                    else echo $endereco_responsavel; ?>">
                            </div>
                            <div class="form-group col-12 col-md-4">
                                <label for="form_bairro"><span class="text-danger">*</span> Bairro :</label>
                                <select name="form_bairro" id="form_bairro" class="form-control" required >
                                    <?
                                    $form_elemento = $edit ? $form_bairro : $id_bairro_responsavel;
                                    include("../includes/inc_select_bairro.php"); ?>
                                </select>
                                <div class="invalid-feedback">
                                    Escolha o bairro.
                                </div>
                            </div>

                        </div>
                        <div class="form-row ">

                            <div class="form-group col-12 ">

                                <p class="text-center py-2 bg-green">
                                    Contatos :
                                </p>

                            </div>

                        </div>

                        <div class="form-row">


                            <div class="form-group col-12" id="container_dinamico">

                                <?

                                if ($edit) {
                                    $qnt = count($form_valor_contato);
                                } else {
                                    $programas = new Query($bd);
                                    $programas->exec("SELECT rc.id_tipo_contato, rc.valor_contato, rc.principal, rc.id_responsavel_contato
                                                    FROM responsavel as r, responsavel_contato as rc, tipo_contato as t
                                                    WHERE r.id_responsavel = rc.id_responsavel AND
                                                            t.id_tipo_contato = rc.id_tipo_contato AND
                                                            r.id_responsavel = " . $id_responsavel);
                                    $qnt = $programas->rows() == 0 ? 1 : $programas->rows();
                                }

                                for ($c = 0; $c < $qnt; $c++) {
                                    
                                    $programas->proximo();

                                
                                ?>

                                    <div class="input-group ml-0 mb-2" id="campo_dinamico">
                                        

                                        <select name="form_tipo_contato[]" id="form_tipo_contato" class="form-control form_tipo_contato" required>
                                            <? $form_elemento = $edit ? $form_tipo_contato :  $programas->record[0];
                                            include("../includes/inc_select_tipo_contato.php"); ?>

                                            <input type="hidden" name="id_responsavel_contato[]" id="id_responsavel_contato" value="<? echo $programas->record[3]; ?>" />

                                            <input type="text" name="form_valor_contato[]" id="form_valor_contato" class="form-control col-md-7" placeholder="Contato" value="<? if ($edit) echo $form_valor_contato[$c];
                                                                                                                                                                                else echo $programas->record[1] ?>" />
                                            <select name="form_principal[]" required id="form_principal" class="form-control col-md-1 form_principal" value="<? if ($edit) echo $form_principal[$c];
                                                                                                                                                                else echo $programas->record[2]; ?>">
                                                <option value="S" <? if ($edit && $form_principal == 'S') echo 'selected';
                                                                    else {
                                                                        if (!$edit && $programas->record[2] == "S") {
                                                                            echo 'selected';
                                                                        }
                                                                    }  ?>>Sim</option>
                                                <option value="N" <? if ($edit && $form_principal == 'N') echo 'selected';
                                                                    else {
                                                                        if (!$edit && $programas->record[2] == "N") {
                                                                            echo 'selected';
                                                                        }
                                                                    }  ?>>Não</option>
                                            </select>

                                            <div class="input-group-append">

                                                <? if ($c == $qnt - 1) { ?>

                                                    <a class="btn btn-success " id="novo_campo_dinamico" href="#form_principal">+</a>

                                                <? } else { ?>

                                                    <a class="btn btn-danger" id="remove_campo_dinamico" href="#form_principal">x</a>

                                                <? } ?>

                                            </div>

                                    </div>

                                    


                                <?
                               

                                }
                                
                             
                                ?>

                                
                               

                            </div>

                        </div>
                    </div>
                    

                </form>


            </div>
            <div class="modal-footer bg-light-2 text-center">
                <button type="button" id="btn_edit_responsavel" name="btn_edit_responsavel" class="btn btn-light btn_edit_responsavel">
                    <i class="fa-solid fa-check text-green"></i>
                    Save
                </button>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#novo_campo_dinamico").on('click', function(){
            var contador = 0;
            contador ++;

            console.log(contador);
            console.log($("#novo_campo_dinamico").val());
        })
        $("#btn_edit_responsavel").on('click', function(){
                var id_responsavel = $("#form_id_responsavel").val();
                var nome = $("#form_responsavel").val();
                var cpf = $("#form_mascara").val();
                var rg = $("#form_rg").val();
                var dt_nascimento = $("#form_dt_nascimento").val();
                var endereco = $("#form_endereco").val();
                var bairro = $("#form_bairro").val();
                var tipo_contato = [];
                var valor_contato = [];
                var principal = [];

                             
                

                $("#form_tipo_contato").each(function(i,e){
                    tipo_contato.push(e.value)
                    
                });

                $("#form_valor_contato").each(function(i,e){
                    valor_contato.push(e.value)
                    //console.log(valor_contato.push(i,e.value))
                    console.log(i);
                    i++
                });
            

                $("#form_principal").each(function(i,e){
                    principal.push(e.value)
                });

                var i = $("#form_valor_contato").val();
                //console.log(i); 

               // console.log(form_principal.size());


                $.ajax({
                    type: 'POST',
                    url:'../../../includes/ajax_edit_responsavel.php',
                    data:{
                        "id_responsavel": id_responsavel,
                        "nome": nome,
                        "cpf": cpf,
                        "rg": rg,
                        "dt_nascimento": dt_nascimento,
                        "endereco": endereco,
                        "bairro": bairro,
                        "tipo_contato": tipo_contato,
                        "valor_contato": valor_contato,
                        "principal": principal
                    },

                    beforeSend: function(){
                        
                    },
                    success: function(){
                        $("#RESPONSAVEL_edit_modal").modal('hide');
                            location.reload(true);
                           
                        
                    },
                    error:function(erro){

                    } 
                })
            })

    });           
</script>
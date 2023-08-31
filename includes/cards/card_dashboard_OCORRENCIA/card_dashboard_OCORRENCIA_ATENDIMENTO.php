<div class="row">

    <div class="col-12 col-md-12">
        <div class="card border">

            <div class="card-body p-0 m-0">

                <div class="card">

                    <div class="card-header bg-green">
                        <i class="fas fa-cogs"></i>
                        <?= "Sistema de denúncias" ?>
                    </div>
                    <div class="border">

                        <div class="row m-0 m-2">

                            <div class="col-12 col-md-6">

                                <div class="row">
                                    <p><b>Tipo de Atendimento:</b> <?= $descricao_ocorrencia  ?></p>
                                </div>

                            </div>

                            <div class="col-12 col-md-6">

                                <div class="row">
                                    <p><b>Relato:</b> <?= $relato_denunciante ?></p>
                                </div>

                            </div>

                            <div class="col-12 col-md-6">

                                <div class="row">
                                    <p><b>Contato do Comunicante:</b> <?= $telefone_denunciante ?></p>
                                </div>

                            </div>

                            <div class="col-12 col-md-6">

                                <div class="row">
                                    <p><b>Local:</b> <?= $endereco_recolhimento ?></p>
                                </div>

                            </div>

                            <div class="col-12 col-md-6">

                                <div class="row">
                                    <p><b>Ponto de Referência:</b> <?= $ponto_referencia ?></p>
                                </div>

                            </div>

                            <div class="col-12 col-md-6">

                                <div class="row">
                                    <p><b>Bairro:</b> <?= $bairro ?></p>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                <?

                $query_tramitacao = new Query($bd);

                $query_tramitacao->exec("SELECT (select toc.descricao from denuncias.tipo_ocorrencia as toc where ot.id_tipo_ocorrencia = toc.id_tipo_ocorrencia) as tipo_ocorrencia_descricao,
                                        ot.descricao, ot.status, ot.id_ocorrencia_tramitacao, (select org.descricao from orgao as org, denuncias.tipo_ocorrencia as toc
                                        where org.id_orgao = toc.id_orgao and ot.id_tipo_ocorrencia = toc.id_tipo_ocorrencia) as orgao,  ot.id_tipo_ocorrencia, ot.hr_alteracao as hora,ot.login,
                                        dt_alteracao as data, ot.descricao as relato_denunciante
                                        from denuncias.ocorrencia_tramitacao as ot where id_ocorrencia = $id_ocorrencia order by ot.id_ocorrencia_tramitacao asc");

                $n = $query_tramitacao->rows();
                $numero_etapa = $n;


                while ($n--) {
                    $query_tramitacao->proximo();
                    $id_ocorrencia_tramitacao = $query_tramitacao->record['id_ocorrencia_tramitacao'];
                    $descricao_tramitacao = $query_tramitacao->record['descricao'];
                    // echo $descricao_tramitacao;


                ?>
                    <div class="card">

                        <div class="card-header bg-green">
                            <div class="row">
                                <div class="col-12">
                                    <i class="fas fa-hotel"></i>
                                    <?= $query_tramitacao->record['orgao'] ?>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row m-0 m-2">

                                <div class="col-12 col-md-6">

                                    <div class="row">
                                        <p><b>Encaminhado por:</b>
                                            <? if ($numero_etapa == ($n + 1)) {
                                                echo "Sistema de denúncias";
                                            } else {
                                                echo $encaminhado_anterior;
                                            }
                                            ?></p>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <div class="row">
                                        <p><b>Tipo de Ocorrência:</b>
                                            <?
                                            if ($numero_etapa == ($n + 1)) {
                                                echo $nome_tipo_ocorrencia;
                                            } else {
                                                echo $query_tramitacao->record['tipo_ocorrencia_descricao'];
                                            }
                                            ?>
                                        </p>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6">

                                    <div class="row">
                                        <p><b>Usuário:</b>
                                            <?
                                            if ($numero_etapa == ($n + 1)) {
                                                echo 'administrador';
                                            } else {

                                                echo $query_tramitacao->record['login'];
                                            }  ?>
                                        </p>

                                        </p>
                                    </div>

                                </div>

                                <div class="col-12 col-md-3">

                                    <div class="row">
                                        <p><b>Data:</b>
                                            <?
                                            echo date('d/m/Y', strtotime($query_tramitacao->record['data']));

                                            ?>
                                        </p>
                                    </div>

                                </div>
                                <div class="col-12 col-md-3">

                                    <div class="row">
                                        <p><b>Hora:</b>
                                            <?
                                            echo $query_tramitacao->record['hora'];
                                            ?>
                                        </p>
                                    </div>

                                </div>

                                <div class="col-12 col-md-12">

                                    <div class="row">
                                        <p><b>Descrição do Atendimento:</b> <? echo $query_tramitacao->record['descricao']; ?></p>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                <?

                    $encaminhado_anterior = $query_tramitacao->record['orgao'];
                    $tipo_ocorrencia_descricao_anterior = $query_tramitacao->record['tipo_ocorrencia_descricao'];
                }

                if ($query_tramitacao->record['status'] == "A") {
                ?>

                    <input type="hidden" name="id_tipo_ocorrencia" id="id_tipo_ocorrencia" value="<?= $query_tramitacao->record['id_tipo_ocorrencia']; ?>">


                    <div class="form-row m-2">
                        <div class="form-group col-12 col-md-12">
                            <label for="form_descricao"><span class="text-danger">*</span> Descrição do Atendimento</label>
                            <textarea class="form-control" name="form_descricao" id="form_descricao" cols="30" rows="3" required><? if ($erro) echo $form_descricao; ?></textarea>
                        </div>

                        <div class="form-group col-12 col-md-12 div_secretaria_encaminhar d-none">
                            <label for="form_orgao_responsavel"><span class="text-danger">*</span>Secretaria Responsável</label>
                            <select class="form-control" name="form_orgao_responsavel" id="form_orgao_responsavel" required>
                                <?
                                $_flag = 'A'; //A == Administratativo.
                                include "../includes/inc_select_tipo_ocorrencia.php";
                                ?>
                            </select>
                        </div>

                        <div class="col-12 pb-3">

                            <?
                            if ($query_tramitacao->record['status'] == 'A') {
                            ?>
                                <div class="col-12 p-0 m-0">
                                    <table class="table border">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">Arquivo</th>
                                                <th scope="col">Descrição</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody class="arquivos_ocorrencia_atendimento">

                                        </tbody>
                                    </table>


                                </div>


                            <?

                            }
                            ?>
                        </div>

                        <div class="col-2 text-left">
                            <?
                            if ($query_tramitacao->record['status'] == 'A') {
                            ?>
                                <div>
                                    <button type="button" class="btn btn-sm btn-green p-2" title="Adicionar imagens" id="add_imagem" data-toggle="modal" data-target="#MODAL_ADD_IMAGEM_TRAMITACAO">
                                        <strong>Adicionar Arquivos</strong>
                                    </button>
                                </div>
                            <?
                            }
                            ?>
                        </div>
                        <div class="col-10 text-right">
                            <div data-toggle="buttons">

                                <label class="btn  btn-green active form_concluido_label">
                                    <input type="checkbox" name="form_concluido" id="form_concluido" required> Concluído
                                </label>
                                <label class="btn  btn-warning active form_encaminhar_label">
                                    <input type="checkbox" name="form_encaminhar" id="form_encaminhar" required> Encaminhar
                                </label>
                            </div>


                        </div>
                    <?
                }
                    ?>

                    </div>
                    <?
                    if ($query_tramitacao->record['status'] == "A") {
                    ?>
                        <div class="card-footer bg-light-2">
                            <?

                            $btns = array('clean', 'save');
                            include('../includes/dashboard/footer_forms.php');

                            ?>
                        <?
                    } else {

                        ?>



                            <button class="btn btn-light" type="submit" name="reeditar">
                                <i class="fas fa-check text-green"></i>
                                <span>Retomar Edição</span>
                            </button>
                        <?

                    }

                        ?>

                        </div>

            </div>

        </div>


        <div class="modal fade" id="MODAL_ADD_IMAGEM_TRAMITACAO" tabindex="-1" aria-labelledby="MODAL_ADD_IMAGEM_TRAMITACAO">

            <div class="modal-dialog modal-xl">

                <div class="modal-content">

                    <div class="modal-header bg-green">

                        <h5 class="modal-title">
                            <i class="fas fa-plus"></i>
                            Adicionar <span class="type_local"></span> Arquivos
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="modal-body">
                            <div class="form-group col-12 col-md-12">
                                <label for="form_modal_add_arquivos_ocorrencia">Arquivo</label>
                                <input type="file" class="form-control" id="form_modal_add_arquivos_ocorrencia" name="form_modal_add_arquivos_ocorrencia">
                            </div>

                            <div class="form-group col-12 col-md-12">
                                <label for="form_modal_descricao_arquivos_ocorrencia">Desrição</label>
                                <input type="text" class="form-control" id="form_modal_descricao_arquivos_ocorrencia" name="form_modal_descricao_arquivos_ocorrencia">
                            </div>
                        </div>
                        <div class="modal-footer bg-light-2 text-center">
                            <input class="btn btn-light" type="reset" name="clear" value="Limpar">

                            <button type="button" id="add_arquivos_ocorrencia" class="btn btn-light">
                                <i class="fa-solid fa-filter text-green"></i>
                                Salvar
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </div>
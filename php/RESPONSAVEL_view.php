<div class="modal fade text-left" id="RESPONSAVEL_view" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-xl" role="document">

        <div class="modal-content">

            <!-- Depois criar um botão no Responsavel_cover para realizar a edição "RESPONSAVEL_viewDados.php" -->
            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="modal-header bg-light-2">
                    <h5 class="modal-title">
                        <i class="fas fa-filter text-green"></i>
                        Filtrar Responsáveis
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-row">

                        <div class="form-group col-12 col-md-6 ">
                            <label for="form_responsavel">Nome: </label>
                            <input type="text" class="form-control" name="form_responsavel" id="form_responsavel" value="<? if ($erro) echo $form_responsavel; ?>">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label for="form_mascara">CPF: </label>
                            <input type="text" class="form-control form_mascara " name="form_mascara" id="form_mascara" value="<? if ($erro) echo $form_mascara; ?>">
                            <input type="hidden" class="form_mascara_unmask" name="form_mascara_unmask" value="<? if ($erro) echo $form_mascara_unmask; ?>">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label for="form_rg">RG: </label>
                            <input type="text" class="form-control" name="form_rg" id="form_rg" value="<? if ($erro) echo $form_rg; ?>">
                        </div>

                    </div>
                </div>


                <div class="modal-footer bg-light-2 text-center">
                    <button type="submit" name="filter" class="btn btn-light">
                        <i class="fa-solid fa-filter text-green"></i>
                        Filtrar
                    </button>
                </div>

            </form>

            <script src="../assets/js/jquery.js"></script>
            <script src="../assets/js/jquery.mask.js"></script>
            <script type="text/javascript">
                $('#form_mascara').mask('000.000.000-00');
                $('#form_rg').mask('00000000000000');
                $(document).on('change', '.form_tipo_contato', function() {
                    var mascara = $(this).find(':selected').data('mascara');
                    if (mascara == 'email') {
                        $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type', 'email');
                    } else {
                        $(this).parents('#campo_dinamico').find('.form_valor_contato').attr('type', 'text').mask(mascara);
                    }
                });
            </script>
        
        </div>

    </div>

</div>
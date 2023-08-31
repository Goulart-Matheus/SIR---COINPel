<style>
    /* Estilo para a miniatura do PDF */
    .pdf-thumbnail {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 198px;
        /* Altura desejada para a miniatura */
        width: 100%;
        overflow: hidden;
        /* Para garantir que a imagem não estoure o espaço */
    }

    .pdf-thumbnail img {
        max-height: 100%;
        max-width: 100%;
    }

    /* Estilo para o ícone do Font Awesome */
    .pdf-thumbnail {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .pdf-thumbnail .fa-file-pdf {
        font-size: 3rem;
        /* Ajuste o tamanho do ícone conforme necessário */
    }

    /* Estilo para o nome do arquivo */
    .pdf-thumbnail .file-name {
        text-align: center;
        font-size: 1rem;
    }
</style>


<?

$query_anexo_ocorrencia = new Query($bd);

$query_anexo_ocorrencia->exec("SELECT ao.tipo_arquivo, ao.arquivo, ao.nome FROM denuncias.arquivo_ocorrencia as ao, 
                                     denuncias.ocorrencia as o, denuncias.ocorrencia_tramitacao as ot
                               WHERE o.id_ocorrencia = ot.id_ocorrencia
                               AND   ao.id_ocorrencia_tramitacao = ot.id_ocorrencia_tramitacao
                               AND   o.id_ocorrencia = $id_ocorrencia");

$n_anexo = $query_anexo_ocorrencia->rows();

?>

<link rel="stylesheet" href="../assets/css/lightbox.css">

<div class="card border">

    <div class="card-header bg-green">

        <div class="row">

            <div class="col-md-6 text-left">

                <i class="fas fa-paperclip pr-1"></i> ANEXOS

            </div>

        </div>

    </div>

    <div class="col-12 card-body p-0 m-0">


        <!-- Inicio -->

        <div class="col-12 text-center pt-5 text-dark d-none" id="nenhum_anexo_vinculado">

            <h5 class="mb-5">Sem anexo vinculado</h5>

        </div>

        <?

        if ($n_anexo == 0) {
        ?>

            <div class="col-12 text-center pt-5 text-dark" id="nenhum_anexo_vinculado">


                <h5 class="mb-5">Sem anexo vinculado</h5>

            </div>
        <?
        } else {

        ?>


            <div class="row" style="margin: 0px;" id="gallery">
                <?php
                while ($n_anexo--) {
                    $query_anexo_ocorrencia->proximo();
                    $tipo_arquivo = $query_anexo_ocorrencia->record['tipo_arquivo'];
                    $arquivo_base64 = $query_anexo_ocorrencia->record['arquivo'];
                    $is_pdf = ($tipo_arquivo === 'application/pdf');

                    // Se for PDF, gere um identificador único para a div e o modal
                    $uniqueId = uniqid();

                    // Exibe a miniatura da imagem estática
                    if (!$is_pdf) {
                ?>
                        <div class="col-12 col-md-3 col-sm-12" id="anexo_imagem" style="padding: 0px; max-height: 200px; width: auto">
                            <a class="example-image-link" href="data:<?php echo $tipo_arquivo ?>;base64, <?php echo $arquivo_base64 ?>" data-lightbox="example-set" data-title="Click the right half of the image to move forward.">
                                <img class="img-thumbnail w-100 h-100 p-1" src="data:<?php echo $tipo_arquivo ?>;base64, <?php echo $arquivo_base64 ?>">
                            </a>
                        </div>
                    <?php
                    } else {
                    ?>
                        <!-- Se for PDF, exibe a imagem estática e abre o modal ao clicar -->
                        <div class="col-12 col-md-3 col-sm-12 img-thumbnail m-0" id="anexo_imagem" style="padding: 0px;">
                            <button class="btn btn-link pdf-thumbnail" data-toggle="modal" data-target="#pdfModal-<?php echo $uniqueId; ?>">
                                <span class="fa-solid fa-file-pdf text-green"></span>
                                <span class="file-name text-dark"><?php echo $query_anexo_ocorrencia->record['nome'] ?></span>
                            </button>
                        </div>

                        <!-- Modal para exibir o PDF completo -->
                        <div class="modal fade" id="pdfModal-<?php echo $uniqueId; ?>" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel-<?php echo $uniqueId; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="pdfModalLabel-<?php echo $uniqueId; ?>">PDF Viewer</h5>

                                        <button type="button" class="close pl-1" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <iframe src="data:<?php echo $tipo_arquivo ?>;base64, <?php echo $arquivo_base64; ?>" frameborder="0" scrolling="auto" width="100%" height="600"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        <?
        }

        ?>

        <!-- Fim-->

    </div>

</div>

<script src="../assets/js/jquery.js"></script>
<script src="../assets/js/lightbox.js"></script>

<script>
    // Função para exibir o modal com o PDF
    function showPdfModal(pdfDataUri) {
        document.getElementById('pdfFrame').src = pdfDataUri;
        $('#pdfModal').modal('show');
    }

    // Impede que o clique no botão recarregue a página
    $('.pdf-thumbnail').click(function(e) {
        e.preventDefault();
    });
</script>
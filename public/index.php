<?

require_once('../class/Layout.php');

$layout = new Layout('SDR - Denúncias', "Secretaria Municipal de Desenvolvimento Rural", "Denúncias");

echo $layout->getHeader();

?>

<div class="row">

    <div class="col-12 col-md-9">



        <div class="row mb-3">

            <div class="col-12">

                <p>
                    Este canal é exclusivo para combater(proteger) e denunciar maus tratos aos animais(ANIMAIS EM RISCO). <br>Os Dados Pessoais solicitados serão <strong>mantidos em sigilo</strong> e somente utilizados para o propósito que motivou o cadastro.
                    <br>Ao fazer a denúncia, você receberá um número de <strong>PROTOCOLO</strong>, que também será enviado para seu e-mail informado na denúncia, <br>com o PROTOCOLO será possível acompanhar o processo.
                </p>

            </div>

        </div>

        <div class="row mb-3">

            <div class="col-12">

                <p>
                    Se você conhece casos de violência contra animais <strong>DENUNCIE !!!</strong>
                </p>

            </div>

        </div>

    </div>

    <div class="col-12 col-md-3">

        <div class="row">

            <div class="col-6 col-md-12 mb-md-2 item-home" onclick="window.location.href = 'OCORRENCIA_form.php'">
                <div class="card border p-2 text-center entered">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h1>
                                    <i class="fas fa-eye"></i>
                                </h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                Fazer <br> Denúncias
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-12 mb-md-2 item-home" onclick="window.location.href = 'CONSULTA_PROTOCOLO.php'">
                <div class="card border p-2 text-center entered">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h1>
                                    <i class="fas fa-search"></i>
                                </h1>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                Consultar <br> Protocolo
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- MODELO DE BOTÃO PARA USAR NO SISTEMA -->

    <!-- <button type="button" class="btn btn-pelotas">Abrir</button> -->

    <?

    echo $layout->getFooter();

    ?>

    <script>
        $(document).ready(function() {

            $('#modal_error').find('.col-12').html('<h4><strong><i class="fa-solid fa-shield-halved text-danger"></i> IMPORTANTE</strong></h4>')
            $('#modal_error').find('#message').html('<div class="col-12"><h5>Os Dados Pessoais solicitados serão mantidos em sigilo e somente utilizados para o propósito que motivou o cadastro.</h5></div><div class="col-12 text-md-end"></div>');
            $('#modal_error').find('.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
            $('#modal_error').modal('show');

        });
    </script>
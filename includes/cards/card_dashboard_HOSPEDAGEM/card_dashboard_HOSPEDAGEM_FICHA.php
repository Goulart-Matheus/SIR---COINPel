<?php

?>

<div class="card">

    <div class="row">

        <div class="col-12 col-md-12 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon ">
                    <?
                    if ($situacao == "S") {
                        echo "<i class='fas fa-circle text-green'></i>";
                    } else {
                        echo "<i class='fas fa-circle text-light'></i>";
                    }
                    ?>
                </span>



                <div class="info-box-content ">




                    <?
                    if ($situacao == "S") {
                        echo "<h3 class=''>Em Atendimento</h3>";
                    } else {
                        echo "<h3 class=''>Atendimento Finalizado</h3>";
                    }
                    ?>


                </div>

                <span class="info-box-icon "><a href="HOSPEDAGEM_edit.php?id_hospedagem='<?= $id_hospedagem ?>'"><i class="fa-solid fa-pen"></i></a></span>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">

                <span class="info-box-icon  bg-green"><i class="fas fas fa-portrait"></i></span>

                <div class="info-box-content">

                    <span class="info-box-text text-green">Numero da ficha:</span>


                    <?= $nro_ficha ?>




                </div>

            </div>

        </div>



        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">

                <span class="info-box-icon  bg-green"><i class="fas fa-info-circle"></i></span>

                <div class="info-box-content">

                    <span class="info-box-text text-green">Numero do chip:</span>

                    <?= $nro_chip ?>


                </div>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">
            <div class="info-box shadow">

                <span class="info-box-icon  bg-green"><i class="fas fas fa-portrait"></i></span>

                <div class="info-box-content">

                    <span class="info-box-text text-green">Responsavel:</span>


                    <?= $responsavel_nome ?>




                </div>

            </div>

        </div>


        <div class="col-12 col-md-6 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-dog"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Espécie:</span>

                    <?= $especie_descricao  ?>



                </div>

            </div>

        </div>

        <div class="col-12 col-md-6 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-dog"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Pelagem:</span>

                    <?= $pelagem  ?>



                </div>

            </div>

        </div>


        <div class="col-12 col-md-6 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-calendar-days"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Data de Entrada:</span>


                    <?= $dt_entrada ?>


                </div>

            </div>

        </div>

        <div class="col-12 col-md-6 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-calendar-days"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Data de Retirada:</span>

                    <?


                    if ($dt_retirada === "01/01/0001") {

                        echo "<p>--------</p>";
                    } else {

                        echo $dt_retirada;
                    }
                    ?>



                </div>

            </div>

        </div>

        <div class="col-12 col-md-12 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon  bg-green"><i class="fas fa-location-dot"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Endereço de Recolhimento:</span>

                    <?= $endereco_recolhimento ?> - <?= $bairro ?>



                </div>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fab fa-asymmetrik"></i></span>


                <div class="info-box-content">


                    <span class="info-box-text text-green">Reincidências Anteriores:</span>



                    <?= $nro_reincidencias ?>



                </div>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fab fa-asymmetrik"></i></span>


                <div class="info-box-content">


                    <span class="info-box-text text-green">URM Referencia:</span>
                    <? switch ($urm_mes) {
                        case '1':
                            echo "Janeiro" . $urm_ano;
                            break;
                        case '2':
                            echo "Fevereiro/" . $urm_ano;
                            break;
                        case '3':
                            echo "Março/" . $urm_ano;
                            break;
                        case '4':
                            echo "Abril/" . $urm_ano;
                            break;
                        case '5':
                            echo "Maio/" . $urm_ano;
                            break;
                        case '6':
                            echo "Junho/" . $urm_ano;
                            break;
                        case '7':
                            echo "Julho/" . $urm_ano;
                            break;
                        case '8':
                            echo "Agosto/" . $urm_ano;
                            break;
                        case '9':
                            echo "Setembro/" . $urm_ano;
                            break;
                        case '10':
                            echo "Outubro/" . $urm_ano;
                            break;
                        case '11':
                            echo "Novembro/" . $urm_ano;
                            break;
                        case '12':
                            echo "Dezembro";
                            break;
                        default:
                            "Nenhuma Urm de Referência";
                            break;
                    }
                    ?>






                </div>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-money-bill"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Valor:</span>

                    <?= "R$ " . $hospedagem_valor;  ?>



                </div>

            </div>

        </div>

        

        


    </div>

</div>
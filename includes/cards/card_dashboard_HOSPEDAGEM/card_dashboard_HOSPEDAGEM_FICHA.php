<?php

?>

<div class="card">

    <div class="row">


        

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-calendar-days"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Data de Entrada:</span>


                    <?= $dt_entrada ?>


                </div>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">

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

        <!---

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
        ---->

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-money-bill"></i></span>


                <div class="info-box-content">

                    <span class="info-box-text text-green">Valor:</span>

                    <?  if($nro_reincidencias == 0){echo "R$ ".$hospedagem_valor;}else{
                                           echo "R$ ".number_format($hospedagem_valor * $nro_reincidencias,2,',','.') ;}  ?>



                </div>

            </div>

        </div>

        

        


    </div>

</div>
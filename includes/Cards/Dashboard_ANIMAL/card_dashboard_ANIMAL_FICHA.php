<?

$query->exec("SELECT 
                        a.id_animal , a.nro_ficha , a.nro_chip , a.sexo, a.observacao, p.descricao , e.descricao
                        FROM 
                            animal a , pelagem p , especie e
                        WHERE 
                            id_animal = $id_animal
                        AND 
                            a.id_pelagem = p.id_pelagem 
                        AND 
                            a.id_especie = e.id_especie

                    ");

$query->result($query->linha);

$id_animal         = $query->record[0];
$nro_ficha         = $query->record[1];
$nro_chip          = $query->record[2];
$id_pelagem        = $query->record[5];
$id_especie        = $query->record[6];
$sexo              = $query->record[3];
$observacao        = $query->record[4];


?>


<div class="card">
    
    <div class="row">

        <div class="col-12 col-md-12 text-center rounded">

            <div class="info-box shadow">

                <span class="info-box-icon  bg-green"><i class="fas fas fa-portrait"></i></span>

                <div class="info-box-content">

                    <h4><span class="info-box-text text-green">ESPÉCIE:</span></h4>

                    <h4 style="text-transform:uppercase"> <?= $query->record[6] ?></h4>

                </div>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">

                <span class="info-box-icon  bg-green"><i class="fas fa-info-circle"></i></span>

                <div class="info-box-content">

                    <H5>
                        <span class="info-box-text text-green">NÚMERO DO CHIP:</span>

                        <?= $query->record[2] ?>
                    </H5>

                </div>

            </div>

        </div>
        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon  bg-green"><i class="fas fa-info-circle"></i></span>


                <div class="info-box-content">

                    <H5>
                        <span class="info-box-text text-green">NÚMERO DA FICHA:</span>

                        <?= $query->record[1] ?>
                    </H5>

                </div>

            </div>

        </div>
        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-info-circle"></i></span>


                <div class="info-box-content">

                    <H5>
                        <span class="info-box-text text-green">SEXO:</span>

                        <?= $query->record[3] == "M" ? "MACHO" : "FÊMEA" ?>
                    </H5>

                </div>

            </div>

        </div>

    </div>

</div>
<?

$query->exec("SELECT 
                     r.id_responsavel , r.nome , r.cpf , r.rg , r.dt_nascimento, r.endereco, b.descricao 
                     FROM 
                         Responsavel r, Bairro b
                     WHERE 
                         id_responsavel = $id_responsavel
                     AND 
                         b.id_bairro = r.id_bairro  
                 ");

$query->result($query->linha);

$id_responsavel         = $query->record[0];
$nome                   = $query->record[1];
$cpf                    = $query->record[2];
$rg                     = $query->record[3];
$dt_nascimento          = $query->record[4];
$endereco               = $query->record[5];
$bairro                 = $query->record[6];


?>



<div class="card">
    <div class="form-row">


    </div>
    <div class="row">



        <div class="col-12 col-md-12 text-center rounded">

            <div class="info-box shadow">

                <span class="info-box-icon  bg-green"><i class="fas fas fa-portrait"></i></span>

                <div class="info-box-content">

                    <h4><span class="info-box-text text-green">NOME:</span></h4>

                    <h4 style="text-transform:uppercase"> <?= $query->record[1] ?></h4>

                </div>

            </div>

        </div>

        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">

                <span class="info-box-icon  bg-green"><i class="fas fa-info-circle"></i></span>

                <div class="info-box-content">

                    <H5>
                        <span class="info-box-text text-green">CPF:</span>

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
                        <span class="info-box-text text-green">RG:</span>

                        <?= $query->record[3] ?>
                    </H5>

                </div>

            </div>

        </div>
        <div class="col-12 col-md-4 text-center rounded">

            <div class="info-box shadow">
                <span class="info-box-icon bg-green"><i class="fas fa-home"></i></span>


                <div class="info-box-content">
                    <h5>
                        <span class="info-box-text text-green">ENDEREÇO:</span>

                        <?= $query->record[5] ?> / Bairro: <?= $query->record[6] ?>
                    </h5>



                </div>

            </div>

        </div>




    </div>

</div>
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

        

    <div class="col-12 text-center rounded">


            <div class="info-box bg-green">

                <span class="info-box-icon"><i class="fas fa-portrait"></i></span>

                <div class="info-box-content">

                    <span class="info-box-text text-center py-3">
                    <?= $query->record[1] ?>
                    </span>

                </div>

            </div>

        </a>

    </div>

    <div class="col-12 col-md-4 text-center rounded">

        <div class="info-box shadow">

             <span class="info-box-icon  bg-green"><i class="fas fa-info-circle"></i></span>

            <div class="info-box-content">

                <span class="info-box-text">CPF:</span>

                
                     <?= $query->record[2] ?>
                

                <span class="info-box-number text-center">
                    <h2  class="p-0 m-0"><?= $c ?></h2>
                </span> 

            </div>

        </div>

    </div>
    <div class="col-12 col-md-4 text-center rounded">

        <div class="info-box shadow">
        <span class="info-box-icon  bg-green"><i class="fas fa-info-circle"></i></span>
             

            <div class="info-box-content">

                <span class="info-box-text">RG:</span>

                <?= $query->record[3] ?>

                <span class="info-box-number text-center">
                    <h2  class="p-0 m-0"><?= $c ?></h2>
                </span> 

            </div>

        </div>

    </div>
    <div class="col-12 col-md-4 text-center rounded">

        <div class="info-box shadow">
        <span class="info-box-icon bg-green"><i class="fas fa-home"></i></span>
             

            <div class="info-box-content">
            
                <span class="info-box-text">Endereço:</span>

                <?= $query->record[5] ?>

                <span class="info-box-number text-center">
                    <h2  class="p-0 m-0"><?= $c ?></h2>
                </span> 

            </div>

        </div>

    </div>
    

    

</div>

</div>
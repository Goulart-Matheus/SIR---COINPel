<?

    include('../includes/session.php'               );
    include('../includes/variaveisAmbiente.php'     );
    include_once('../includes/dashboard/header.php' );
    include('../class/class.tab.php'                );
    include('../function/function.date.php'         );

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

    $tab = new Tab();

    $tab->setTab('Pesquisar'               , 'fas fa-search', 'RESPONSAVEL_view.php?id_responsavel='                   .$id_responsavel );
    $tab->setTab($query->record[1]         , $query->record[5]    , $_SERVER['PHP_SELF'] . '?id_responsavel='                .$id_responsavel );
    $tab->setTab('Editar'                  , 'fas fa-pencil-alt'  , 'RESPONSAVEL_edit.php?id_responsavel='                   .$id_responsavel );
    $tab->printTab($_SERVER['PHP_SELF']. '?id_responsavel='.$query->record[0]);

?>

    <section class="content">

            <div class="card p-0">

            <div class="card-header border-bottom-1 mb-3 bg-light-2">

                    <div class="text-center">
                        <h4><?= $auth->getApplicationDescription($_SERVER['PHP_SELF']) ?></h4>
                    </div>

                    <div class="row text-center">
                        <div class="col-12 col-sm-4 offset-sm-4">
                            <? if ($erro) echo callException($erro, 2); ?>
                        </div>
                    </div>

            </div>  

                <div class="card-body pt-3">
                     

                    <div class="row">

                        <div class="col-12">

                            <? include("../includes/cards/dashboard_RESPONSAVEL/card_dashboard_RESPONSAVEL_FICHA.php"); ?>

                        </div>

                    </div>

                     <div class="row">

                        <div class="col-12 col-md-3">
                    
                            <? include("../includes/cards/dashboard_RESPONSAVEL/card_dashboard_RESPONSAVEL_INFO.php"); ?>

                        </div>

                         <div class="col-12 col-md-9">
                    
                            <? include("../includes/cards/dashboard_RESPONSAVEL/card_dashboard_RESPONSAVEL_ANIMAL.php"); ?>

                        </div>
                       
                         <div class="col-12 col-md-12">
                    
                            <? include("../includes/cards/dashboard_RESPONSAVEL/card_dashboard_RESPONSAVEL_OCORRENCIA.php"); ?>

                        </div>  

                    </div> 

                </div>

            </div>

    </section> 
   

<? 
    include_once('../includes/dashboard/footer.php'); 

    function isNum($val)
    {
        if(is_numeric($val))
        {
            if(strtoupper($val) != "NAN")
            {
                return intval($val);
            }
        }
        return 0;

    }
?>
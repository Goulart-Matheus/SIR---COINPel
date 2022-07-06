<?
    $totais = [];

    $query_totais = new Query($bd);
    $query_totais->exec("SELECT COUNT(id_animal) FROM animal");
    $query_totais->proximo();

    $totais['cadastrados'] = $query_totais->record[0];

    $query_totais = new Query($bd);
    $query_totais->exec("SELECT MAX(id_animal) FROM animal");
    $query_totais->proximo();

    $totais['ult_id'] = $query_totais->record[0];

    $query_totais = new Query($bd);
    $query_totais->exec("SELECT COUNT(id_) FROM animal WHERE id_animal = 6");
    $query_totais->proximo();

    $totais['recuperados'] = $query_totais->record[0];

    $query_totais = new Query($bd);
    $query_totais->exec("SELECT COUNT(id_paciente) FROM paciente_evolucao WHERE id_evolucao = 3");
    $query_totais->proximo();

    $totais['obitos'] = $query_totais->record[0];
/*
    $query_totais = new Query($bd);
    $query_totais->exec("SELECT COUNT(pe.id_paciente) 
                           FROM paciente_evolucao pe
                          WHERE pe.id_evolucao = 4
                            AND id_paciente_evolucao = (SELECT MAX(id_paciente_evolucao) FROM paciente_evolucao WHERE id_paciente = pe.id_paciente)
                        ");
    $query_totais->proximo();
*/
    $totais['Cadastrados'] = $totais['positivos'] - $totais['recuperados'] - $totais['obitos'];

?>

<div class="row">

    <div class="col-md-6">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-danger"><i class="fas fa-plus"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Positivos </span>
                <span class="info-box-number"><?= $totais['positivos'] ?></span>
                <span class="info-box-text text-right small">Último ID: <?= $totais['ult_id'] ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-green"><i class="fas fa-home"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Recuperados</span>
                <span class="info-box-number"><?= $totais['recuperados'] ?></span>
                <span class="info-box-text text-right small">&nbsp;</span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-6">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-gray"><i class="fas fa-house-user"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Isolados</span>
                <span class="info-box-number"><?= $totais['isolados'] ?></span>
                <span class="info-box-text text-right small">&nbsp;</span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-dark"><img src="../../../assets/images/icone-laco-01.png"></span>

            <div class="info-box-content">
                <span class="info-box-text">Óbitos</span>
                <span class="info-box-number"><?= $totais['obitos'] ?></span>
                <span class="info-box-text text-right small">&nbsp;</span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>

</div>
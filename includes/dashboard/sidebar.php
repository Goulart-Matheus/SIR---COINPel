<?
$user =$auth->getUser();
$menu =new Query($bd);

$menu->exec("SELECT	DISTINCT a.superior,a.tipo,a.descricao,a.codaplicacao,a.fonte,a.nova_janela, a.icone
                FROM 	usuario_grupo ug, 
                	grupo_aplicacao ga, 
                	aplicacao a
                WHERE 	ug.login ='$user' AND
                	ug.codgrupo = ga.codgrupo     AND
                	ga.codaplicacao = a.codaplicacao  AND
                	a.situacao = 1
                ORDER BY 1,2 DESC,3
");
$n=$menu->rows();
while($n--){
    $menu->proximo();
    $a_superior[]=$menu->record[0];
    $a_tipo[]=$menu->record[1];
    $a_descricao[]=$menu->record[2];
    $a_codaplicacao[]=$menu->record[3];
    $a_fonte[]=$menu->record[4];
    $a_nova_janela[]=$menu->record[5];
    $a_icone[]=$menu->record[6];
}

function monta_menu($a_superior,$a_tipo,$a_descricao,$a_codaplicacao,$a_fonte,$superior,$a_nova_janela, $a_icone){
    for($i=0; $i<sizeof($a_superior); $i++){
        if ($a_superior[$i]==$superior){
            if ($a_tipo[$i]=='m'){
                ?>
                <li class="nav-item has-treeview">
                    <a  href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon <?echo $a_icone[$i]?>"></i>
                        <p>
                            <?echo $a_descricao[$i];?>
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?
                        monta_menu($a_superior,$a_tipo,$a_descricao,$a_codaplicacao,$a_fonte,$a_codaplicacao[$i],$a_nova_janela, $a_icone);
                        ?>
                    </ul>
                </li>
                <?
            }
            else {
                if ($a_nova_janela[$i]=="S") $target="target='_blank'";
                else $target="";
                ?>
                <li class="nav-item">
                    <a href="<?echo $a_fonte[$i]?>" <?echo $target;?> class="nav-link">
                        <i class="nav-icon <?echo $a_icone[$i]?>"></i>
                        <p><?echo $a_descricao[$i];?></p>
                    </a>
                </li>
                <?
            }
        }
    }
}
?>
<aside class="main-sidebar sidebar-dark-dark levation-4">
    <a href="../index.php" class="brand-link navbar-dark text-sm">
        <img src="../assets/images/logo-short.png"
             alt="Logo <? echo $system->getTitulo(); ?>"
             class="brand-image"
             style="opacity: .8">
        <span class="brand-text font-weight-light"><? echo $system->getTitulo(); ?></span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <a href="formPass.php">
                    <img src="../assets/images/user-default.png" class="img-circle elevation-2"  alt="Imagem perfil">
                </a>
            </div>
            <div class="info">
                <a href="formPass.php" class="d-block"><?echo $auth->getUserName();?></a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu"
                data-accordion="false">
                <?
                monta_menu($a_superior,$a_tipo,$a_descricao,$a_codaplicacao,$a_fonte,1,$a_nova_janela, $a_icone);
                ?>
            </ul>
        </nav>
    </div>
</aside>

<?

$col = "col-md-" . (12 / sizeof($btns));

?>
<div class="row">

    <?
    for ($i = 0; $i < sizeof($btns); $i++) {
        switch ($i) {
            case 0:
                $align = "text-left";
                break;
            case sizeof($btns) - 1:
                $align = "text-right";
                break;
            default:
                $align = "text-center";
                break;
        }

    ?>
        <div class="<?= $col . " " . $align ?>">
            <?= buttons($btns[$i]) ?>
        </div>
    <?
    }
    ?>

</div>
<?

function buttons($type)
{

    $buttons = array(

        'save'      => '<button class="btn btn-light save_validate" type="submit" name="add">
                                <i class="fas fa-check text-green"></i>
                                <span>Salvar</span>
                            </button>',

        'edit'      => '<button class="btn btn-light" type="submit" name="edit">
                                <i class="fas fa-check text-green"></i>
                                <span>Salvar Edição</span>
                            </button>',

        'remove'    => '<button class="btn btn-light" type="submit" name="remove">
                                <i class="fas fa-trash text-danger"></i>
                                <span>Remover</span>
                            </button>',

        'selectAll' =>  '<button class="btn btn-light" type="button" id="selectButton" onClick="toggleSelect(); return false">
                                <i class="fa-solid fa-square-check text-warning"></i>
                                <span>Selecionar Todos</span>
                            </button>',

        'clean'     =>  '<button class="btn btn-light" type="reset" name="clear">
                                <i class="fa-solid fa-times text-secondary"></i>
                                <span>Limpar Campos</span>
                            </button>',
                            
        'reload'     =>  '<button class="btn btn-light" type="reset" name="clear">
                                <i class="fa-solid fa-arrows-rotate"></i>
                                <span>Restaurar</span>
                            </button>',

        'enable'    =>  '<button class="btn btn-light" type="submit" name="habilitar">
                                <i class="fa-solid fa-user-check text-green"></i>
                                <span>Habilitar</span>
                            </button>',

        'disable'   =>  '<button class="btn btn-light" type="submit" name="desabilitar">
                                <i class="fa-solid fa-user-times text-danger"></i>
                                <span>Desabilitar</span>
                            </button>',
    );

    return $buttons[$type];
}
?>
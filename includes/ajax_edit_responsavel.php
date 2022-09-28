<?php

include('./session.php');
include('./variaveisAmbiente.php');
include("../function/function.date.php");

$query = new Query($bd);

error_reporting(E_ALL);
header("Content-type: application/json");

$id_responsavel = $_POST['id_responsavel'];
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];
$dt_nascimento['dt_nascimento'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$tipo_contato[] = $_POST['tipo_contato'];
$valor_contato = $_POST['valor_contato'];
$principal[] = $_POST['principal'];

$query->begin();

                            $itens = array(
                                trim($id_responsavel),
                                trim($nome),
                                $cpf, //CPF
                                $rg,
                                $dt_nascimento,
                                $endereco,
                                $bairro,
                                $_login,
                                $_ip,
                                $_data,
                                $_hora,

                            );

                            $where = array(0 => array('id_responsavel', $id_responsavel));
                            $query->updateTupla('responsavel', $itens, $where);

                            $responsavel_contato = new Query($bd);
                            $responsavel_contato->exec("SELECT rc.id_responsavel_contato
                                                    FROM responsavel as r, responsavel_contato as rc, tipo_contato as t
                                                    WHERE r.id_responsavel = rc.id_responsavel AND
                                                        t.id_tipo_contato = rc.id_tipo_contato AND
                                                        r.id_responsavel = " . $id_responsavel);
                            $linhas = $responsavel_contato->rows();

                            $linhas_array = [];
                            $linhas_array_atual = [];
                            $linhas_array_deletar = [];
                            while ($linhas--) {
                                $responsavel_contato->proximo();
                                array_push($linhas_array, $responsavel_contato->record[0]);
                            }

                            $i = 0;

                            foreach ($valor_contato as $val) {

                                $itens = array(
                                    $id_responsavel,
                                    $tipo_contato[$i],
                                    $val,
                                    $principal[$i],
                                    $_login,
                                    $_ip,
                                    $_data,
                                    $_hora,
                                );


                                if ($id_responsavel_contato[$i]) { // Atualiza os Programas.

                                    if (in_array($id_responsavel_contato[$i], $linhas_array)) {

                                        $where = array(0 => array('id_responsavel_contato', $id_responsavel_contato[$i]));
                                        $query->updateTupla('responsavel_contato', $itens, $where);
                                        array_push($linhas_array_atual, $id_responsavel_contato[$i]);
                                    }
                                } else {
                                    $query->insertTupla('responsavel_contato', $itens);
                                }

                                $i++;
                            }

                            foreach ($linhas_array as $linhas_array_deletar) { // Deleta elementos que não estão na página.
                                if (!in_array($linhas_array_deletar, $linhas_array_atual)) {
                                    $where = array(0 => array('id_responsavel_contato', $linhas_array_deletar));
                                    $query->deleteTupla('responsavel_contato', $where);
                                }
                            }


                            $query->commitNotMessage();
                $ret [] =array(
                    'resultado' => 1,
                    'valor_contato' => $valor_contato
                );

                $ret['erro']= $query->sql;

    



                echo json_encode($ret);

?>
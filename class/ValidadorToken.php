<?php

class ValidadorToken
{
    private $chaveSecreta;

    public function __construct()
    {
        $this->chaveSecreta = 'sua_chave_secreta_aqui';
    }

    public function gerarToken($dadosSessao, $ip)
    {
        $timestamp = time();
        $dadosParaToken = serialize($dadosSessao) . $ip . $timestamp;
        $token = hash_hmac('sha256', $dadosParaToken, $this->chaveSecreta);
        $_SESSION['token'] = $token; // Armazena o último token gerado na sessão
        $_SESSION['token_timestamp'] = $timestamp; // Armazena o timestamp da última atualização do token
        return $token;
    }

    public function isTokenValido($dadosSessao, $ip, $token, $intervaloSegundos = 10)
    {
        if (!isset($_SESSION['token']) || !isset($_SESSION['token_timestamp'])) {
            return true; // Permite a validação se o token não estiver definido na sessão
        }

        $ultimoToken = $_SESSION['token'];
        $ultimoTimestampAtualizacao = $_SESSION['token_timestamp'];
        $timestampAtual = time();

        if (($timestampAtual - $ultimoTimestampAtualizacao) < $intervaloSegundos) {
            // Verifica se o token recebido é igual ao último token gerado
            return ($token !== $ultimoToken);
        }

        $_SESSION['token'] = $token; // Armazena o token atual na sessão
        $_SESSION['token_timestamp'] = $timestampAtual; // Atualiza o timestamp da última validação do token

        return true; // Permite a validação
    }
}


?>

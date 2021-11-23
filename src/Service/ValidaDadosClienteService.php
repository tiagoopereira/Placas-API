<?php

namespace App\Service;

use App\Exception\ValidationException;

class ValidaDadosClienteService
{
    const ATRIBUTOS_CLIENTE = [
        'id' => null,
        'nome' => null,
        'telefone' => 11,
        'cpf' => 11,
        'placa_carro' => 7
    ];

    public static function valida(array $dados): void
    {
        self::validaNotNull($dados);
        self::validaQtdCaracteres($dados);
    }

    private static function validaNotNull(array $dados): void
    {
        foreach (self::ATRIBUTOS_CLIENTE as $atributo => $valor) {
            if ($atributo === 'id') {
                continue;
            }

            if (!in_array($atributo, array_keys($dados)) || empty($dados[$atributo])) {
                throw new ValidationException("Atributo {$atributo} é obrigatório!");
            }
        }
    }

    private static function validaQtdCaracteres(array $dados): void
    {   
        foreach ($dados as $chave => $dado) {
            if (!is_null(self::ATRIBUTOS_CLIENTE[$chave]) && (strlen($dado) < self::ATRIBUTOS_CLIENTE[$chave])) {
                $qtd = self::ATRIBUTOS_CLIENTE[$chave];

                throw new ValidationException("O atributo {$chave} deve ter {$qtd} caracteres!");
            }
        }
    }
}
<?php

namespace App\Factory;

use App\Entity\Cliente;

class ClienteFactory
{
    public static function create(array $dados): Cliente
    {
        try {
            $cliente = new Cliente();
            $cliente->setNome($dados['nome']);
            $cliente->setTelefone($dados['telefone']);
            $cliente->setCpf($dados['cpf']);
            $cliente->setPlacaCarro($dados['placa_carro']);

            return $cliente;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
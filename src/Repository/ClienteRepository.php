<?php

namespace App\Repository;

use App\Entity\Cliente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

class ClienteRepository extends ServiceEntityRepository
{
    private Connection $connection;

    public function __construct(
        ManagerRegistry $registry,
        Connection $connection
    )
    {
        parent::__construct($registry, Cliente::class);
        $this->connection = $connection;
    }

    public function verificaSeCPFJaExiste(string $cpf, int $id = null): bool
    {
        $sql = "SELECT COUNT(*) FROM cliente WHERE cpf = :cpf";
        $binds = ['cpf' => $cpf];

        if ($id) {
            $sql .= " AND id != :id";
            $binds['id'] = $id;
        }

        $resultado = $this->connection->executeQuery($sql, $binds)->fetchOne();

        return intval($resultado) !== 0;
    }

    public function findByFinalPlaca(string $numero): array
    {
        $sql = "SELECT * FROM app.cliente WHERE placa_carro LIKE :numero;";
        $binds = [
            'numero' => '%' . $numero
        ];

        return $this->connection->executeQuery($sql, $binds)->fetchAllAssociative();
    }

    public function update(int $id, Cliente $cliente): mixed
    {
        $sql = "UPDATE app.cliente SET 
                    nome = :nome,
                    telefone = :telefone,
                    cpf = :cpf,
                    placa_carro = :placa_carro
                WHERE id = :id";

        $binds = [
            'nome' => $cliente->getNome(),
            'telefone' => $cliente->getTelefone(),
            'cpf' => $cliente->getCpf(),
            'placa_carro' => $cliente->getPlacaCarro(),
            'id' => $id
        ];

        $this->connection->executeQuery($sql, $binds);

        return $this->find($id);
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM app.cliente WHERE id = :id";

        $this->connection->executeQuery($sql, ['id' => $id]);
    }
}

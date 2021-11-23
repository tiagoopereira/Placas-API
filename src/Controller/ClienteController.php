<?php

namespace App\Controller;

use App\Factory\ClienteFactory;
use App\Repository\ClienteRepository;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ValidaDadosClienteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClienteController extends AbstractController
{
	private EntityManagerInterface $entityManager;
	private ClienteRepository $repository;

	public function __construct(
		EntityManagerInterface $entityManager,
		ClienteRepository $repository
	)
	{
		$this->entityManager = $entityManager;
		$this->repository = $repository;
	}

	public function index(): JsonResponse
	{
		$clientes = $this->repository->findAll();

		return new JsonResponse($clientes, JsonResponse::HTTP_OK);
	}

	public function find(int $id): JsonResponse
	{
		$cliente = $this->repository->find($id);

		return new JsonResponse($cliente, JsonResponse::HTTP_OK);
	}

	public function create(Request $request): JsonResponse
	{
		try {
			$dados = json_decode($request->getContent(), true);
			ValidaDadosClienteService::valida($dados);

			$cliente = ClienteFactory::create($dados);
			$cpfExiste = $this->repository->verificaSeCPFJaExiste($cliente->getCpf());

			if ($cpfExiste) {
				return new JsonResponse(["message" => "CPF já cadastrado"], JsonResponse::HTTP_CONFLICT);
			}

			$this->entityManager->persist($cliente);
			$this->entityManager->flush();

			return new JsonResponse($cliente, JsonResponse::HTTP_CREATED);
		} catch (ValidationException $e) {
			return new JsonResponse(['message' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
		} catch (\Exception $e) {
			return new JsonResponse(['message' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function update(int $id, Request $request): JsonResponse
	{
		try {
			$dados = json_decode($request->getContent(), true);
			ValidaDadosClienteService::valida($dados);

			$cliente = ClienteFactory::create($dados);
			$cpfExiste = $this->repository->verificaSeCPFJaExiste($cliente->getCpf(), $id);

			if ($cpfExiste) {
				return new JsonResponse(["message" => "CPF já cadastrado"], JsonResponse::HTTP_CONFLICT);
			}

			$cliente = $this->repository->update($id, $cliente);

			return new JsonResponse($cliente, JsonResponse::HTTP_OK);
		} catch (ValidationException $e) {
			return new JsonResponse(['message' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
		} catch (\Exception $e) {
			return new JsonResponse(['message' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function delete(int $id): JsonResponse
	{
		try {
			$this->repository->delete($id);

			return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
		} catch (\Exception $e) {
			return new JsonResponse(['message' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	public function consultaPlaca(string $numero): JsonResponse
	{
		$clientes = $this->repository->findByFinalPlaca($numero);

		return new JsonResponse($clientes, JsonResponse::HTTP_OK);
	}
}

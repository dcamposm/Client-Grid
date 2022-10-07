<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ClientRepository;
use App\Entity\Client;

class ClientController extends AbstractController
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    #[Route('/', name: 'index')]
    public function index(Request $request,PaginatorInterface $paginator): Response
    {
        $clients = $this->clientRepository->findAll();

         $clients = $paginator->paginate(
            $this->clientRepository->findAll(), 
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('client/index.html.twig', [
            'clientList' => $clients
        ]);
    }

    #[Route('/client/{id}', name: 'show_client', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $client = $this->clientRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'lastname' => $client->getLastname(),
            'phone' => $client->getPhone(),
            'email' => $client->getEmail(),
            'birthday' => $client->getBirthday(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/client/insert', name: 'insert_client', methods: ['POST'])]
    public function insert(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $lastname = $request->get('lastname');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $birthday = new \DateTime($request->get('birthday'));

        if (empty($name) || empty($lastname) || empty($phone) || empty($email || empty($birthday))) {
            throw new NotFoundHttpException('ERROR!');
        }

        $client = new Client();

        $client->setName($name);
        $client->setLastname($lastname);
        $client->setPhone($phone);
        $client->setEmail($email);
        $client->setBirthday($birthday);

        $this->clientRepository->save($client,true);

        return new JsonResponse(['status' => 'Client created!'], Response::HTTP_OK);
    }

    #[Route('/client/update/{id}', name: 'update_client', methods: ['POST'])]
    public function update($id, Request $request): JsonResponse
    {
        $client = $this->clientRepository->findOneBy(['id' => $id]);

        if (empty($client)) throw new NotFoundHttpException('ERROR!');

        $name = $request->get('name');
        $lastname = $request->get('lastname');
        $phone = $request->get('phone');
        $email = $request->get('email');
        $birthday = new \DateTime($request->get('birthday'));

        empty($name) ? true : $client->setName($name);
        empty($lastname) ? true : $client->setLastname($lastname);
        empty($phone) ? true : $client->setPhone($phone);
        empty($email) ? true : $client->setEmail($email);
        empty($birthday) ? true : $client->setBirthday($birthday);

        $this->clientRepository->save($client,true);

        return new JsonResponse(['status' => 'Client updated!'], Response::HTTP_OK);
    }

   #[Route('/client/{id}', name: 'delete_client', methods: ['DELETE'])]
    public function delete($id): JsonResponse
    {
        $client = $this->clientRepository->findOneBy(['id' => $id]);

        if (empty($client)) throw new NotFoundHttpException('ERROR!');

        $this->clientRepository->remove($client, true);

        return new JsonResponse(['status' => 'Client deleted'], Response::HTTP_OK);
    }
}

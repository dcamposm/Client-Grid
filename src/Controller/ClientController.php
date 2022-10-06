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
            $this->clientRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('client/index.html.twig', [
            'clientList' => $clients
        ]);
    }

    #[Route('/client/add', name: 'add_client', methods: ['POST'])]
    public function add(Request $request): JsonResponse
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

        return new JsonResponse(['status' => 'Client created!'], Response::HTTP_CREATED);
    }
}

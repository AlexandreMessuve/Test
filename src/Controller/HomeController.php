<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods: ['GET'])]
    public function index(): response
    {
        $currentUser = $this->getUser();
        return $this->render('/pages/home.html.twig', [
            'currentUser' => $currentUser
        ]);
    }
}
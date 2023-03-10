<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
    *  Controller user page
     * @param PaginatorInterface $paginator
     * @param UserRepository $repository
     * @param Request $request
     * @return Response
     */
    #[Route('/utilisateur', name: 'user.index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, UserRepository $repository, Request $request): Response
    {
        $currentUser = $this->getUser();

        $users = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('/pages/user/index.html.twig', [
            'users' => $users,
            'currentUser' => $currentUser
        ]);
    }


    /**
     * Contoller pour edit un utilisateur
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edition/{id}', name: 'user.edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $manager): Response
    {
        $currentUser = $this->getUser();
        if (!$this->getUser()){
            return $this->redirectToRoute('home.index');
        }

        if ($this->getUser() !== $user) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas modifier cette utilisateur'
            );
            return $this->redirectToRoute('user.index');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Utilisateur modifié avec succés'
            );
            return $this->redirectToRoute('user.index');
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Controller pour supprimer un utilisateur
     * @param EntityManagerInterface $manager
     * @param User $user
     * @return Response
     */
    #[Route('//utilisateur/supprimer/{id}', name: 'user.del', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, User $user): Response
    {
        if ($this->getUser() !== $user) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas supprimer cette utilisateur'
            );
            return $this->redirectToRoute('user.index');
        }
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'L\'utilisateur a bien été supprimé'
        );

        return $this->redirectToRoute('user.index');
    }
}

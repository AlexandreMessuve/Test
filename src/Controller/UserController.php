<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserDeleteType;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
    *  Controller user page
     * @param PaginatorInterface $paginator
     * @param RecipeRepository $repository
     * @param Request $request
     * @return Response
     */
    #[Route('/utilisateur', name: 'user.index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, RecipeRepository $repository, Request $request): Response
    {
        $currentUser = $this->getUser();

        $recipes = $paginator->paginate(
            $repository->findBy(['user' => $currentUser]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('/pages/user/index.html.twig', [
            'recipes' => $recipes,
            'currentUser' => $currentUser
        ]);
    }


    /**
     * Contoller pour edit un utilisateur
     * @param UserPasswordHasherInterface $hashed
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/utilisateur/edit', name: 'user.edit')]
    public function edit(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hashed): Response
    {
        $currentUser = $this->getUser();

        $form = $this->createForm(UserType::class, $currentUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $plainPassword = $form->getData()->getplainPassword();

            if ($hashed->isPasswordValid($currentUser, $plainPassword)){
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre profil a bien été modifié'
                );
                return $this->redirectToRoute('user.index');
            }else {
                $this->addFlash(
                    'error',
                    'Impoosible de changer vos données car vous n\'avez pas mis le bon mot de passe'
                );
            }
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
    #[Route('/utilisateur/supprimer', name: 'user.del', methods: ['GET' , 'POST'])]
    public function delete(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $this->container->get('security.token_storage')->setToken(null);
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre compte a bien été supprimé'
        );

        return $this->redirectToRoute('home.index');
    }
}

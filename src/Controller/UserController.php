<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CommentsRepository;
use App\Repository\UserRepository;
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
     * @param CommentsRepository $repository
     * @param Request $request
     * @return Response
     */
    #[Route('/utilisateur/{id}', name: 'user.index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, CommentsRepository $repository, Request $request, User $user): Response
    {
        $currentUser = $this->getUser();

        $comments = $paginator->paginate(
            $repository->findBy(['user' => $user],['createdAt' => 'DESC']),
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('/pages/user/index.html.twig', [
            'user' => $user,
            'comments' => $comments,
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
    #[Route('/utilisateur/edit', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hashed): Response
    {
        $currentUser = $this->getUser();

        $form = $this->createForm(UserType::class, $currentUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->getData()->getplainPassword();

            if ($hashed->isPasswordValid($currentUser, $plainPassword)) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre profil a bien été modifié'
                );
                return $this->redirectToRoute('user.index');
            } else {
                $this->addFlash(
                    'error',
                    'Impoosible de changer vos données car vous n\'avez pas mis le bon mot de passe'
                );
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'currentUser' => $currentUser,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/utilisateur/editpass', name: 'user.edit.pass', methods: ['GET', 'POST'])]
    public function editPass(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hashed): Response
        {
            $currentUser = $this->getUser();

            if ($request->isMethod('POST')){
                if ($hashed->isPasswordValid($currentUser, $request->request->get('plainPassword'))){
                    if ($request->request->get('password') === $request->request->get('passwordVerify')){
                        if ($request->request->get('password') !== $request->request->get('plainPassword')){
                            $hashedPass =  $hashed->hashPassword($currentUser ,$request->request->get('password'));
                            $currentUser->setPassword($hashedPass);
                            $manager->persist($currentUser);
                            $manager->flush();
                            $this->addFlash('success', 'Mot de passe modifié avec succès');
                            return $this->redirectToRoute('user.index');
                        } else {
                            $this->addFlash('error', 'Vous ne pouvez pas remettre le même mot de passe');
                        }


                    }else{
                        $this->addFlash('error', 'Nouveau mot de passe non identique avec le confirme');
                    }
                }else{
                    $this->addFlash(
                        'error',
                        'Ancien mot de passe incorrect'
                    );
                }
            }


            return $this->render('pages/user/editpass.html.twig', [
                'currentUser' => $currentUser
            ]);

        }

    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     */

    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/utilisateur', name: 'user.list', methods: ['GET'])]
    public function userList(UserRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $users = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('pages/user/list.html.twig',[
            'users' => $users,
            'currentUser' => $this->getUser()
        ]);
    }
    /**
     * Controller pour supprimer un utilisateur
     * @param EntityManagerInterface $manager
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

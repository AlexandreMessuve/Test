<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    /**
     * This function display all ingredients
     * @author Alexandre Messuve <alexandre.messuves@gmail.com>
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods: ['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $currentUser = $this->getUser();
        $ingredient = $paginator->paginate(
            $repository->findBy(['user' => $currentUser]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('/pages/ingredient/index.html.twig', [
            'ingredients' => $ingredient,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Renders a page create ingredient
     * @author Alexandre Messuve <alexandre.messuves@gmail.com>
     * @return Response
     */
    #[Route('/ingredient/ajout', name:'ingredient.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response
    {
        $currentUser = $this->getUser();
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $ingredient->setUser($currentUser);

            $manager->persist($ingredient);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre ingrédient a bien été crée'
            );

            return $this->redirectToRoute('ingredient.index');
        }
        return $this->render('/pages/ingredient/new.html.twig', [
            'form' => $form->createView(),
            'currentUser' => $currentUser
    ]);
    }

    /**
     * Renders a form update
     * @param Request $request
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/modifier/{id}', name:'ingredient.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Ingredient $ingredient,
        EntityManagerInterface $manager
    ): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a bien été mis a jour'
            );

            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('/pages/ingredient/edit.html.twig', [
            'form' => $form->createView(),
            'currentUser' => $currentUser
        ]);
    }

    #[Route('//ingredient/supprimer/{id}', name: 'ingredient.del', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Ingrédient a bien été supprimé'
        );

        return $this->redirectToRoute('ingredient.index');
    }
}

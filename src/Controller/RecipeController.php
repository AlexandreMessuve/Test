<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{

    /**
     *
     * This controller display all recipes
     * @author Alexandre Messuve <alexandre.messuves@gmail.com>
     * @param PaginatorInterface $paginator
     * @param RecipeRepository $repository
     * @param Request $request
     * @return Response
     */
    #[Route('/recette', name: 'recipe.index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, RecipeRepository $repository, Request $request): Response
    {
        $currentUser = $this->getUser();
        $recipes = $paginator->paginate(
            $repository->findBy(['user' => $currentUser]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('/pages/recipe/index.html.twig', [
            'recipes' => $recipes,
            'currentUser' => $currentUser
        ]);
    }


    /**
     * This controller create a recipe
     * @author Alexandre Messuve <alexandre.messuves@gmail.com>
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/recette/ajout", name:"recipe.new" , methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $currentUser = $this->getUser();
        $recipe = new Recipe();
        $form =  $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $recipe->setUser($currentUser);

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a bien été ajouté'
            );
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('/pages/recipe/new.html.twig', [
            'form' => $form->createView(),
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Controller update a recipe
     * @param Request $request
     * @param Recipe $recipe
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/modifier/{id}', name:'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Recipe $recipe,
        EntityManagerInterface $manager
    ): Response
    {
        $currentUser = $this->getUser();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a bien été mis a jour'
            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('/pages/recipe/edit.html.twig', [
            'form' => $form->createView(),
            'currentUser' => $currentUser
        ]);
    }

    #[Route('/recette/supprimer/{id}', name: 'recipe.del', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Recipe $recipe): Response
    {
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash(
            'success',
            'Ingrédient a bien été supprimé'
        );

        return $this->redirectToRoute('recipe.index');
    }
}

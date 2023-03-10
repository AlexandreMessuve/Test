<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;


    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public function login()
    {

    }

    /**
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        if (!$this->getUser())
        {
            $this->addFlash('error','Tu n\'es pas connecté');
            return  $this->redirectToRoute('security.login');

        }

        if ($this->getUser()->getRoles() === ["ROLE_USER"])
        {
            $this->addFlash('error','Tu n\'as pas les permissions requis ');
            return  $this->redirectToRoute('home.index');

        }

        $url = $this->adminUrlGenerator->setController(UserCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Fil Rouge');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Utilisateur');

        yield MenuItem::subMenu('Actions','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Creer un utilisateur', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les utilisateurs', 'fas fa-eye', User::class),
        ]);

        yield MenuItem::section('Ingredients');
        yield MenuItem::subMenu('Actions','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Creer un ingrédient', 'fas fa-plus', Ingredient::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les ingrédients', 'fas fa-eye', Ingredient::class),
        ]);

        yield MenuItem::section('Recettes');
        yield MenuItem::subMenu('Actions','fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Creer une recette', 'fas fa-plus', Recipe::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les recettes', 'fas fa-eye', Recipe::class),
        ]);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}

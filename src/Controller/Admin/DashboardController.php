<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController{

    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator){}
    public function index(): Response{
        return $this->render(view: 'admin/dashboard.html.twig');
    }

    #[AdminDashboard(routePath: "/admin/article", routeName: "app_admin_article")]
    public function article(): Response
    {
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    #[AdminDashboard(routePath: "/admin/account", routeName: "app_admin_account")]
    public function account(): Response
    {
        $url = $this->adminUrlGenerator->setController(AccountCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    #[AdminDashboard(routePath: "/admin/category", routeName: "app_admin_category")]
    public function category(): Response
    {
        $url = $this->adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Exemple');
    }

    public function configureMenuItems(): iterable
    {
        return[
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            yield MenuItem::section('-----'),
            yield MenuItem::linkToCrud('Account', 'fa fa-user', Account::class),
            yield MenuItem::linkToCrud('Category', 'fa fa-list', Category::class),
            yield MenuItem::linkToCrud('Article', 'fa fa-newspaper', Article::class)
        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}

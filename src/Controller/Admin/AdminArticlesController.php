<?php


namespace App\Controller\Admin;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminArticlesController
 * @package App\Controller
 *
 * @Route("sgadblog/administration")
 */
class AdminArticlesController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     * @return Response
     */
    public function dashboard(): Response
    {

        return $this->render('admin/articles/dashboard.html.twig');
    }
}

<?php


namespace App\Controller\Admin;


use App\Entity\Article;
use App\Form\ArticleType;
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

    /**
     * @Route("/articles", name="back-articles")
     * page qui affiche tous les articles
     * @param ArticleRepository $repoArticles
     * @return Response
     */
    public function index(ArticleRepository $repoArticles): Response
    {
//        $pagination->setEntityClass(Article::class)
//            ->setCurrentPage($page);
//        dd($pagination);
        $articles = $repoArticles->findAllRecent();
//        dd($articles);
        return $this->render('admin/articles/index.html.twig', [
//            'pagination' => $pagination,
            'articles' => $articles,
        ]);
    }

    /**
     * permet de créer un nouvel article
     *
     * @Route("/article/new", name="article_create")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserRepository $repo
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, ObjectManager $manager, UserRepository $repo): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $role = 'admin';
            $userAdmin = $repo->findOneByRole($role);

            $article->setArticleCreatedAt(new DateTime('now'));
            $article->setUser($userAdmin);

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success',
                "L'article <strong>{$article->getTitle()}</strong> a bien été enregistrée !"
            );
            return $this->redirectToRoute('back-articles', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('admin/articles/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
}

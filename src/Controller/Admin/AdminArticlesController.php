<?php


namespace App\Controller\Admin;


use App\Entity\Article;
use App\Form\ArticleEditType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param ArticleRepository $articleRepository
     * @param TagRepository $tagRepository
     * @param CommentRepository $commentRepository
     * @return Response
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function dashboard(
        ArticleRepository $articleRepository,
        TagRepository $tagRepository,
        CommentRepository $commentRepository
    ): Response
    {

        return $this->render('admin/articles/dashboard.html.twig', [
            'articles' => $articleRepository->findLatest(4),
            'nbArticles' => $articleRepository->countNbArticles(),
            'nbCategories' => $tagRepository->countNbCategories(),
            'nbComments' => $commentRepository->countNbComments()
        ]);
    }

    /**
     * @Route("/articles", name="back-articles")
     * page qui affiche tous les articles
     * @param ArticleRepository $repoArticles
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function index(ArticleRepository $repoArticles, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $paginator->paginate(
            $repoArticles->findAllRecent(),
            $request->query->getInt('page', 1),
            10);
        return $this->render('admin/articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * permet de créer un nouvel article
     *
     * @Route("/article/new", name="article_create")
     *
     * @param Request $request
     * @param UserRepository $repo
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request, UserRepository $repo): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $role = 'admin';
            $userAdmin = $repo->findOneByRole($role);

            $article->setArticleCreatedAt(new DateTime('now'));
            $article->setUser($userAdmin);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

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

    /**
     * @param Article $article
     * @param Request $request
     * @return Response
     * @Route("/article/edit/{slug}", name="article_edit")
     * @throws \Exception
     */
    public function edit(
        Article $article,
        Request $request
    ): Response
    {
        $form = $this->createForm(ArticleEditType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article->setArticleModifiedAt(new DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success',
                "L'article <strong>{$article->getTitle()}</strong> a bien été modifié !"
            );
            return $this->redirectToRoute('back-articles', [
                //'slug' => $article->getSlug()
            ]);

        }
        return $this->render('admin/articles/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * @param Article $article
     * @return RedirectResponse
     * @Route("/article/delete/{slug}", name="article_delete")
     */
    public function delete(Article $article): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        $this->addFlash(
            'success',
            "L'article <strong>{$article->getTitle()}</strong> a  bien été supprimé !"
        );
        return $this->redirectToRoute('back-articles');
    }
}

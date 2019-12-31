<?php


namespace App\Controller;


use App\Repository\ArticleRepository;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @param ArticleRepository $articleRepository
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function home(ArticleRepository $articleRepository, TagRepository $tagRepository): Response
    {
        return $this->render('site/home.html.twig',[
            'tags' => $tagRepository->findAll(),
            'articles' => $articleRepository->findLatest(4)
        ]);
    }

    /**
     * @Route("categorie/{slugcat}", name="categorie_show")
     * @param ArticleRepository $articleRepository
     * @param TagRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function showCategorie(
        TagRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $slugcat = $request->attributes->get('slugcat');
//        dd($slugcat);
        $idcat = $categoryRepository->findByName($slugcat);
//        dd($idcat);
        $category = $categoryRepository->find($idcat);
//        dd($category);
//        dd($articleRepository->findByCategory($category->getName()));
        $testArticles = $category->getArticles();
//        dd($category->getArticles());
//        try test with articles
        if($testArticles !== null) {
            $articles = $paginator->paginate(
                $categoryRepository->find($idcat)->getArticles(),
                $request->query->getInt('page', 1),
                9);
        }else{
            $articles = null;
        }
        return $this->render('site/tags/show.html.twig', [
            'catName' => $category->getName(),
            'catSlug' => $category->getSlug(),
            'catDescription' => $category->getDescription(),
            'tags' => $categoryRepository->findAll(),
            'articles' => $articles
        ]);
    }

    /**
     * @Route("article/{slugarticle}", name="show_article")
     * @param ArticleRepository $articleRepository
     * @param TagRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function showArticle(
        ArticleRepository $articleRepository,
        TagRepository $categoryRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $slugarticle = $request->attributes->get('slugarticle');
////        dd($slugcat);
//        $idcat = $categoryRepository->findByName($slugcat);
////        dd($idcat);
//        $category = $categoryRepository->find($idcat);
//        dd($articleRepository->findByCategory($category));
//        try test with articles

        return $this->render('site/articles/show.html.twig', [
            'tags' => $categoryRepository->findAll(),
            'article' => $articleRepository->findOneByName($slugarticle)
//            'category' => $category = $categoryRepository->find($idcat)
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function contact(TagRepository $tagRepository)
    {
        return $this->render('site/contact.html.twig',[
            'tags' => $tagRepository->findAll()
        ]);
    }
}

<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
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

        $idcat = $tagRepository->findByName('référencement');
        $category = $tagRepository->find($idcat);
        $refArticles = $category->getArticles();

        return $this->render('site/home.html.twig',[
            'tags' => $tagRepository->findAll(),
            'articles' => $articleRepository->findLatest(6),
            'refArticles' => $refArticles
        ]);
    }

    /**
     * @Route("categorie/{slugcat}", name="categorie_show")
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
        $idcat = $categoryRepository->findByName($slugcat);
        $category = $categoryRepository->find($idcat);
        $testArticles = $category->getArticles();
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
     * @param CommentRepository $commentRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function showArticle(
        ArticleRepository $articleRepository,
        TagRepository $categoryRepository,
        CommentRepository $commentRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $slugarticle = $request->attributes->get('slugarticle');
        $article = $articleRepository->findOneBySlug($slugarticle);
        $idArticle = $article->getId();
//        $comments = $commentRepository->findByID($idArticle);
        $comments = $paginator->paginate(
            $commentRepository->findByID($idArticle),
            $request->query->getInt('page', 1),
            10);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setArticle($articleRepository->findOneBySlug($slugarticle));
            $comment->setCommentCreatedAt(new \DateTime());
            $comment->setActivation(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre commentaire à été ajouté ! Nous le traiteront dans les plus brefs délais.'
            );
            return $this->redirectToRoute('show_article', [
                'slugarticle' => $article->getSlug(),
            ]);
        }

        return $this->render('site/articles/show.html.twig', [
            'tags' => $categoryRepository->findAll(),
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param TagRepository $tagRepository
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function contact(Request $request, TagRepository $tagRepository, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form['name']->getData();
            $usermail = $form['mail']->getData();
            $userphone = $form['phone']->getData();
            $usermsg = $form['message']->getData();

            $email = (new TemplatedEmail())
                ->from('contact@sylvain-guerrin.fr')
                ->to('sguerrin549@gmail.com')
                ->subject('Demande de contact')
                ->text('Demande de contact provenant du blog sylvain guerrin')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'username' => $username,
                    'mail' => $usermail,
                    'phone' => $userphone,
                    'msg' => $usermsg
                ])
            ;

//            $transport = new EsmtpTransport('smtp');
//            $mailer = new Mailer($transport);
            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a bien été pris en compte ! Nous vous répondrons dans les plus brefs délais.'
            );

            return $this->redirectToRoute('home');
        }

        return $this->render('site/contact.html.twig',[
            'tags' => $tagRepository->findAll(),
            'form' => $form->createView()
        ]);
    }
}

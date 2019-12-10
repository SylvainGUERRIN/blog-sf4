<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminCommentsController
 * @package App\Controller\Admin
 * @Route("sgadblog/administration")
 */
class AdminCommentsController extends AbstractController
{
    /**
     * @Route("/commentaires", name="back-commentaires")
     * page qui affiche tous les commentaires
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     * @throws \Exception
     */
    public function index(CommentRepository $commentRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $comments = $paginator->paginate(
            $commentRepository->findAllRecent(),
            $request->query->getInt('page', 1),
            10);
        return $this->render('admin/comments/index.html.twig', [
            'comments' => $comments,
        ]);
    }

//    /**
//     * appel ajax pour l'activation du commentaire
//     * @Route("/ajax/commentaire/activation", name="activation_commentaire")
//     */
//    public function CommentaireActivation(Request $request, CommentRepository $commentRepository, ObjectManager $manager)
//    {
//        if($request->isXmlHttpRequest()){
//            $id = $request->get('id');
//            $comment = $commentRepository->findByIDWithoutActivation($id);
//            //dd($comment);
//            $comment->setActivation(1);
//            $manager->persist($comment);
//            $manager->flush();
//            $commentaires = $commentRepository->findAll();
//        }
//        return $this->render('admin/partials/commentaireTab.html.twig', [
//            'commentaires' => $commentaires,
//        ]);
//    }
//
//    /**
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     * @Route("/sgfback/commentaire/delete/{id}", name="comment_delete")
//     */
//    public function delete(Comment $comment , ObjectManager $manager, ArticleRepository $articleRepository)
//    {
//        $commentWhereArticleIs = $comment->getArticle();
//        if($commentWhereArticleIs !== null){
//            $idArticle = $commentWhereArticleIs->getId();
//            $articleCorrespondant = $articleRepository->findOneByID($idArticle);
//            $nbComments = $articleCorrespondant[0]->getNbComments();
//            $nbCommentsDef = $nbComments - 1;
//            $article = $this->getDoctrine()
//                ->getRepository(Article::class)
//                ->find($idArticle);
//            $article->setNbcomments($nbCommentsDef);
//            $manager->persist($article);
//            $manager->remove($comment);
//            $manager->flush();
//        }
//        $manager->remove($comment);
//        $manager->flush();
//
//        $this->addFlash(
//            'success',
//            "Le commentaire a  bien été supprimé !"
//        );
//        return $this->redirectToRoute('back-commentaires');
//    }
//
//    /**
//     * appel ajax pour la désactivation du commentaire
//     * @Route("/ajax/commentaire/desactivation", name="desactivation_commentaire")
//     */
//    public function CommentaireDesactivation(Request $request, CommentRepository $commentRepository, ObjectManager $manager)
//    {
//        if($request->isXmlHttpRequest()){
//            $id = $request->get('id');
//            $comment = $commentRepository->findByIDWithoutActivation($id);
//            $comment->setActivation(0);
//            $manager->persist($comment);
//            $manager->flush();
//            $commentaires = $commentRepository->findAll();
//        }
//        return $this->render('admin/partials/commentaireTab.html.twig', [
//            'commentaires' => $commentaires,
//        ]);
//    }
//
//    /**
//     * fonction en ajax pour la recherche dans les commentaires
//     * @Route("/ajax/comment/search", name="comment_search")
//     */
//    public function commentSearch(Request $request, CommentRepository $commentRepository)
//    {
//        if($request->isXmlHttpRequest()){
//            $value = $request->get('value');
//            $commentaires = $commentRepository->findAllMatching($value);
//            if($commentaires === array()){
//                $commentaires = $commentRepository->findAll();
//                return $this->render('admin/partials/commentaireTab.html.twig', [
//                    'commentaires' => $commentaires,
//                ]);
//            }
//            return $this->render('admin/partials/commentaireTab.html.twig', [
//                'commentaires' => $commentaires,
//            ]);
//        }
//        $commentaires = $commentRepository->findAll();
//        return $this->render('admin/partials/commentaireTab.html.twig', [
//            'commentaires' => $commentaires,
//        ]);
//    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    /**
     * appel ajax pour l'activation du commentaire
     * @Route("/ajax/commentaire/activation", name="activation_commentaire")
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function CommentaireActivation(Request $request, CommentRepository $commentRepository): Response
    {
        if($request->isXmlHttpRequest()){
            $id = $request->get('id');
            $comment = $commentRepository->findByIDWithoutActivation($id);
            $comment->setActivation(1);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $commentaires = $commentRepository->findAll();
        }
        return $this->render('admin/partials/commentTab.html.twig', [
            'comments' => $commentaires,
        ]);
    }

    /**
     * appel ajax pour la désactivation du commentaire
     * @Route("/ajax/commentaire/desactivation", name="desactivation_commentaire")
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function CommentaireDesactivation(Request $request, CommentRepository $commentRepository): Response
    {
        if($request->isXmlHttpRequest()){
            $id = $request->get('id');
            $comment = $commentRepository->findByIDWithoutActivation($id);
            $comment->setActivation(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $commentaires = $commentRepository->findAll();
        }
        return $this->render('admin/partials/commentTab.html.twig', [
            'comments' => $commentaires,
        ]);
    }

    /**
     * @param Comment $comment
     * @return RedirectResponse
     * @Route("/commentaire/delete/{id}", name="comment_delete")
     */
    public function delete(Comment $comment): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $this->addFlash(
            'success',
            "Le commentaire a  bien été supprimé !"
        );
        return $this->redirectToRoute('back-commentaires');
    }
}

<?php


namespace App\Controller\Admin;


use App\Form\TagType;
use App\Repository\TagRepository;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminTagsController
 * @package App\Controller
 *
 * @Route("sgadblog/administration")
 */
class AdminTagsController extends AbstractController
{
    /**
     * @Route("/categories", name="back-categories")
     * page qui affiche tous les categories
     * @param CategoryRepository $repo
     * @param SubCategoryRepository $subCategoryRepository
     * @return Response
     */
    public function index(TagRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('admin/tags/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * créer une nouvelle catégorie
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route("/categories/new", name="categorie_create")
     */
    public function create(Request $request)
    {
        $categories = new Tag();
        $form = $this->createForm(TagType::class, $categories);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();

            $this->addFlash(
                'success',
                "La catégorie {$categories->getName()} a bien été créée !"
            );

            return $this->redirectToRoute('back-categories');
        }

        return $this->render('admin/tags/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/categorie/edit/{slug}", name="categorie_edit")
     * @param Tag $categories
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(Tag $categories, Request $request)
    {
        $form = $this->createForm(TagType::class, $categories);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();

            $this->addFlash('success',
                "La catégorie <strong>{$categories->getName()}</strong> a bien été modifié !"
            );
            return $this->redirectToRoute('back-categories');
        }

        return $this->render('admin/tags/edit.html.twig',[
            'form' => $form->createView(),
            'categorie' => $categories
        ]);
    }

    /**
     * @param Tag $categories
     * @return RedirectResponse
     * @Route("/categorie/delete/{slug}", name="categorie_delete")
     */
    public function delete(Tag $categories): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categories);
        $em->flush();

        $this->addFlash(
            'success',
            "La catégorie <strong>{$categories->getName()}</strong> a  bien été supprimé !"
        );
        return $this->redirectToRoute('back-categories');
    }
}

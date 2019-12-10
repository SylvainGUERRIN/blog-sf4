<?php


namespace App\Controller\Admin;


use App\Repository\TagRepository;
use Doctrine\Common\Persistence\ObjectManager;
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

//    /**
//     * créer une nouvelle catégorie
//     * @param Categories $categories
//     * @param Request $request
//     * @param ObjectManager $manager
//     * @return RedirectResponse|Response
//     * @Route("/categories/new", name="categorie_create")
//     */
//    public function create(Request $request, ObjectManager $manager)
//    {
//        $categories = new Category();
//        $form = $this->createForm(CategoryType::class, $categories);
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
//
//            $manager->persist($categories);
//            $manager->flush();
//
//            $this->addFlash(
//                'success',
//                "La catégorie {$categories->getName()} a bien été créée !"
//            );
//
//            return $this->redirectToRoute('back-categories');
//        }
//
//        return $this->render('admin/categories/new.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/categorie/edit/{slug}", name="categorie_edit")
//     * @param Category $categories
//     * @param Request $request
//     * @param ObjectManager $manager
//     * @return RedirectResponse|Response
//     */
//    public function edit(Category $categories, Request $request, ObjectManager $manager)
//    {
//        $form = $this->createForm(CategoryType::class, $categories);
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
//
//            $manager->persist($categories);
//            $manager->flush();
//
//            $this->addFlash('success',
//                "La catégorie <strong>{$categories->getName()}</strong> a bien été modifié !"
//            );
//            return $this->redirectToRoute('back-categories');
//        }
//
//        return $this->render('admin/categories/edit.html.twig',[
//            'form' => $form->createView(),
//            'categorie' => $categories
//        ]);
//    }
//
//    /**
//     * @param Category $categories
//     * @param ObjectManager $manager
//     * @return RedirectResponse
//     * @Route("/categorie/delete/{slug}", name="categorie_delete")
//     */
//    public function delete(Category $categories , ObjectManager $manager): RedirectResponse
//    {
//        $manager->remove($categories);
//        $manager->flush();
//
//        $this->addFlash(
//            'success',
//            "La catégorie <strong>{$categories->getName()}</strong> a  bien été supprimé !"
//        );
//        return $this->redirectToRoute('back-categories');
//    }
}

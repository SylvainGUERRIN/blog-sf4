<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends AbstractController
{
    /**
     * @param TagRepository $tagRepository
     * @return Response
     */
    public function showAction(TagRepository $tagRepository): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }
}

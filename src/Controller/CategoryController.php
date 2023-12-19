<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categorys' => $category,
        ]);
    }

    #[Route('/{CategoryName}', requirements: ['CategoryName' => '\w+'], methods: ['GET'], name: 'show')]
    public function show(
        string $CategoryName,
        CategoryRepository $categoryRepository,
        ProgramRepository $programRepository
    ): Response {
        $category = $categoryRepository->findOneBy(['name' => $CategoryName]);

        $programs = $programRepository->findBy(['Category' => $category], ['id' => 'DESC'], 3);

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }
}

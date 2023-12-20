<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'The new category has been created');
    
            // Redirect to categories list
            return $this->redirectToRoute('category_index');
        }
    
        // Render the form
        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{CategoryName}', methods: ['GET'], name: 'show')]
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

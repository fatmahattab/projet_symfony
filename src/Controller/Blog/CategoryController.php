<?php

namespace App\Controller\Blog;

use App\Form\SearchType;
use App\Entity\Post\Post;
use App\Model\SearchData;
use App\Entity\Post\Category;
use App\Repository\Post\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'category.index', methods: ['GET'])]
    public function index(
        Category $category,
        PostRepository $postRepository,
        Request $request
    ): Response {
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);

            return $this->render('pages/post/index.html.twig', [
                'category' => $category,
                'form' => $form->createView(),
                'posts' => $posts
            ]);
        }

        return $this->render('pages/category/index.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'posts' => $postRepository->findPublished($request->query->getInt('page', 1), $category)
        ]);
    }
}

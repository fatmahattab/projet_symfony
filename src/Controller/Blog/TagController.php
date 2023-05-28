<?php

namespace App\Controller\Blog;

use App\Entity\Post\Tag;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\Post\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/etiquettes')]
class TagController extends AbstractController
{
    #[Route('/{slug}', name: 'tag.index', methods: ['GET'])]
    public function index(
        Tag $tag,
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
                'tag' => $tag,
                'form' => $form->createView(),
                'posts' => $posts
            ]);
        }

        return $this->render('pages/tag/index.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
            'posts' => $postRepository->findPublished($request->query->getInt('page', 1), null, $tag)
        ]);
    }
}

<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use\App\Entity\Editor;

class EditorController extends AbstractController
{
    /**
     * @Route("/editor", name="editor")
     */
    public function index()
    {   $editors = $this->getDoctrine()
        ->getRepository(Editor::class)
        ->findAll();

        $response = [];

        foreach ($editors as $editor) {
            $response[] = [
                'id' => $editor->getId(),
                'name' => $editor->getName(),
            ];
        }

        return $this->json($response);
    }

    /**
     * @Route("/editor/{id}", name="editor_detail", requirements={"id"="\d+"})
     */
    public function editor($id)
    {
        $editor = $this->getDoctrine()
        ->getRepository(Editor::class)
        ->find($id);

        if(!$editor) {
            throw $this->createNotFoundException('Editor does not exist.');
        }

        $games = [];

        foreach ($editor->getGames() as $game) {
            $games[] = [
                'id' => $game->getId(),
                'name' => $game->getName(),
            ];
        }

        $response = [
            'id' => $editor->getId(),
            'name' => $editor->getName(),
            'game' => $games,
        ];

        return $this->json($response);
    }

}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Note;

class NoteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $notes = $this->getDoctrine()->getRepository(Note::class)->findAll();

        return $this->render('note/index.html.twig', [
            'notes' => $notes
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request)
    {
        $jsonResponse = new JsonResponse();


        if ($request->isXmlHttpRequest()) {
            $content = $request->request->get('content');
            $entityManager = $this->getDoctrine()->getManager();

            $note = new Note();
            $note->setContent($content);
            $note->setCreatedAt(new \DateTime('now'));

            $entityManager->persist($note);
            $entityManager->flush();

            $jsonResponse->setData(['response' => true, 'new_note_id' => $note->getId()]);

        }

        return $jsonResponse;
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function editAction(Request $request)
    {
        $jsonResponse = new JsonResponse();

        if ($request->isXmlHttpRequest()) {
            $noteId = $request->request->get('id');
            $content = $request->request->get('content');
            $entityManager = $this->getDoctrine()->getManager();
            $note = $entityManager->getRepository(Note::class)->find($noteId);

            $note->setContent($content);
            $entityManager->flush();

            $jsonResponse->setData(['response' => true]);

        }
        return $jsonResponse;
    }

    /**
     * @Route("/delete", name="delete")
     */
    public function deleteAction(Request $request)
    {
        $jsonResponse = new JsonResponse();

        if ($request->isXmlHttpRequest()) {
            $noteId = $request->request->get('id');

            $entityManager = $this->getDoctrine()->getManager();
            $note = $entityManager->getRepository(Note::class)->find($noteId);

            if (!$noteId) {
                throw $this->createNotFoundException('Have not found note for id = ' . $noteId);
            }

            $entityManager->remove($note);
            $entityManager->flush();

            $jsonResponse->setData(['response' => true]);
        }

        return $jsonResponse;
    }
}

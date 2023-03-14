<?php

namespace App\Controller;
use App\Entity\Comments;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    #[Route('/comments/{id}', name: 'app_comments' , methods: ['POST'])]
    public function addComment(Request $request, EntityManagerInterface $manager, User $user)
    {
        if ($request->isMethod('POST')){
            $comments = new Comments();
            $content = $request->request->get('content');
            $comments->setContent($content)
                    ->setUser($user)
                    ->setAutor($this->getUser());
            $manager->persist($comments);
            $manager->flush();
            $this->addFlash('commentSucc', 'Envoyé');
            return $this->redirectToRoute('user.index', ['id' => $user->getId()]);
        }else  {
            $this->addFlash('commentErr', 'Impossible d\'envoyer le commentaire');
            return $this->redirectToRoute('user.index', ['id' => $user->getId()]);
        }

    }

    /**
     * @param EntityManagerInterface $manager
     * @param Comments $comments
     * @return Response
     */
    #[Route('/comments/del/{id}', name: 'app_comments_delete', methods: ['GET','POST'])]
    public function delete(Request $request,EntityManagerInterface $manager, Comments $comments): Response
    {
        $user  = $comments->getUser();
        if ($this->getUser() === $comments->getUser() || $this->getUser() === $comments->getAutor()){
            $manager->remove($comments);
            $manager->flush();
            $this->addFlash('commentSucc', 'commentaire supprimé');
        }else{
            $this->addFlash('commentErr', 'impossible de supprimer le commentaire');
        }
        return $this->redirectToRoute('user.index', ['id' => $user->getId()]);
    }
}

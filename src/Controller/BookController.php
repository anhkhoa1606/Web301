<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book_list")
     */
    public function listAction()
    {
        $books = $this->getDoctrine()
            ->getRepository('App\Entity\Book')
            ->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }
    /**
     * @Route("/book/views/{id}", name="book_views")
     */
    public function detailsAction($id)
    {
        $book = $this->getDoctrine()
            ->getRepository('App\Entity\Book')
            ->find($id);

        return $this->render('book/views.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @Route("/book/delete/{id}", name="book_delete")
     */
    public function deleteAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository('App\Entity\Book')->find($id);
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_list');
    }
    /**
    * @Route("/book/create", name="book_create", methods={"GET","POST"})
    */
    public function createAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book_list', array('id' => $book->getId()));
        }
        return $this->render('book/create.html.twig', array(
            'book' => $book,
            'form' => $form->createView(),
        ));
    }

    

        /**
        * @Route("/book/edit/{id}", name="book_edit", methods={"GET","POST"})
        */
        public function editAction(Request $request, $id)
       {
           $em = $this->getDoctrine()->getManager();
           $book = $em->getRepository(Book::class)->find($id);
           
           $form = $this->createForm(BookType::class, $book);
           
           $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager()->flush();
               return $this->redirectToRoute('book_list', array('id' => $id));
           }
           
           return $this->render('book/edit.html.twig', [
               'id' => $id,
               'form' => $form->createView()
           ]);
        }
}

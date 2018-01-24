<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Prophecy\PhpDocumentor\ClassAndInterfaceTagRetriever;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */

class CategoryController extends Controller
{

    /**
     * @Route("/category/update/{id}", name="admin.category.update")
     * @Route("/category/form", name="admin.category.form", defaults={"id" = null})
     */
    public function formAction(ManagerRegistry $doctrine, Request $request, int $id = null):Response
    {
        //doctrine
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Category::class);

        $entity = $id ? $rc->find($id) : new Category();
        $type = CategoryType::class;

        $category = new Category();
        $type = CategoryType::class;
        $form = $this->createForm($type, $category);
        $form->handleRequest($request);
        //virtuellement le pre_submit est là
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $doctrine->getManager()->persist($data);
            $doctrine->getManager()->flush();

            $this->addFlash('notice', 'la catégorie a été créée');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/form.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/category", name="admin.category.index")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request):Response
    {
        //on écrit le CRUD RepoClass = repository::class, c'est pour les select
        $rc = $doctrine
            ->getRepository(Category::class);
        $results = $rc->findAll();

        return $this->render('admin/category/index.html.twig',[
            'results' => $results
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="admin.category.delete")
     */
    public function deleteAction(ManagerRegistry $doctrine, Request $request, int $id):Response
    {

        //doctrine
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Category::class);

        //sélection de l'entité
        $entity = $rc->find($id);
        $em->remove($entity);
        $em->flush();

        $this->addFlash('notice','La catégorie ' . $id . ' a été supprimée.');
        return $this->redirectToRoute('admin.category.index');
    }


}

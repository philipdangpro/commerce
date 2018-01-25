<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Prophecy\PhpDocumentor\ClassAndInterfaceTagRetriever;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */

class ProductController extends Controller
{
    /**
     * @Route("/product/update/{id}", name="admin.product.update")
     * @Route("/product/form", name="admin.product.form", defaults={"id" = null})
     */
    public function formAction(ManagerRegistry $doctrine, Request $request, int $id = null):Response
    {
        //doctrine
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Product::class);

        $type = ProductType::class;
        $entity = $id ? $rc->find($id) : new Product();

        $form = $this->createForm($type, $entity);
        $form->handleRequest($request);

        //virtuellement le pre_submit est là
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            //faire le transfert d'image
            $filename = (new \DateTime())->getTimestamp();
            $data->getImage()->move('img/product', 'PDA' . $filename . ".jpg");

//            'img/product', $data->getImage()->getClientOriginalName()
//            $data->toto = 'img/product/' . $filename . '.jpg';

            $data->setImage('img/product/PDA' . $filename . ".jpg");
            $doctrine->getManager()->persist($data);
            $doctrine->getManager()->flush();

            $this->addFlash('notice', 'le produit a été créé');

            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/form.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/product", name="admin.product.index")
     */
    public function indexAction(ManagerRegistry $doctrine, Request $request):Response
    {
        //on écrit le CRUD RepoClass = repository::class, c'est pour les select
        $rc = $doctrine
            ->getRepository(Product::class);
        $results = $rc->findAll();

        return $this->render('admin/product/index.html.twig',[
            'results' => $results
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="admin.product.delete")
     */
    public function deleteAction(ManagerRegistry $doctrine, Request $request, int $id):Response
    {
        //doctrine
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Product::class);

        //sélection de l'entité
        $entity = $rc->find($id);
        $em->remove($entity);
        $em->flush();

        $this->addFlash('notice','Le produit ' . $id . ' a été supprimée.');
        return $this->redirectToRoute('admin.product.index');
    }

}

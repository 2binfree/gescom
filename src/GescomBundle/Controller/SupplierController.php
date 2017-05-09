<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Supplier;
use GescomBundle\Form\SupplierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SupplierController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param string $name
     * @Route("/supplier", name="supplier")
     */
    public function indexAction()
    {
        $suppliers = $this->getDoctrine()->getRepository('GescomBundle:Supplier')->findAll();
        return $this->render('@Gescom/Supplier/supplier.html.twig', [
            'suppliers'  => $suppliers,
            'documentType' => "Fournisseur",
            'deletionUrl' => $this->generateUrl("delete_supplier", ['supplier' => 0]),
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/supplier/add", name="add_supplier")
     */
    public function addAction(Request $request)
    {
        $supplier = new Supplier();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form  ->isValid()){
            $em->persist($supplier);
            $em->flush();
            return $this->redirectToRoute('supplier');
        }

        return $this->render('@Gescom/Supplier/addSupplier.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Supplier $supplier
     * @Route("/supplier/edit/{supplier}", name="edit_supplier")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Supplier $supplier)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('supplier');
        }

        return $this->render('@Gescom/Supplier/addSupplier.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }

    /**
     * @param $supplier Supplier
     * @Route("/supplier/delete/{supplier}", name="delete_supplier")
     * @return RedirectResponse
     */
    public function deleteAction(Supplier $supplier)
    {
        $em = $this->getDoctrine()->getManager();
        $productSuppliers = $em->getRepository("GescomBundle:ProductSupplier")->findBy(['supplier' => $supplier]);
        foreach ($productSuppliers as $productSupplier){
            $em->remove($productSupplier);
        }
        $em->remove($supplier);
        $em->flush();
        return $this->redirectToRoute('supplier');
    }
}

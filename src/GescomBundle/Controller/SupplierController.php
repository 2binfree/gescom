<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Supplier;
use GescomBundle\Entity\SupplierFilter;
use GescomBundle\Form\SupplierFilterType;
use GescomBundle\Form\SupplierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SupplierController extends Controller
{

    /**
     * @param Request $request
     * @param int $page
     * @param array|null $filter
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/supplier/{page}/{filter}", name="supplier", requirements={"page" : "\d+"})
     */
    public function indexAction(Request $request, $page = 1, $filter = null)
    {
        $filter = new SupplierFilter();
        $filterData = $request->get("gescom_bundle_filter_type");
        if (is_null($filterData)){
            $data = $request->get("filter");
            if (!is_null($data)){
                $filterData = $this->get("gescom.filter.transform")->transform($data);
            }
        }
        if (! is_null($filterData)) {
            foreach($filterData as $field => $value) {
                if ($value !== "") {
                    $accessor = "set" . ucwords($field);
                    $filter->$accessor($value);
                }
            }
        }
        $form = $this->createForm(SupplierFilterType::class, $filter);

        return $this->render('@Gescom/Supplier/supplier.html.twig', [
            'data'  => $this->get("gescom.navigator"),
            'filter' => $filter,
            'filterURL' => http_build_query($filter),
            'documentType' => "Fournisseur",
            'deletionUrl' => $this->generateUrl("delete_supplier", ['supplier' => 0]),
            'form' => $form->createView(),
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

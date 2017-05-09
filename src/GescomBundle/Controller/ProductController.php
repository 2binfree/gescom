<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Product;
use GescomBundle\Entity\ProductSupplier;
use GescomBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends Controller
{
    /**
     * @Route("/product", name="product")
     */
    public function indexAction()
    {
        // we retrieve all data from table through the entity
        $products = $this->getDoctrine()->getRepository('GescomBundle:Product')->findAll();
        return $this->render('@Gescom/Product/product.html.twig', [
            'products'  => $products,
            'documentType' => "Produit",
            'deletionUrl' => $this->generateUrl("delete_product", ['product' => 0]),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/product/add", name="add_product")
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form  ->isValid()){
            $suppliers = $product->getProductSupplier()["name"];
            $product->resetProductSupplier();
            foreach($suppliers as $supplier){
                $productSupplier = new ProductSupplier();
                $productSupplier->setProduct($product);
                $productSupplier->setSupplier($supplier);
                $em->persist($productSupplier);
                $product->addProductSupplier($productSupplier);
            }
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('@Gescom/Product/addProduct.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }
    /**
     * @param Request $request
     * @param Product $product
     * @Route("/product/edit/{product}", name="edit_product")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('product');
        }

        return $this->render('@Gescom/Product/addProduct.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }

    /**
     * @param $product Product
     * @Route("/product/delete/{product}", name="delete_product")
     * @return RedirectResponse
     */
    public function deleteAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $productSuppliers = $em->getRepository("GescomBundle:ProductSupplier")->findBy(['product' => $product]);
        foreach ($productSuppliers as $productSupplier){
            $em->remove($productSupplier);
        }
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('product');
    }    
}

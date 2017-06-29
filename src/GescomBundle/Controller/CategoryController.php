<?php

namespace GescomBundle\Controller;

use GescomBundle\Entity\Category;
use GescomBundle\Form\CategoryFilterType;
use GescomBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/category", name="category")
     * @Method({"get", "post"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $navigator = $this->get("gescom.navigator");
        $filter = $navigator->getEntityFilter();

        $form = $this->createForm(CategoryFilterType::class, $filter);

        return $this->render('@Gescom/Category/category.html.twig', [
            'data'  => $this->get("gescom.navigator"),
            'filter' => $filter,
            'filterURL' => http_build_query($filter),
            'documentType' => "Catégories",
            'deletionUrl' => $this->generateUrl("delete_category", ['category' => 0]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/category/add", name="add_category")
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('@Gescom/Category/addCategory.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $category Category
     * @return Response
     * @Route("/category/edit/{category}", name="edit_category")
     */
    public function editAction(Request $request, Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form  ->isValid()){
            $em->flush();
            return $this->redirectToRoute('category');
        }

        return $this->render('@Gescom/Category/addCategory.html.twig', [
            'form'      =>  $form->createView(),
        ]);
    }

    /**
     * @param $category Category
     * @Route("/category/delete/{category}", name="delete_category")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category');
    }
}

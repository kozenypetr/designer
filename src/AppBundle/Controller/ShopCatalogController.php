<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gedmo\Translatable\TranslatableListener;
use Doctrine\ORM\Query;

use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ShopCatalogController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);*/

        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findBy(['isActive' => true]);

        $products   = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('AppBundle:ShopCatalog:index.html.twig', array('products' => $products, 'categories' => $categories));
    }


    /**
     * @Route("/kategorie/{slug}", name="shop_catalog_list")
     */
    public function listAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $category = $this->em->getRepository('AppBundle:Category')->findOneBySlug($slug);

        if (!$category)
        {
            throw $this->createNotFoundException('Kategorie neexistuje');
        }

        $products = $this->em->getRepository('AppBundle:Product')->findActiveForCategory($category);

        return $this->render('AppBundle:ShopCatalog:list.html.twig', array('category' => $category, 'products' => $products));
    }

    /**
     * @Route("/material/{slug}", name="shop_catalog_material")
     */
    public function materialAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $material = $this->em->getRepository('AppBundle:Material')->findOneBySlug($slug);

        if (!$material)
        {
            throw $this->createNotFoundException('Material neexistuje');
        }

        $products = $this->em->getRepository('AppBundle:Product')->findActiveForMaterial($material);

        return $this->render('AppBundle:ShopCatalog:list.html.twig', array('category' => $material, 'products' => $products));
    }

    /**
     * @Route("/udalost/{slug}", name="shop_catalog_event")
     */
    public function eventAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $event = $this->em->getRepository('AppBundle:Event')->findOneBySlug($slug);

        if (!$event)
        {
            throw $this->createNotFoundException('Událost neexistuje');
        }

        $products = $this->em->getRepository('AppBundle:Product')->findActiveForEvent($event);

        return $this->render('AppBundle:ShopCatalog:list.html.twig', array('category' => $event, 'products' => $products));
    }


    /**
     * @Route("/produkt/{slug}", name="shop_catalog_detail")
     */
    public function detailAction(Request $request, $slug)
    {
        $this->em = $this->getDoctrine()->getManager();

        $product = $this->em->getRepository('AppBundle:Product')->findOneBySlug($slug);

        if (!$product)
        {
            throw $this->createNotFoundException('Produkt neexistuje');
        }

        $attributeForm = $this->createAttributesForm($product);

        return $this->render('AppBundle:ShopCatalog:detail.html.twig', array('product' => $product, 'attributeForm' => ($attributeForm?$attributeForm->createView():null)));
    }


    protected function createAttributesForm($product)
    {
        if ($product->getAttributes())
        {
            $formBuilder = $this->get('form.factory')
                ->createNamedBuilder('attribute', FormType::class, NULL/*hodnoty*/, array('csrf_protection' => false));

            foreach ($product->getAttributes() as $attribute)
            {
                $options = [];
                if ($attribute->getType() == ChoiceType::class)
                {
                    $choices = [];
                    foreach ($attribute->getOptions() as $option)
                    {
                        $choices[$option->getName()] = $option->getName();
                    }

                    $options['choices'] = $choices;
                }

                $options['label'] = $attribute->getName();
                $options['required'] = $attribute->getIsRequired();
                $options['attr']['title'] = $attribute->getHelp();
                $options['attr']['class'] = $attribute->getClass();
                $formBuilder->add($attribute->getId(), $attribute->getType(), $options);
            }

            $form = $formBuilder->getForm();

            return $form;
        }

        return null;
    }


    public function categoriesAction()
    {
        $this->em = $this->getDoctrine()->getManager();

        $categories = $this->em->getRepository('AppBundle:Category')->findAllActive();

        $events = $this->em->getRepository('AppBundle:Event')->findAllActive();

        $materials  = $this->em->getRepository('AppBundle:Material')->findAllActive();

        return $this->render('AppBundle:ShopCatalog:categories.html.twig', array('categories' => $categories, 'materials' => $materials, 'events' => $events));
    }

}

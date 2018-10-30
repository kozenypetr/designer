<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use AppBundle\Form\ChangePasswordType;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:ShopCustomer:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/loginAjax", name="login_ajax")
     */
    public function loginAjaxAction()
    {
        $redirect = false;

        if ($this->get('cart.manager')->updateCart($this->getUser()))
        {
            $redirect = $this->container->get('router')->generate('shop_cart');
        }

        return $this->json(array(
            'username' => $this->getUser()->getUsername(),
            'error' => false,
            'redirect' => $redirect
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        $this->redirect('/');
    }


    /**
     * @Route("/zakaznik/zmena-hesla/potvrzeni", name="reset_password_confirm")
     */
    public function resetPasswordConfirmAction(Request $request)
    {
        return $this->render('AppBundle:ShopCustomer:resetPasswordConfirm.html.twig', array(
        ));
    }

    /**
     * @Route("/zakaznik/zmena-hesla/{hash}", name="reset_password")
     */
    public function resetPasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $error = null;

        $hash = $request->get('hash', 'totonikdynenastane');

        $em = $this->getDoctrine()->getManager();

        $customer = $em->getRepository('AppBundle:Customer')->findOneByResetPasswordHash($hash);

        if (!$customer)
        {
            $error = 'Neplatný požadavek na změnu hesla';

            return $this->render('AppBundle:ShopCustomer:resetPassword.html.twig', array(
                'error' => $error,
                'customer' => null
            ));
        }

        $form = $this->createForm(ChangePasswordType::class);
        // only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $password = $passwordEncoder->encodePassword($customer, $data['plainPassword']);
            $customer->setPassword($password);
            $customer->setResetPasswordHash(null);
            $em->flush();

            return $this->redirectToRoute('reset_password_confirm');
        }

        return $this->render('AppBundle:ShopCustomer:resetPassword.html.twig', array(
            'error' => $error,
            'customer' => $customer,
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/zakaznik/zapomenute-heslo/potvrzeni", name="reset_password_request_confirm")
     */
    public function resetPasswordRequestConfirmAction(Request $request)
    {
        return $this->render('AppBundle:ShopCustomer:resetPasswordRequestConfirm.html.twig', array(
        ));
    }


    /**
     * @Route("/zakaznik/zapomenute-heslo", name="reset_password_request")
     */
    public function resetPasswordRequestAction(Request $request)
    {
        $error = null;
        $email = '';

        if ($request->isMethod('POST'))
        {
            $em = $this->getDoctrine()->getManager();

            $email = $request->get('email');

            $customer = $em->getRepository('AppBundle:Customer')->findOneByEmail($email);

            if (!$customer)
            {
                $error = 'Zadaný email nebyl nalezen v naší databázi';
            }
            else
            {

                // 1. Nastaveni hash na obnovu hesla
                $customer->setResetPasswordHash(md5('s7df5sldkfjsd' . rand(10000, 20000) . $customer->getEmail()));
                $customer->setResetPasswordExpireAt(new \DateTime('+30 minutes'));
                $em->flush();

                // 2. Zaslani mailu z odkazem na zmenu hesla
                $message = (new \Swift_Message('Zapomenuté heslo'))
                    ->setFrom('info@kozenypetr.cz', 'GOWOOD.CZ')
                    ->setTo($customer->getEmail())
                    ->addBcc('info@gowood.cz')
                    ->setBody(
                        $this->get('templating')->render('AppBundle:ShopCustomer/email:resetPasswordEmail.html.twig',
                            array(
                                'customer' => $customer,
                            )
                        ),
                        'text/html'
                    )
                ;

                $this->get('mailer')->send($message);

                return $this->redirectToRoute('reset_password_request_confirm');
            }

        }

        return $this->render('AppBundle:ShopCustomer:resetPasswordRequest.html.twig', array(
            'error' => $error,
            'email' => $email
        ));
    }

}

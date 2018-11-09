<?php
/**
 * Created by PhpStorm.
 * User: petr
 * Date: 1.11.18
 * Time: 8:30
 */

namespace AppBundle\Security;
use AppBundle\Manager\CartManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LoginHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    private $router;
    private $session;
    private $cm;

    /**
     * Constructor
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	RouterInterface $router
     * @param 	Session $session
     */
    public function __construct( RouterInterface $router, Session $session, CartManager $cm )
    {
        $this->router  = $router;
        $this->session = $session;
        $this->cm = $cm;
    }

    /**
     * onAuthenticationSuccess
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	TokenInterface $token
     * @return 	Response
     */
    public function onAuthenticationSuccess( Request $request, TokenInterface $token)
    {
        // if AJAX login

        $updateQuantity = $this->cm->updateCart($token->getUser());

        $redirect = false;

        if ($updateQuantity)
        {
            $redirect = $this->router->generate( 'shop_cart' );
        }

        if ( $request->isXmlHttpRequest() ) {

            $array =  array(
                'username' => $token->getUser()->getUsername(),
                'error' => false,
                'redirect' => $redirect
            );

            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;

            // if form login
        } else {

            /*if ( $this->session->get('_security.main.target_path' ) ) {

                $url = $this->session->get( '_security.main.target_path' );

            } else {

                $url = $this->router->generate( 'home_page' );

            } // end if*/

            return new RedirectResponse( $this->router->generate( 'homepage' ) );

        }
    }

    /**
     * onAuthenticationFailure
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	AuthenticationException $exception
     * @return 	Response
     */
    public function onAuthenticationFailure( Request $request, AuthenticationException $exception )
    {
        // if AJAX login
        if ( $request->isXmlHttpRequest() ) {

            $array = array( 'error' => true, 'message' => $exception->getMessage() ); // data to return via JSON
            $response = new Response( json_encode( $array ) );
            $response->headers->set( 'Content-Type', 'application/json' );

            return $response;

            // if form login
        } else {

            // set authentication exception to session
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

            return new RedirectResponse( $this->router->generate( 'login' ) );
        }
    }
}
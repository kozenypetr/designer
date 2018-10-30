<?php

// src/AppBundle/Security/TimeAuthenticator.php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Manager\CartManager;

use Doctrine\ORM\EntityManagerInterface;

class CustomerAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoder;
    private $session;
    private $em;
    private $cm;

    public function __construct(UserPasswordEncoderInterface $encoder, Session $session, EntityManagerInterface $em, CartManager $cm)
    {
        $this->encoder = $encoder;
        $this->session = $session;
        $this->em = $em;
        $this->cm = $cm;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $exception) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new CustomUserMessageAuthenticationException('Neplatné přihlášení.');
        }

        $currentUser = $token->getUser();

        if ($currentUser instanceof UserInterface) {
            if ($currentUser->getPassword() !== $user->getPassword()) {
                throw new BadCredentialsException('The credentials were changed from another session.');
            }
        } else {
            if ('' === ($givenPassword = $token->getCredentials())) {
                throw new BadCredentialsException('Heslo nesmí být prázdné.');
            }
            if (!$this->encoder->isPasswordValid($user, $givenPassword)) {
                throw new BadCredentialsException('Heslo není platné.');
            }
        }


        // zkusime najit kosik pro zakaznika a vlozit do session
        // aktualizace kosiku
        $this->cm->updateCart($user);


        return new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            $providerKey,
            $user->getRoles()
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
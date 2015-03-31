<?php
namespace JDJ\UserBundle\Service;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Routing\RouterInterface;

class AuthenticationHandler  implements AuthenticationFailureHandlerInterface, AuthenticationSuccessHandlerInterface
{
    /**
     * @var
     */
    private $router;

    /**
     * Constructeur
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            $json = array(
                'has_error'   => false,
                'username'    => $token->getUser()->getUsername(),
            );
            $response = new Response(json_encode($json));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return new RedirectResponse($this->router->generate('jdj_web_homepage'));

    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            $json = array(
                'has_error' => true,
                'error'     => $exception->getMessage()
            );

            $response = new Response(json_encode($json));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            return parent::onAuthenticationFailure($request, $exception);
        }
    }
}
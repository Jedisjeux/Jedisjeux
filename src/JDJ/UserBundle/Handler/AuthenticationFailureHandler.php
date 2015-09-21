<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/09/15
 * Time: 23:50
 */

namespace JDJ\UserBundle\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse( array(
                'has_error' => true,
                'error'     => $exception->getMessage(),
            ));

        } else {
            $response = parent::onAuthenticationFailure($request, $exception);
        }

        return $response;
    }
}
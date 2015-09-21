<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 21/09/15
 * Time: 23:46
 */

namespace JDJ\UserBundle\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;


/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return JsonResponse|RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token ) {

        if ($request->isXmlHttpRequest()) {
            $response = new JsonResponse(array(
                'has_error'   => false,
                'username'    => $token->getUser()->getUsername(),
            ));
        } else {
            $response = parent::onAuthenticationSuccess($request, $token);
        }

        return $response;
    }
}
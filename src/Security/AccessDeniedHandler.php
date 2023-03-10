<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException, ): ?RedirectResponse
    {
        // add a custom flash message and redirect to the login page
        $request->getSession()->getFlashBag()->add('error', 'Tu n\'as pas les droits pour aller sur cette page');

        return new RedirectResponse($this->urlGenerator->generate('home.index'));
    }
}

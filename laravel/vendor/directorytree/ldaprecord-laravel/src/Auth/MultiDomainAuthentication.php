<?php

namespace LdapRecord\Laravel\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait MultiDomainAuthentication
{
    use CreatesUserProvider;

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function getLdapGuard()
    {
        return Auth::guard(
            $this->getCurrentAuthGuard() ?? $this->getLdapGuardFromRequest(request())
        );
    }

    /**
     * Get the LDAP domain from the request.
     *
     * @param Request $request
     *
     * @return string|null
     */
    protected function getLdapGuardFromRequest(Request $request)
    {
        return $request->get('domain', config('ldap.default'));
    }
}

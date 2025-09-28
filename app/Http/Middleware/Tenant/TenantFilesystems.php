<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tenant\ManagerTenant;

class TenantFilesystems
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(auth()->check())

       $this->setFilessystemsRoot();


        return $next($request);
    }


    public function setFilessystemsRoot()
    {

         $tenant = app(ManagerTenant::class)->getTenant();

        if ($tenant) {
            $path = storage_path("app/public/tenants/{$tenant->uuid}");

            if (!\Illuminate\Support\Facades\File::exists($path)) {
                \Illuminate\Support\Facades\File::makeDirectory($path, 0755, true);
            }

            // Redefine o root do disco tenant dinamicamente
            config()->set('filesystems.disks.tenant.root', $path);
        }
    }






}

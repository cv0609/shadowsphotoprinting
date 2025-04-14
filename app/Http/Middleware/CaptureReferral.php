<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Affiliate;


class CaptureReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $affiliate = Affiliate::where('referral_code',$request->query('ref'))->first();

            session(['referral_code' => $request->query('ref'),'referral_coupon_code'=>$affiliate->coupon_code]);
        }

        return $next($request);
    }
}

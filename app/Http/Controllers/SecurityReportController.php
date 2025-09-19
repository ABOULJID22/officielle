<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SecurityReportController extends Controller
{
    public function csp(Request $request)
    {
        // CSP reports are JSON payloads sent by browsers; no CSRF token
        $report = $request->getContent();
        if (blank($report)) {
            return response()->noContent();
        }

        Log::channel(config('logging.default'))
            ->info('[CSP-REPORT]', ['payload' => json_decode($report, true)]);

        return response()->noContent();
    }
}

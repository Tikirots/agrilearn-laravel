<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateVerifyController extends Controller
{
    /**
     * Public certificate verification page — no login required.
     * This is the URL encoded inside every certificate's QR code.
     */
    public function show(string $code = '')
    {
        $certificate = trim($code) !== ''
            ? Certificate::with(['trainee', 'program'])->where('certificate_code', $code)->first()
            : null;

        return view('certificate_verify', compact('certificate'));
    }
}

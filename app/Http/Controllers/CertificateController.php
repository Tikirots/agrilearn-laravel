<?php
namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller {
    public function verify($code) {
        $certificate = Certificate::where('certificate_code', $code)->with(['trainee.user', 'program'])->first();
        
        if (!$certificate) {
            return view('certificates.invalid');
        }

        return view('certificates.verify', compact('certificate'));
    }
}

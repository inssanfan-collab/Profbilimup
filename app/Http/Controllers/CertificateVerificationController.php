<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Contracts\View\View;

class CertificateVerificationController extends Controller
{
    public function show(string $qrToken): View
    {
        $certificate = Certificate::where('qr_token', $qrToken)
            ->with(['courseAssignment.listener.listenerProfile', 'courseAssignment.course'])
            ->first();

        return view('certificates.verify', ['certificate' => $certificate]);
    }
}

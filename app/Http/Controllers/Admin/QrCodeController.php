<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Response;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    use AuthorizesBusinessAccess;

    /**
     * Show QR code dashboard and customization for a business.
     */
    public function show(Business $business): View
    {
        $business = $this->getAuthorizedBusiness($business);
        $url = $business->public_url;

        // Generate SVG string
        $qrSvg = QrCode::size(280)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url);

        return view('admin.qr.show', compact('business', 'qrSvg', 'url'));
    }

    /**
     * Download the QR code as an SVG file.
     */
    public function download(Business $business): Response
    {
        $business = $this->getAuthorizedBusiness($business);
        $url = $business->public_url;

        $qrSvg = QrCode::size(600)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url);

        $filename = "{$business->slug}-qr-code.svg";

        return response($qrSvg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Render the print-ready table-tent / counter sticker template.
     */
    public function print(Business $business): View
    {
        $business = $this->getAuthorizedBusiness($business);
        $url = $business->public_url;

        $qrSvg = QrCode::size(240)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url);

        return view('admin.qr.print', compact('business', 'qrSvg', 'url'));
    }
}

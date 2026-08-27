<?php

namespace App\Http\Controllers;

use App\Usecase\SubscriptionUsecase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionUsecase $usecase
    ) {}

    public function showForm(Request $request): View
    {
        $package = $request->query('package', 'pro');

        return view('landing.subscribe', [
            'package' => $package,
        ]);
    }

    public function doSubscribe(Request $request): RedirectResponse
    {
        $process = $this->usecase->subscribe($request);

        if ($process['success']) {
            $invoiceNumber = $process['data']['invoice_number'];

            return redirect()->route('payment.invoice', $invoiceNumber)
                ->with('success', 'Pendaftaran institusi berhasil. Silakan selesaikan pembayaran invoice Anda.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $process['message'] ?? 'Gagal memproses pendaftaran.');
    }

    public function showPayment(string $invoiceNumber): View
    {
        $process = $this->usecase->getInvoice($invoiceNumber);

        if (! $process['success'] || empty($process['data'])) {
            abort(404, 'Tagihan pembayaran tidak ditemukan.');
        }

        return view('landing.payment_invoice', [
            'invoice' => (object) $process['data'],
        ]);
    }
}

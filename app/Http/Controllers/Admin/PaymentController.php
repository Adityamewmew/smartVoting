<?php

namespace App\Http\Controllers\Admin;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Usecase\PaymentUsecase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    protected array $page = [
        'route' => 'payments',
        'title' => 'Pembayaran & Billing',
    ];

    protected string $baseRedirect;

    public function __construct(
        protected PaymentUsecase $usecase
    ) {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }

    public function index(Request $request): View|Response
    {
        $data = $this->usecase->getAll([
            'keywords' => $request->get('keywords'),
            'status' => $request->get('status'),
            'institution_id' => $request->get('institution_id'),
        ]);
        $data = $data['data']['list'] ?? [];

        $institutions = DB::table(DatabaseConst::INSTITUTION())
            ->whereNull('deleted_at')
            ->orderBy('name', 'asc')
            ->get();

        return view('_admin.payments.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
            'status' => $request->get('status', 'all'),
            'institution_id' => $request->get('institution_id', 'all'),
            'institutions' => $institutions,
        ]);
    }

    public function add(): View|Response
    {
        $institutions = DB::table(DatabaseConst::INSTITUTION())
            ->whereNull('deleted_at')
            ->orderBy('name', 'asc')
            ->get();

        return view('_admin.payments.add', [
            'page' => $this->page,
            'institutions' => $institutions,
        ]);
    }

    public function doCreate(Request $request): RedirectResponse
    {
        $process = $this->usecase->create(data: $request);

        if ($process['success']) {
            return redirect()->route('admin.payments.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
        }

        return redirect()->back()->withInput()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function detail(int $id): View|RedirectResponse|Response
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.payments.detail', [
            'data' => (object) $data['data'],
            'page' => $this->page,
        ]);
    }

    public function update(int $id): View|RedirectResponse|Response
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.payments.update', [
            'data' => (object) $data['data'],
            'page' => $this->page,
        ]);
    }

    public function doUpdate(Request $request, int $id): RedirectResponse
    {
        $process = $this->usecase->update(data: $request, id: $id);

        if ($process['success']) {
            return redirect()->route('admin.payments.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
        }

        return redirect()->back()->withInput()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function confirmPayment(int $id): RedirectResponse
    {
        $process = $this->usecase->confirmPayment($id);

        if ($process['success']) {
            return redirect()->back()
                ->with('success', 'Pembayaran berhasil dikonfirmasi lunas.');
        }

        return redirect()->back()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function delete(int $id): RedirectResponse
    {
        $process = $this->usecase->delete($id);

        if ($process['success']) {
            return redirect()->route('admin.payments.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
        }

        return redirect()->back()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }
}

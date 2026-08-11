<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Usecase\ElectionUsecase;
use App\Usecase\LivePollingUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ElectionController extends Controller
{
    protected array $page = [
        'route' => 'elections',
        'title' => 'Event Pemilihan',
    ];

    protected string $baseRedirect;

    public function __construct(
        protected ElectionUsecase $usecase,
        protected LivePollingUsecase $livePollingUsecase
    ) {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }

    public function index(Request $request): View|Response
    {
        $data = $this->usecase->getAll([
            'keywords' => $request->get('keywords'),
        ]);
        $data = $data['data']['list'] ?? [];

        return view('_admin.elections.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
        ]);
    }

    public function add(): View|Response
    {
        return view('_admin.elections.add', [
            'page' => $this->page,
        ]);
    }

    public function doCreate(Request $request): RedirectResponse
    {
        $process = $this->usecase->create(data: $request);

        if ($process['success']) {
            return redirect()->route('admin.elections.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
        }

        return redirect()->back()->withInput()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function update(int $id): View|RedirectResponse|Response
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        return view('_admin.elections.update', [
            'data' => (object) $data['data'],
            'page' => $this->page,
        ]);
    }

    public function doUpdate(int $id, Request $request): RedirectResponse
    {
        $process = $this->usecase->update(data: $request, id: $id);

        if ($process['success']) {
            return redirect()->route('admin.elections.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
        }

        return redirect()->back()->withInput()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function delete(int $id): RedirectResponse
    {
        $process = $this->usecase->delete(id: $id);

        if ($process['success']) {
            return redirect()->route('admin.elections.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
        }

        return redirect()->route('admin.elections.index')
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function detail(int $id): View|RedirectResponse
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $process = $this->livePollingUsecase->getLiveResults($id);

        if (! $process['success']) {
            return redirect()->route('admin.elections.index')->with('error', 'Gagal memuat data laporan.');
        }

        // Fetch recent voting sessions (T-07)
        $processSessions = $this->livePollingUsecase->getRecentSessions(20, $id);
        $recentSessions = $processSessions['data']['sessions'] ?? [];

        return view('_admin.elections.detail', [
            'election' => (object) $data['data'],
            'page' => $this->page,
            'totalVotes' => $process['data']['total_votes'],
            'candidates' => $process['data']['candidates'],
            'recentSessions' => $recentSessions,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Usecase\CandidateUsecase;
use App\Usecase\ElectionUsecase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateController extends Controller
{
    protected array $page = [
        'route' => 'candidates',
        'title' => 'Kandidat Paslon',
    ];

    protected string $baseRedirect;

    public function __construct(
        protected CandidateUsecase $usecase,
        protected ElectionUsecase $electionUsecase
    ) {
        $this->baseRedirect = 'admin.'.$this->page['route'].'.index';
    }

    public function index(Request $request): View
    {
        $elections = $this->electionUsecase->getAll(['no_pagination' => true]);
        $elections = $elections['data']['list'] ?? [];

        // If no election_id is provided, try to select the first active or scheduled election, or just the first one
        $electionId = $request->get('election_id');
        if (! $electionId && count($elections) > 0) {
            $electionId = $elections[0]->id;
        }

        $data = [];
        if ($electionId) {
            $response = $this->usecase->getAll([
                'keywords' => $request->get('keywords'),
                'election_id' => $electionId,
            ]);
            $data = $response['data']['list'] ?? [];
        }

        return view('_admin.candidates.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
            'elections' => $elections,
            'selectedElectionId' => $electionId,
        ]);
    }

    public function add(Request $request): View
    {
        $elections = $this->electionUsecase->getAll(['no_pagination' => true]);
        $elections = $elections['data']['list'] ?? [];

        return view('_admin.candidates.add', [
            'page' => $this->page,
            'elections' => $elections,
            'selectedElectionId' => $request->get('election_id'),
        ]);
    }

    public function doCreate(Request $request): RedirectResponse
    {
        $process = $this->usecase->create(data: $request);

        if ($process['success']) {
            return redirect()->route($this->baseRedirect, ['election_id' => $request->input('election_id')])
                ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
        }

        return redirect()->back()->withInput()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function update(int $id): View|RedirectResponse
    {
        $data = $this->usecase->getByID($id);

        if (empty($data['data'])) {
            return redirect()->route($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $elections = $this->electionUsecase->getAll(['no_pagination' => true]);
        $elections = $elections['data']['list'] ?? [];

        return view('_admin.candidates.update', [
            'data' => (object) $data['data'],
            'page' => $this->page,
            'elections' => $elections,
        ]);
    }

    public function doUpdate(Request $request, int $id): RedirectResponse
    {
        $process = $this->usecase->update(data: $request, id: $id);

        if ($process['success']) {
            return redirect()->route($this->baseRedirect, ['election_id' => $request->input('election_id')])
                ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
        }

        return redirect()->back()->withInput()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }

    public function delete(int $id): RedirectResponse
    {
        $candidate = $this->usecase->getByID($id);
        $electionId = $candidate['data']['election_id'] ?? null;

        $process = $this->usecase->delete(id: $id);

        if ($process['success']) {
            return redirect()->route($this->baseRedirect, ['election_id' => $electionId])
                ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
        }

        return redirect()->back()
            ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
    }
}

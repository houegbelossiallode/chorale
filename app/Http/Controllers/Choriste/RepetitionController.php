<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Repetition;
use Illuminate\Http\Request;

class RepetitionController extends Controller
{
    /**
     * Display a listing of rehearsals for choristers.
     */
    public function index()
    {
        $repetitions = Repetition::with(['event.repertoireEntries.chant', 'event.repertoireEntries.partieEvent', 'chants'])
            ->withCount('presences')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('choriste.repetitions.index', compact('repetitions'));
    }
}

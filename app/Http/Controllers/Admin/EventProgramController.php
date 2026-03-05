<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PartieEvent;
use App\Models\Chant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EventProgramController extends Controller
{
    public function index(Event $event)
    {
        // On récupère le répertoire de l'événement avec les chants et les parties associées
        $repertoire = DB::table('repertoire')
            ->leftJoin('chants', 'repertoire.chant_id', '=', 'chants.id')
            ->leftJoin('partie_events', 'repertoire.partie_event_id', '=', 'partie_events.id')
            ->where('repertoire.event_id', $event->id)
            ->select('repertoire.id', 'chants.title as chant_title', 'partie_events.titre as partie_titre')
            ->orderBy('partie_events.ordre')
            ->get();

        $allChants = Chant::all();
        $allParties = PartieEvent::orderBy('ordre')->get();

        return view('admin.events.program.index', compact('event', 'repertoire', 'allChants', 'allParties'));
    }

    public function downloadPdf(Event $event)
    {
        // Nettoyage radical des tampons de sortie
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $repertoire = DB::table('repertoire')
            ->leftJoin('chants', 'repertoire.chant_id', '=', 'chants.id')
            ->leftJoin('partie_events', 'repertoire.partie_event_id', '=', 'partie_events.id')
            ->where('repertoire.event_id', $event->id)
            ->select('chants.title as chant_title', 'chants.parole', 'partie_events.titre as partie_titre', 'partie_events.ordre as partie_ordre')
            ->orderBy('partie_events.ordre')
            ->get();

        // On construit le HTML de manière ultra-linéaire (sans retours à la ligne inutiles)
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8">';
        $html .= '<style>body{font-family:sans-serif;font-size:11pt;margin:0;padding:20px;}';
        $html .= '.header{text-align:center;margin-bottom:20px;}';
        $html .= '.logo{max-width:60px;margin-bottom:5px;}';
        $html .= '.chorale-name{font-weight:bold;font-size:12pt;display:block;}';
        $html .= '.title{font-weight:bold;font-size:14pt;margin-top:5px;}';
        $html .= '.main-table{width:100%;border-collapse:collapse;}';
        $html .= '.column{width:50%;vertical-align:top;padding:0 10px;}';
        $html .= '.item{margin-bottom:15px;}';
        $html .= '.partie{text-decoration:underline;font-weight:bold;text-transform:uppercase;}';
        $html .= '.lyrics{font-family:serif;font-size:10pt;margin-top:2px;display:block;line-height:1.1;}';
        $html .= '.lyrics p { margin: 0; padding: 0; line-height: 1.1; }';
        $html .= '</style></head><body>';

        // Encodage du logo en base64 pour PDF
        $logoPath = public_path('images/logo chorale st oscar romero noir fond blanc.png');
        $logoHtml = '';
        if (file_exists($logoPath)) {
            $base64 = base64_encode(file_get_contents($logoPath));
            $logoHtml = '<img src="data:image/png;base64,' . $base64 . '" class="logo"><br>';
        }

        $html .= '<div class="header">';
        $html .= $logoHtml;
        $html .= '<span class="chorale-name">Paroisse Ste Mère Térésa - Chapelle St Oscar Romero - Groupe Louange</span>';
        $html .= '<div class="title">' . e($event->title) . '</div>';
        $html .= '<div>' . \Carbon\Carbon::parse($event->start_at)->translatedFormat('j F Y') . '</div></div>';

        $html .= '<table class="main-table">';

        $count = $repertoire->count();
        $mid = (int) ceil($count / 2);

        // Conversion en tableaux indexés
        $col1 = $repertoire->slice(0, $mid)->values();
        $col2 = $repertoire->slice($mid)->values();

        for ($i = 0; $i < $mid; $i++) {
            $html .= '<tr>';

            // Colonne gauche
            $html .= '<td class="column">';
            if (isset($col1[$i])) {
                $item = $col1[$i];
                $html .= '<div class="item"><span class="partie">' . e($item->partie_titre) . '</span> : <strong>' . e($item->chant_title) . '</strong>';
                if ($item->parole) {
                    $html .= '<div class="lyrics">' . $item->parole . '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</td>';

            // Colonne droite
            $html .= '<td class="column">';
            if (isset($col2[$i])) {
                $item = $col2[$i];
                $html .= '<div class="item"><span class="partie">' . e($item->partie_titre) . '</span> : <strong>' . e($item->chant_title) . '</strong>';
                if ($item->parole) {
                    $html .= '<div class="lyrics">' . $item->parole . '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</td>';

            $html .= '</tr>';
        }
        $html .= '</table></body></html>';

        return Pdf::loadHTML($html)->setPaper('a4', 'portrait')->download('repertoire.pdf');
    }

    public function storeRepertoire(Request $request, Event $event)
    {
        $validated = $request->validate([
            'chant_id' => [
                'required',
                'exists:chants,id',
                function ($attribute, $value, $fail) use ($event) {
                    if (DB::table('repertoire')->where('event_id', $event->id)->where('chant_id', $value)->exists()) {
                        $fail('Ce chant est déjà présent dans le programme de cet événement.');
                    }
                },
            ],
            'partie_event_id' => [
                'required',
                'exists:partie_events,id',
                function ($attribute, $value, $fail) use ($event) {
                    if (DB::table('repertoire')->where('event_id', $event->id)->where('partie_event_id', $value)->exists()) {
                        $fail('Cette partie est déjà utilisée dans le programme de cet événement.');
                    }
                },
            ],
        ]);

        DB::table('repertoire')->insert([
            'event_id' => $event->id,
            'chant_id' => $validated['chant_id'],
            'partie_event_id' => $validated['partie_event_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Chant ajouté au répertoire.');
    }

    public function toggleVisibility(Event $event)
    {
        DB::table('events')
            ->where('id', $event->id)
            ->update(['is_repertoire_public' => DB::raw('NOT is_repertoire_public')]);

        return back()->with('success', 'Visibilité du répertoire mise à jour.');
    }

    public function destroyRepertoire($id)
    {
        DB::table('repertoire')->where('id', $id)->delete();
        return back()->with('success', 'Élément retiré du répertoire.');
    }
}

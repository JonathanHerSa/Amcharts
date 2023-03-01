<?php

namespace App\Http\Controllers;

use App\Events\UpdateChart;
use App\Models\Amchart;
use App\Models\Chartgrid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AmchartController extends Controller
{
    public function index()
    {
        $datos = Chartgrid::all();
        return Inertia::render('GraficaDragable', ['datos' => $datos]);
    }

    public function store(Request $request)
    {
        $catA = Amchart::where('id', $request->ValorB)->first();
        $catB = Amchart::where('id', $request->ValorB)->first();

        if ($catA->id === $catB->id) return;

        $catB->update([
            'value' => $catA->value + $catB->value,
        ]);
        $catA->update([
            'value' => 0,
        ]);

        return redirect(route('am5.index'));
    }

    public function grid(Request $request)
    {
        $catA = Chartgrid::where('id', $request->ValorA)->first();
        $catB = Chartgrid::where('id', $request->ValorB)->first();

        if ($catA->id === $catB->id) {
            event(new UpdateChart);
            return redirect(route('am5.index'));
        }

        $catB->update([
            'value' => $catA->value + $catB->value,
        ]);
        $catA->update([
            'value' => 0,
        ]);

        event(new UpdateChart);
        return redirect(route('am5.index'));
    }
    public function notes(Request $request)
    {
        dd($request->ValorA);
    }
}

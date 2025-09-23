<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TimeSlotController extends Controller
{
    // ADMIN: gerar horários para um dia
    public function generate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->date);
        $start = Carbon::create($date->year, $date->month, $date->day, 8, 0, 0);
        $end = Carbon::create($date->year, $date->month, $date->day, 17, 0, 0);

        $slotsCreated = 0;
        while ($start <= $end) {
            $time = $start->format('H:i');

            TimeSlot::firstOrCreate([
                'date' => $date->format('Y-m-d'),
                'time' => $time,
            ], [
                'status' => 0, // liberada
            ]);

            $slotsCreated++;
            $start->addMinutes(30);
        }

        return response()->json([
            'message' => "$slotsCreated slots criados para " . $date->format('Y-m-d')
        ]);
    }

    // LISTA horários disponíveis para o cliente
    public function available(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $slots = TimeSlot::where('date', $request->date)
            ->where('status', 0) // liberada
            ->orderBy('time')
            ->get();

        return response()->json($slots);
    }

    // RESERVAR horário (cliente)
    public function reserve(Request $request, $id)
    {
        $slot = TimeSlot::findOrFail($id);

        if ($slot->status != 0) {
            return response()->json(['message' => 'Slot não disponível'], 422);
        }

        $slot->update([
            'status' => 1, // reservada
            'user_id' => Auth::id(),
        ]);

        return response()->json($slot);
    }

    // OPCIONAL: Listar todos slots (admin)
    public function index()
    {
        return TimeSlot::with('user')->orderBy('date')->orderBy('time')->get();
    }
}

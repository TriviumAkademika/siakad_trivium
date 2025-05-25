<?php

namespace App\Http\Controllers;

use App\Models\Waktu;
use Illuminate\Http\Request;

class WaktuController extends Controller
{
    public function index(Request $request)
    {
        $query = Waktu::query();

        // Handle day filter
        if ($request->has('hari') && !empty($request->hari)) {
            $selectedDays = is_array($request->hari) ? $request->hari : [$request->hari];
            $query->whereIn('hari', $selectedDays);
        }

        // Handle sorting
        $sortBy = $request->get('sort', 'hari');
        $sortDirection = $request->get('direction', 'asc');

        switch($sortBy) {
            case 'hari':
                // Custom sort for days of week
                $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')");
                break;
            case 'id':
                $query->orderBy('id_waktu', $sortDirection);
                break;
            case 'jam_mulai':
                $query->orderBy('jam_mulai', $sortDirection);
                break;
            case 'jam_selesai':
                $query->orderBy('jam_selesai', $sortDirection);
                break;
            default:
                $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat')");
        }

        // Paginate results
        $waktu = $query->paginate(10);
        
        // Preserve query parameters in pagination links
        $waktu->appends($request->query());

        return view('waktu.index', compact('waktu'));
    }

    public function create()
    {
        return view('waktu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|string|max:10',
            'jam_selesai' => 'required|string|max:10',
        ]);

        Waktu::create($request->all());

        return redirect()->route('waktu.index')->with('success', 'Data waktu berhasil ditambahkan!');
    }

    public function show(Waktu $waktu)
    {
        return view('waktu.show', compact('waktu'));
    }

    public function edit($id)
    {
        $waktu = Waktu::findOrFail($id);
        return view('waktu.edit', compact('waktu'));
    }

    public function update(Request $request, $id)
    {
        $waktu = Waktu::findOrFail($id);

        $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|string|max:10',
            'jam_selesai' => 'required|string|max:10',
        ]);

        $waktu->update($request->all());

        return redirect()->route('waktu.index')->with('success', 'Data waktu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $waktu = Waktu::findOrFail($id);
        $waktu->delete();

        return redirect()->route('waktu.index')->with('success', 'Data waktu berhasil dihapus!');
    }
}
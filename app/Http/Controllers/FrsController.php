<?php

namespace App\Http\Controllers;

use App\Models\Frs;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use Illuminate\Http\Request;


class FrsController extends Controller
{
    public function index()
    {
        $frs = Frs::with('mahasiswa')->get();
        return view('frs.index', compact('frs'));
    }

    public function create()
    {
        $mahasiswa = Mahasiswa::all();
        return view('frs.create', compact('mahasiswa'));
    }

    public function store(Request $request)
    {
        Frs::create($request->all());
        return redirect()->route('frs.index')->with('success', 'FRS created.');
    }

    public function edit($id)
    {
        $frs = Frs::findOrFail($id);
        $mahasiswa = Mahasiswa::all();
        return view('frs.edit', compact('frs', 'mahasiswa'));
    }

    public function update(Request $request, $id)
    {
        $frs = Frs::findOrFail($id);
        $frs->update($request->all());
        return redirect()->route('frs.index')->with('success', 'FRS updated.');
    }

    public function destroy($id)
    {
        Frs::destroy($id);
        return redirect()->route('frs.index')->with('success', 'FRS deleted.');
    }

    public function show($id)
    {
        $frs = Frs::with('mahasiswa', 'detailFrs.jadwal.matkul', 'detailFrs.jadwal.dosen', 'detailFrs.jadwal.ruangan', 'detailFrs.jadwal.waktu')->findOrFail($id);
        $jadwals = Jadwal::with('matkul', 'dosen', 'ruangan', 'waktu')->get();

        return view('detail_frs.index', compact('frs', 'jadwals'));
    }
}

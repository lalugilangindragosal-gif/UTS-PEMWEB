<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AnggotaController extends Controller
{
    private function buatDeskripsi($nim, $peran)
    {
        return $peran === 'master'
            ? "$nim merupakan master dari uts ini dengan tugas membuat front-end dan mengirimkannya ke grup"
            : "dengan NIM $nim merupakan pegawai dan mempunyai tugas mengerjakan back-end pada UTS ini";
    }

    public function index()
    {
        $anggotas = Anggota::orderBy('id', 'asc')->get();
        return view('welcome', compact('anggotas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'nim'   => 'required|string|max:30',
            'peran' => 'required|in:master,pegawai',
            'foto'  => 'required|image|max:2048',
        ]);

        $namaFile = time() . '_' . $request->file('foto')->getClientOriginalName();
        $request->file('foto')->move(public_path('uploads'), $namaFile);

        Anggota::create([
            'nama'      => $request->nama,
            'nim'       => $request->nim,
            'peran'     => $request->peran,
            'deskripsi' => $this->buatDeskripsi($request->nim, $request->peran),
            'foto'      => $namaFile,
        ]);

        return redirect()->route('anggota.index')->with('pesan', 'Biodata berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'nama'  => 'required|string|max:100',
            'nim'   => 'required|string|max:30',
            'peran' => 'required|in:master,pegawai',
            'foto'  => 'nullable|image|max:2048',
        ]);

        $namaFile = $anggota->foto;
        if ($request->hasFile('foto')) {
            $pathLama = public_path('uploads/' . $anggota->foto);
            if (File::exists($pathLama)) {
                File::delete($pathLama);
            }
            $namaFile = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('uploads'), $namaFile);
        }

        $anggota->update([
            'nama'      => $request->nama,
            'nim'       => $request->nim,
            'peran'     => $request->peran,
            'deskripsi' => $this->buatDeskripsi($request->nim, $request->peran),
            'foto'      => $namaFile,
        ]);

        return redirect()->route('anggota.index')->with('pesan', 'Biodata berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $pathLama = public_path('uploads/' . $anggota->foto);
        if (File::exists($pathLama)) {
            File::delete($pathLama);
        }

        $anggota->delete();
        return redirect()->route('anggota.index')->with('pesan', 'Biodata berhasil diperbarui.');
    }
}
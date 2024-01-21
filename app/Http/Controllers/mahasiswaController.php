<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class mahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = mahasiswa::orderBy('nim', 'desc')->paginate(2);
        return view('mahasiswa.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // menggunakan session flash untuk mengirim pesan
        Session::flash('nim', $request->nim);
        Session::flash('nama', $request->nama);
        Session::flash('jurusan', $request->jurusan);
        Session::flash('alamat', $request->alamat);
        // validasi form input
        $request->validate([
            'nim'=>'required|numeric|unique:mahasiswa,nim',
            'nama'=>'required',
            'jurusan'=>'required',
            'alamat'=>'required',
        ],[
            'nim.required=>"NIM wajib di isi!',
            'nim.numeric=>"NIM wajib di isi dengan angka!',
            'nim.unique=>"NIM yang telah diisi sudah ada dalam data',
            'nama.required=>"Nama wajib di isi!',
            'jurusan.required=>"Jurusan wajib di isi!',
            'alamat.required=>"Alamat wajib di isi!',
        ]);

        $data = [
            'nim' => $request -> nim,
            'nama' => $request -> nama,
            'jurusan' => $request -> jurusan,
            'alamat' => $request -> alamat,
        ];
        // insert data ke database
        mahasiswa::create($data);
        // redirect to home with send message   
        return redirect()->to('mahasiswa')->with('success','Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = mahasiswa::where('nim', $id)->first();
        return view('mahasiswa.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'=>'required',
            'jurusan'=>'required',
            'alamat'=>'required',
        ],[
            'nama.required=>"Nama wajib di isi!',
            'jurusan.required=>"Jurusan wajib di isi!',
            'alamat.required=>"Alamat wajib di isi!',
        ]);
        $data = [
            'nama' => $request -> nama,
            'jurusan' => $request -> jurusan,
            'alamat' => $request -> alamat,
        ];
        mahasiswa::where('nim',$id)->update($data);
        return redirect()->to('mahasiswa')->with('success','Berhasil melakukan update data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        mahasiswa::where('nim',$id)->delete();

        return redirect()->to('mahasiswa')->with('success','Berhasil melakukan delete data');
    }
}

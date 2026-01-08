<?php

namespace App\Http\Controllers;

use App\Models\Detail_Transaction;
use App\Models\Products;
use App\Models\return_pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnPesananController extends Controller
{
    public function Retur_Submit(Request $request)
    {
        $retur = new return_pesanan();
        $retur->Barang = $request->Barang;
        $retur->id_detil_transaksi = $request->id_detil_transaksi;
        $retur->qty_retur = $request->qty_retur;
        $retur->save();
        // dd("ini",$retur);
        return redirect()->route('fill-data', [
            'retur_id' => $retur
        ]);
    }

    public function fill_data($retur_id)
    {
        // dd('fill',$retur_data);
        $retur = DB::table('return_pesanans as a')
                ->join('products as b', 'b.id_product', '=', 'a.Barang')
                ->select('a.*', 'b.*')
                ->where('a.id','=',$retur_id)
                ->get()->first();
        return view('User.Pelanggan.form-retur',['data'=>$retur]);
        // dd('data', $retur);
    }

    public function ajukan_data(Request $req){
        // dd($req);
        $retur = return_pesanan::where('id', $req->id)->first();
        $retur->alasan_retur=$req->alasan_retur;
        $retur->link_bukti=$req->link_bukti;
        $retur->persetujuan_1=$req->confirm_bukti;
        $retur->persetujuan_2=$req->confirm_norefund;
        $retur->save();


        $dt = Detail_Transaction::where('id_Detail_transaction',$retur->id_detil_transaksi)->first();
        return redirect('/Transaction/'.$dt->Transaksis_id)->with('message', 'Retur Berhasil Diajukan, Silahkan Menunggu persetujuan!');
    }

    public function terima(Request $req){
        // dd($req);
        $retur = return_pesanan::where('id', $req->id_retur)->first();
        $retur->retur_status = true;
        $retur->save();
        return redirect()->back()->with('message', 'Jangan Lupa untuk melengkapi Resi!');
    }
    
    public function tolak(Request $req){
        // dd($req);
        $retur = return_pesanan::where('id', $req->id_retur)->first();
        $retur->retur_status = false;
        $retur->alasan_ditolak = $req->alasan_menolak;
        $retur->save();
        return redirect()->back()->with('message', 'Sedih Mendengarnya!');
    }

    public function input_resi(Request $req){
        // dd($req);
        $retur = return_pesanan::where('id', $req->id_retur)->first();
        $retur->Ekspedisi = $req->ekspedisi;
        $retur->Resi = $req->resi;
        $retur->save();
        return redirect()->back()->with('message', 'Resi Berhasil Ditambahkan!');
    }
}

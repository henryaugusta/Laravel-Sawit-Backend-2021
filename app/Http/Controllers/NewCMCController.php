<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\CMCTimeline;
use App\Models\PoRequest;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewCMCController extends Controller
{

    public function editCMCView(Request $request, $id)
    {
        $data = PoRequest::findOrFail($id);
        $ekspedisiList = Truck::all();
        $users = User::all();
        if (Auth::user()->role == 4) {
            $users = User::where("role", '=', '4')->get();
        }
        $timelineEntries = CMCTimeline::where("po_requests_id",'=',$id)->orderBy("created_at","DESC")->get();
        // Assuming $timelineEntries is a collection of CMCTimeline objects
        return view('cmc.edit')->with(compact('data', 'ekspedisiList', 'users','timelineEntries'));
    }

    public function commercialRequestView(Request $request)
    {
        $datas = PoRequest::where('last_process_by', '=', 3)->get();
        return view('cmc.my_request')->with(compact('datas'));
    }

    public function warehouseRequestView(Request $request)
    {
        $datas = PoRequest::where('last_process_by', '=', 2)->get();
        return view('cmc.my_request')->with(compact('datas'));
    }

    public function warehouseInputtedView(Request $request)
    {
        $datas = PoRequest::where('last_process_by', '=', 4)->get();
        $topTitle = "Manage Permintaan";
        $topSubTitle = "Permintaan Yang Sedang Diantarkan";
        $bodyTitle = "Permintaan Sedang Diantar";
        return view('cmc.my_request')->with(
            compact(
                'datas',
                'bodyTitle',
                'topTitle',
                'topSubTitle')
        );
    }


    public function commercialPOInputtedView(Request $request)
    {
        $datas = PoRequest::where('last_process_by', '=', 3)->get();
        $topTitle = "Manage Permintaan";
        $topSubTitle = "Permintaan Yang Telah Diinput Nomor PO-nya";
        $bodyTitle = "Permintaan Telah Diinput";
        return view('cmc.my_request')->with(
            compact(
                'datas',
                'bodyTitle',
                'topTitle',
                'topSubTitle')
        );
    }

    public function myRequestView(Request $request)
    {
        $datas = PoRequest::where('disiapkan_oleh', '=', Auth::id())->get();
        return view('cmc.my_request')->with(compact('datas'));
    }

    public function newRequest(Request $request)
    {
        $ekspedisiList = Truck::all();
        $compact = compact('ekspedisiList');
        return view('cmc.customer.new_request')->with($compact);
    }

    public function storeNewRequest(Request $request)
    {
        //new request by customer;
        $poRequest = new PoRequest(); // Replace with your actual model

        $poRequest->nomor_surat_jalan = $request->input('nomor_surat_jalan');
        $poRequest->nomor_surat_jalan_date = $request->input('nomor_surat_jalan_date');
        $poRequest->order_reference = $request->input('order_reference');
        $poRequest->order_penjualan_nomor = $request->input('order_penjualan_nomor');
//        $poRequest->order_penjualan_nomor_date = $request->input('order_penjualan_nomor_date');
        $poRequest->ekspedisi = $request->input('ekspedisi');
        $poRequest->alamat_pengambilan = $request->input('alamat_pengambilan');
        $poRequest->dijual_kepada = $request->input('dijual_kepada');
        $poRequest->dikirim_ke = $request->input('dikirim_ke');
        $poRequest->comment_customer = $request->input('comment_customer');
        $poRequest->disiapkan_oleh = Auth::id();

        // Serialize and store the products
        $products = [];

        foreach ($request->input('kode_produk') as $key => $kodeProduk) {
            $products[] = [
                'kode_produk' => $kodeProduk,
                'uraian' => $request->input('uraian')[$key],
                'jumlah' => $request->input('jumlah')[$key],
                'unit' => $request->input('unit')[$key],
                'berat_kotor' => $request->input('berat_kotor')[$key],
                'volume' => $request->input('volume')[$key],
            ];
        }

        $poRequest->last_process_by = Auth::user()->role;
        $poRequest->products = json_encode($products);

        if ($request->hasFile('attachment')) {

            $file_path = public_path() . $poRequest->photo;
            MyHelper::removeFile($file_path);

            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            $savePath = "/web_files/po_attachment/";
            $savePathDB = "$savePath$fileName";
            $path = public_path() . "$savePath";
            $file->move($path, $fileName);

            $photoPath = $savePathDB;
            $poRequest->attachment = $photoPath;
        }
        $poRequest->deadline = $request->deadline;


        // Save the data
        if ($poRequest->save()) {
            //add logger to timeline
            CMCTimeline::create([
                'action' => "Pembuatan Permintaan Baru",
                'po_requests_id' => $poRequest->id,
                'last_man' => Auth::id(),
                'edited_field' => $poRequest,
            ]);
            return back()->with(["success" => "Data saved successfully"]);
        } else {
            return back()->with(["error" => "Saving process failed"]);
        }
    }

    public function updateRequest(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'nomor_surat_jalan' => '',
            'nomor_surat_jalan_date' => 'date',
            'order_reference' => '',
            'order_penjualan_nomor' => '',
            'ekspedisi' => '',
            'alamat_pengambilan' => '',
            'dijual_kepada' => '',
            'id_driver' => '',
            'id_armada' => '',
            'dikirim_ke' => '',
            'po_number' => '',
            'comment_commercial' => '',
            'kode_produk.*' => '',
            'uraian.*' => '',
            'jumlah.*' => 'numeric|min:0',
            'unit.*' => '',
            'berat_kotor.*' => 'numeric|min:0',
            'volume.*' => 'numeric|min:0',
        ]);

        // Find the existing PoRequest instance
        $poRequest = PoRequest::findOrFail($id);

        // Update the attributes
        $poRequest->fill($validatedData);

        // Serialize and update the products
        $products = [];
//        foreach ($validatedData['kode_produk'] as $key => $kodeProduk) {
//            $products[] = [
//                'kode_produk' => $kodeProduk,
//                'uraian' => $validatedData['uraian'][$key],
//                'jumlah' => $validatedData['jumlah'][$key],
//                'unit' => $validatedData['unit'][$key],
//                'berat_kotor' => $validatedData['berat_kotor'][$key],
//                'volume' => $validatedData['volume'][$key],
//            ];
//        }
        $poRequest->last_process_by = Auth::user()->role;
//        $poRequest->products = json_encode($products);

        $action = "Edit Data";

        if (Auth::user()->role==2 && $request->po_number!=""){
            $action = Auth::user()->name." "."Telah Menginput Nomor PO :"." ".$request->po_number;
        }
        if (Auth::user()->role==4 && $request->po_number!=""){
            $action = Auth::user()->name." "."Telah Menginput Armada Ekspedisi";
        }
        // Create a new CMCTimeline entry if there are changes
        CMCTimeline::create([
            'action' => $action,
            'po_requests_id' => $poRequest->id,
            'last_man' => Auth::id(),
            'edited_field' => $poRequest,
        ]);

        // Save the updated data
        if ($poRequest->save()) {
            return back()->with(["success" => "Data updated successfully"]);
        } else {
            return back()->with(["error" => "Update process failed"]);
        }
    }

}

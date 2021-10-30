<?php

namespace App\Http\Controllers;

use App\Helper\RazkyFeb;
use App\Models\MappingRequestSellPhoto;
use App\Models\News;
use App\Models\Price;
use App\Models\RequestSell;
use App\Models\RSHistory;
use App\Models\RsScale;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestSellController extends Controller
{

    /**
     * Show the form for managing existing resource.
     */
    public function viewManage(Request $request)
    {
        $currentStatus = "";
        $datas = RequestSell::orderBy('id', 'desc');
        if ($request->status != null) {
            $currentStatus = RequestSell::defineStatus($request->status);
            $datas = $datas->where('status', '=', $request->status);
        }
        $datas = $datas->get();
        return view('requestsell.manage')->with(compact('datas', 'currentStatus'));
    }


    function deleteAJAX($id, Request $request)
    {
//        return $request->all();
        $object = RequestSell::find($id);
        $object->status = 0;
        $object->reason = $request->reason;
        if ($object->save()){
            return "1";
        }else{
            return "0";
        }
    }
    /**
     * Show the edit form for editing armada
     *
     */
    public function viewDetail(Request $request, $id)
    {
        $userId = $request->user_id;
        $mobile_user = "";
        $mobile_user_role = "";
        if ($userId != null) {
            $mobile_user = User::find($userId);
            $mobile_user_role = $mobile_user->role;
        }

        $data = RequestSell::findOrFail($id);
        $user_data = User::findOrFail($data->user_id);
        $driver_data = User::find($data->driver_id);
        $staff_data = User::find($data->staff_id);
        $history_data = RSHistory::where('id_rs', '=', $id)->orderBy('id', 'desc')->get();
        $truck_data = Truck::find($data->truck_id);
        $trucks = Truck::all();
        $staffs = User::where('role', '=', 2)->get();

        $rsScaleData = RsScale::where('rs_id', '=', $id)->orderBy('id', 'DESC')->get();
        $total_weight = 0.0;
        foreach ($rsScaleData as $item) {
            $total_weight += $item['result'];
        }

        $price = DB::table('price')->latest('created_at')->first();

        $retVal = compact('data', 'user_data', 'trucks', 'mobile_user', 'mobile_user_role',
            'staff_data', 'driver_data', 'truck_data', 'price', 'staffs', 'history_data', 'total_weight');
        if (RazkyFeb::isAPI())
            return $retVal;

        if (str_contains(url()->current(), 'mobile_raz/')) {
            return view('requestsell.mobile_edit')->with($retVal);
        }
        return view('requestsell.edit')->with($retVal);
    }

    public function storeSignature($id, Request $request)
    {
        $object = RequestSell::findOrFail($request->id);
        $type = $request->type;

        if ($type == null)
            return RazkyFeb::error(400, "Lengkapi Type Terlebih Dahulu");


        if ($request->hasFile('photo')) {

            $file_path = public_path() . $object->photo;
            RazkyFeb::removeFile($file_path);

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = "signature_" . $object->id . '_' . $type . '.' . $extension;

            $savePath = "/web_files/request_sell/$id/";
            $savePathDB = "$savePath$fileName";
            $path = public_path() . "$savePath";
            $upload = $file->move($path, $fileName);

            // type 1 = user
            // type 2 = driver
            // type 3 = staff

            if ($type == "1") {
                $object->photo_sign_owner = $savePathDB;
            }
            if ($type == "2") {
                $object->photo_sign_driver = $savePathDB;
            }
            if ($type == "3") {
                $object->photo_sign_staff = $savePathDB;
            }

            if ($object->save()) {
                if (str_contains(url()->current(), 'api/'))
                    return RazkyFeb::success(200, "Berhasil Menyimpan Tanda Tangan");

                return back()->with(["success" => "Gagal Mengganti Tanda Tangan"]);
            } else {
                return RazkyFeb::error(400, "Gagal Menyimpan Tanda Tangan");
            }

        } else {
            return RazkyFeb::error(400, "Lengkapi Foto Terlebih Dahulu");
        }

    }


    public function changeDriver(Request $request)
    {
        $data = RequestSell::findOrFail($request->id);
        $staff_data = User::find($request->staff_id);
        if ($staff_data == null) {
            return back()->with(["error" => "Gagal Mengganti Staff, Data Tidak Ditemukan"]);
        }
        $data->driver_id = $request->staff_id;

        if ($data->save()) {

            $notifTitle = "Transaksi $data->rs_code";
            $notifMessage = "Status Transaksi $data->rs_code telah $data->status_desc";
            $historyMessage = "Pengemudi Truck Untuk Transaksi Ini Telah Dialihkan ke <strong>$data->driver_name</strong>";
            $this->insertHistory($data, $historyMessage);

            RazkyFeb::insertNotification(
                $data->user_id,
                $notifTitle,
                $notifMessage,
                $historyMessage,
                2
            );

            return back()->with(["success" => "Berhasil Mengganti Staff"]);
        } else {
            return back()->with(["error" => "Gagal Mengganti Staff"]);
        }
    }

    public function changeStatus(Request $request)
    {
        $data = RequestSell::findOrFail($request->id);
        $data->status = $request->status;
        if ($data->save()) {
            $notifTitle = "Transaksi $data->rs_code";
            $notifMessage = "Status Transaksi $data->rs_code telah $data->status_desc";
            $historyMessage = "Status Pesanan Anda Telah Berubah Menjadi <strong>$data->status_desc</strong>";
            $this->insertHistory($data, $historyMessage);

            RazkyFeb::insertNotification(
                $data->user_id,
                $notifTitle,
                $notifMessage,
                $historyMessage,
                2
            );

            if (str_contains(url()->current(), 'api/'))
                return RazkyFeb::responseSuccessWithData(
                    200,
                    1,
                    1,
                    "Berhasil Mengganti Status",
                    "Change Status Success",
                    ""
                );

            return back()->with(["success" => "Berhasil Mengganti Status"]);

        } else {
            if (str_contains(url()->current(), 'api/'))
                return RazkyFeb::responseErrorWithData(
                    400,
                    0,
                    0,
                    "Gagal Mengganti Status",
                    "Change Status Error",
                    ""
                );

            return back()->with(["error" => "Gagal Mengganti Status"]);
        }
    }

    public
    function changeMajor(Request $request)
    {
        $data = RequestSell::findOrFail($request->id);
        $staff_data = User::find($request->staff_id);
        $driver_data = User::find($request->driver_id);
        $truck_data = Truck::find($request->truck_id);

        $data->staff_id = $request->staff_id;
        $data->driver_id = $request->driver_id;
        $data->status = $request->status;
        $data->truck_id = $request->truck_id;

        $reqStaff = $request->staff_id;
        $reqDriver = $request->driver_id;
        $reqStatus = $request->status;
        $reqTruck = $request->truck;

        if ($data->save()) {

            $notifTitle = "Transaksi " . $data->rs_code;
            $historyMessage = "Status Transaksi Anda Telah Mengalami Perubahan";
            // $historyMessage =
            //     "Status Pesanan Anda Telah Berubah Menjadi <strong>$data->status_desc</strong>," .
            //     "dijemput oleh Staff <strong>$staff_data->name ($staff_data->email)</strong> dan" .
            //     " <strong>$driver_data->name ($driver_data->email) </strong>" .
            //     " dengan truck <strong>$truck_data->name ($truck_data->nopol)</strong>";

            $this->insertHistory(
                $data,
                $historyMessage
            );

            $notifMessage = "Proses Transaksi $data->rs_code Telah Diupdate";
            RazkyFeb::insertNotification(
                $data->user_id,
                $notifTitle,
                $notifMessage,
                $historyMessage,
                2
            );

            return back()->with(["success" => "Berhasil Mengganti Status"]);
        } else {
            return back()->with(["error" => "Gagal Mengganti Status"]);
        }
    }

    public
    function changeStaff(Request $request)
    {
        $data = RequestSell::findOrFail($request->id);
        $staff_data = User::find($request->staff_id);
        if ($staff_data == null) {
            return back()->with(["error" => "Gagal Mengganti Staff, Data Tidak Ditemukan"]);
        }
        $data->staff_id = $request->staff_id;

        if ($data->save()) {

            $notifTitle = "Transaksi " . $data->rs_code;
            $notifMessage = "Proses Transaksi $data->rs_code Telah Diupdate";
            $historyMessage = "Staff Penjemput Untuk Transaksi Ini Telah Dialihkan ke <strong>$data->staff_name</strong>";
            $this->insertHistory($data, $historyMessage);

            RazkyFeb::insertNotification(
                $data->user_id,
                $notifTitle,
                $notifMessage,
                $historyMessage,
                2
            );
            return back()->with(["success" => "Berhasil Mengganti Staff"]);
        } else {
            return back()->with(["error" => "Gagal Mengganti Staff"]);
        }
    }

    public function storeFinal(Request $request)
    {
        $rules = [
            'final_price' => 'required',
            'final_margin' => 'required',
            'price_paid' => 'required',
        ];

        $customMessages = [
            'required' => 'Mohon Isi Kolom :attribute terlebih dahulu'
        ];
        $this->validate($request, $rules, $customMessages);

        $finalPrice = ($request->final_price);
        $finalMargin = ($request->final_margin);
        $pricePaid = ($request->price_paid);

//        $finalPrice = filter_var($request->final_price, FILTER_SANITIZE_NUMBER_INT);
//        $finalMargin = filter_var($request->final_marign, FILTER_SANITIZE_NUMBER_INT);
//        $pricePaid = filter_var($request->price_paid, FILTER_SANITIZE_NUMBER_INT);

        $data = RequestSell::findOrFail($request->id);
        $data->final_price = $finalPrice;
        $data->final_margin = $finalMargin;
        $data->price_paid = $pricePaid;
        $data->finished_at = time();
        $data->status = 1;

        if ($data->save()) {

            $notifTitle = "Transaksi " . $data->rs_code;
            $notifMessage = "Proses Transaksi $data->rs_code Telah Selesai";
            $historyMessage = "Transaksi $data->rs_code Telah Selesai, Invoice Dapat Dilihat pada menu invoice";
            $this->insertHistory($data, $historyMessage);

            RazkyFeb::insertNotification(
                $data->user_id,
                $notifTitle,
                $notifMessage,
                $historyMessage,
                2
            );
            if (RazkyFeb::isAPI())
                return RazkyFeb::success(200, "Berhasil Menyelesaikan Transaksi");

            return back()->with(["success" => "Berhasil Menyelesaikan Transaksi"]);
        } else {
            if (RazkyFeb::isAPI())
                return RazkyFeb::error(400, "Gagal Menyelesaikan Transaksi");

            return back()->with(["error" => "Gagal Menyelesaikan Transaksi"]);
        }
    }

    public
    function changeTruck(Request $request)
    {
        $data = RequestSell::findOrFail($request->id);
        $truck_data = Truck::find($request->truck_id);
        if ($truck_data == null) {
            return back()->with(["error" => "Truck Tidak Ditemukan"]);
        }
        $data->truck_id = $request->truck_id;
        if ($data->save()) {

            $notifTitle = "Transaksi " . $data->rs_code;
            $notifMessage = "Proses Transaksi $data->rs_code Telah Diupdate";
            $historyMessage =
                "Truck Untuk Menjemput Transaksi Ini Telah Diperbarui menjadi
            <strong>$truck_data->name dengan nopol $truck_data->nopol</strong>";

            $this->insertHistory($data, $historyMessage);

            RazkyFeb::insertNotification(
                $data->user_id,
                $notifTitle,
                $notifMessage,
                $historyMessage,
                2
            );


            return back()->with(["success" => "Berhasil Mengganti Truck"]);
        } else {
            return back()->with(["error" => "Gagal Mengganti Truck"]);
        }
    }

    /**
     * store the request sell
     *
     */
    public
    function store(Request $request)
    {
        $rules = [
            'long' => 'required',
            'lat' => 'required',
            'est_weight' => 'required|numeric',
            'address' => 'required',
            'contact' => 'required',
            'status' => 'required',
            'upload_file' => 'required',
            'upload_file.*' => 'mimes:jpeg,png,jpg,gif,svg,png|max:12048',
        ];

        $customMessages = [
            'required' => 'Mohon Isi Kolom :attribute terlebih dahulu'
        ];
        $this->validate($request, $rules, $customMessages);

        $latestPriceObject = Price::latest()->first();

        $image = $request->file('upload_file');

        //ARRAY FOR SAVING IMAGE
        $dataFile = array();

        foreach ($image as $files) {
            $destinationPath = 'web_files/request_sell/';
            $file_name = $request->user . "_" . uniqid() . $files->getClientOriginalName();
            if ($files->move($destinationPath, $file_name))
                $dataFile[] = $destinationPath . $file_name;
        }


        $data = new RequestSell();
        $data->user_id = Auth::id();
        $data->driver_id = null;
        $data->staff_id = null;
        $data->est_weight = $request->est_weight;
        $data->est_margin = strval($latestPriceObject->margin);
        $data->est_price = strval($latestPriceObject->price);
        $data->address = $request->address;
        $data->lat = $request->lat;
        $data->long = $request->long;
        $data->contact = $request->contact;
        $data->status = $request->status;

        if ($data->save()) {

            $counter = 1;
            foreach ($dataFile as $itemPhoto) {

                //Save Image into MappingRequestSellPhoto;
                $mapping = new MappingRequestSellPhoto();
                $mapping->request_sell_id = $data->id;
                $mapping->request_sell_id = $data->id;
                $mapping->path = $itemPhoto;
                $mapping->save();

                //Save First PHOTO AS SAMPUL
                if ($counter == 1) {
                    $objectRS = $data;
                    $data->photo = $itemPhoto;
                    $data->save();
                }
            }


            if (str_contains(url()->current(), 'api/')) {
                return RazkyFeb::responseSuccessWithData(
                    200,
                    1,
                    1,
                    "Request Jual Berhasil",
                    "Sell Request Success",
                    array("SellRequest" => $data)
                );
            } else {
                return redirect("$request->redirectTo")->with(['success' => "Request Jual Berhasil"]);
            }

        } else {
            if (str_contains(url()->current(), 'api/')) {
                return response()->json([
                    'http_response' => 400,
                    'status' => 0,
                    'message_id' => 'Request Jual Gagal',
                    'message' => 'Sell Request has Failed',
                ], 400);
            } else {
                return redirect("$request->redirectTo")->with(['error' => "Request Jual Berhasil"]);
            }
        }
    }

    public
    function getByUser($id, Request $request)
    {
        //either staff or user;
        $isFromUser = true;
        $user = User::find($id);

        $role="";
        if ($user != null)
            $role = $user->role;

        if ($role==1){
            $role="all";
        }

        $status = $request->status;
        $perPage = $request->per_page;

        if ($request->per_page == null) {
            $perPage = 10;
        }

        // if request doesnt containt ?paginate=true
        // then show all data directly
        if ($request->is_paginate == null || $request->is_paginate == "false") {
            // Check if id is all
            if ($id == "all" || $role=="all") {
                $datas = RequestSell::orderBy('id', 'desc')->get();
            } else {
                if ($user->role == 2)
                    $datas = RequestSell::
                    where(function ($query) use ($id) {
                        $query->where('staff_id', '=', $id)
                            ->orWhere('driver_id', '=', $id);
                    })
                        ->orderBy('id', 'desc')->get();
                else
                    $datas = RequestSell::where('user_id', '=', $id)->orderBy('id', 'desc')->get();
            }
        } else {
            // Check if id is all
            // which mean, API will response all
            if ($id == "all" || $role=="all") {
                // Check if status filter is not null
                if ($status != null) {
                    $datas =
                        RequestSell::
                        where('status', '=', $status)
                            ->orderBy('id', 'desc')
                            ->simplePaginate($perPage);
                } else {
                    //will executed if filter is null, which means, same as "all" filter
                    $datas = RequestSell::orderBy('id', 'desc')->simplePaginate($perPage);
                }
            } else {
                if ($status != null) {
                    if ($user->role == 2)
                        RequestSell::
                        where('status', '=', $status)
                            ->where(function ($query) use ($id) {
                                $query->where('staff_id', '=', $id)
                                    ->orWhere('driver_id', '=', $id);
                            })
                            ->orderBy('id', 'desc')
                            ->simplePaginate($perPage);
                    else
                        $datas =
                            RequestSell::
                            where('status', '=', $status)
                                ->where('user_id', '=', $id)
                                ->where('status', '=', $status)
                                ->orderBy('id', 'desc')
                                ->simplePaginate($perPage);


                } else {
                    // if status is not null
                    if ($user->role == 2)
                        $datas = RequestSell::
                        where(function ($query) use ($id) {
                            $query->where('staff_id', '=', $id)
                                ->orWhere('driver_id', '=', $id);
                        })
                            ->orderBy('id', 'desc')->simplePaginate($perPage);
                    if ($user->role == 3)
                        $datas = RequestSell::where('user_id', '=', $id)
                            ->orderBy('id', 'desc')->simplePaginate($perPage);
                }

            }

        }

        return $datas;
    }

    public
    function insertHistory(RequestSell $requestSell, $desc)
    {
        $history = new \App\Models\RSHistory();
        $history->id_rs = $requestSell->id;
        $history->id_staff = $requestSell->staff_id;
        $history->id_driver = $requestSell->driver_id;
        $history->id_truck = $requestSell->truck_id;
        $history->desc = $desc;
        $history->status = $requestSell->status;
        $history->save();
    }


}

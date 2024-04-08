<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArmadaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewCreate()
    {
        return view('armada.create');
    }

    /**
     * Show the form for managing existing resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewManage()
    {
        $datas = Truck::where('deleted_by','=',null)->get();
        return view('armada.manage')->with(compact('datas'));
    }

    /**
     * Show the edit form for editing armada
     *
     * @return \Illuminate\Http\Response
     */
    public function viewUpdate($id)
    {
        $data = Truck::findOrFail($id);
        return view('armada.edit')->with(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Truck();
        $data->name = $request->armada_name;
        $data->nopol = $request->nopol;
        $data->fuel_type = $request->fuel_type;
        $data->description = $request->description;
        $data->created_by = Auth::id();
        if ($request->hasFile('photo')) {

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            $savePath = "/web_files/truck/";
            $savePathDB = "$savePath$fileName";
            $path = public_path() . "$savePath";
            $file->move($path, $fileName);

            $photoPath = $savePathDB;
            $data->photo = $photoPath;
        }

        if ($data->save()) {
            return back()->with(["success" => "Data saved successfully"]);
        } else {
            return back()->with(["error" => "Saving process failed"]);
        }
    }

    /**
     * Delete Armada by filling deleted_by_id
     * @param @id of armada
     * return json or view
     */
    public function delete(Request $request,$id){
        $armada = Truck::findOrFail($id);
        $armada->deleted_by = Auth::id();

        if ($armada->save()) {
            if ($request->is('api/*'))
                return MyHelper::responseSuccessWithData(
                    200, 1, 200,
                    "Berhasil Mengupdate Data",
                    "Success",
                    Auth::user(),
                );
            return back()->with(["success"=>"Berhasil Mengupdate Data"]);
        }else{
            if ($request->is('api/*'))
                return MyHelper::responseErrorWithData(
                    200, 3, 400,
                    "Berhasil Mengupdate Data",
                    "Success",
                    ""
                );
            return back()->with(["errors"=>"Gagal Mengupdate Data"]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data = Truck::findOrFail($id);
        $data->name = $request->armada_name;
        $data->nopol = $request->nopol;
        $data->fuel_type = $request->fuel_type;
        $data->description = $request->description;
        $data->created_by = Auth::id();
        if ($request->hasFile('photo')) {

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            $savePath = "/web_files/truck/";
            $savePathDB = "$savePath$fileName";
            $path = public_path() . "$savePath";
            $file->move($path, $fileName);

            $photoPath = $savePathDB;
            $data->photo = $photoPath;
        }

        if ($data->save()) {
            return back()->with(["success" => "Data saved successfully"]);
        } else {
            return back()->with(["error" => "Saving process failed"]);
        }
    }

    public function get(){
        $datas = Truck::all();
        return $datas;
    }
}

<?php

namespace App\Http\Controllers;

use App\Helper\MyHelper;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewCreate()
    {
        return view('news.create');
    }

    /**
     * Show the form for managing existing resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewManage()
    {
        $datas = News::all();
        return view('news.manage')->with(compact('datas'));
    }

    /**
     * Show the edit form for editing armada
     *
     * @return \Illuminate\Http\Response
     */
    public function viewUpdate($id)
    {
        $data = News::findOrFail($id);
        return view('news.edit')->with(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $data = new News();
        $data->title = $request->title;
        $data->author = $request->author;
        $data->type = $request->type;
        $data->content = $request->news_content;
        if ($request->hasFile('photo')) {

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            $savePath = "/web_files/news/";
            $savePathDB = "$savePath$fileName";
            $path = public_path() . "$savePath";
            $file->move($path, $fileName);

            $photoPath = $savePathDB;
            $data->photo = $photoPath;
        }

        return $this->SaveData($data, $request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function update(Request $request, $id)
    {
//        dd($request->all());
        $data = News::findOrFail($id);
        $data->title = $request->title;
        $data->author = $request->author;
        $data->content = $request->news_content;
        $data->type = $request->type;
        if ($request->hasFile('photo')) {

            $file_path = public_path() . $data->photo;
            MyHelper::removeFile($file_path);

            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            $savePath = "/web_files/news/";
            $savePathDB = "$savePath$fileName";
            $path = public_path() . "$savePath";
            $file->move($path, $fileName);

            $photoPath = $savePathDB;
            $data->photo = $photoPath;
        }

        return $this->SaveData($data, $request);
    }

    /**
     * Delete Armada by filling deleted_by_id
     * @param @id of armada
     * return json or view
     */
    public function delete(Request $request, $id)
    {
        $data = News::findOrFail($id);

        if ($data->delete()) {
            if ($request->is('api/*'))
                return MyHelper::responseSuccessWithData(
                    200, 1, 200,
                    "Berhasil Menghapus Data",
                    "Success",
                    Auth::user(),
                );
            return back()->with(["success" => "Berhasil Menghapus Data"]);
        } else {
            if ($request->is('api/*'))
                return MyHelper::responseErrorWithData(
                    400, 3, 400,
                    "Berhasil Mengupdate Data",
                    "Success",
                    ""
                );
            return back()->with(["errors" => "Gagal Menghapus Data"]);
        }

    }


    public function get(Request $request)
    {

        $datas = News::all();
        if ($request->type != "") {
            $datas = News::where('type', '=', $request->type)->get();
        }
        return $datas;
    }

    /**
     * @param News $data
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function SaveData(News $data, Request $request)
    {
        if ($data->save()) {
            if ($request->is('api/*'))
                return MyHelper::responseSuccessWithData(
                    200, 1, 200,
                    "Berhasil Menyimpan Data",
                    "Success",
                    $data,
                );

            return back()->with(["success" => "Berhasil Mengupdate Data"]);
        } else {
            if ($request->is('api/*'))
                return MyHelper::responseErrorWithData(
                    400, 3, 400,
                    "Berhasil Mengupdate Data",
                    "Success",
                    ""
                );
            return back()->with(["errors" => "Gagal Mengupdate Data"]);
        }
    }
}

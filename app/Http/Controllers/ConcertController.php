<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use function App\Helper\applyDefaultFSW;

class ConcertController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Concert::class, 'concert');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Concert::query();
        $query = applyDefaultFSW($request, $query);

        $q = $request->get('search');   // 검색
        // Fulltext * 부분 제외 -  " " 로 묶으면 단어 합치기
        if ($q) {
            $query->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", $q);
        }

        return $query->paginate($request->get('per_page') ?: 40);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'title' => 'required',
            'poster' => 'required',
            'desc' => 'required|min:3',
            'artist' => 'required',
            'price' => 'required',
            'openDate' => 'required',
            'closeDate' => 'required',
            'playTime' => 'required',
            'reEndDate' => 'required',
        ]);

        $request->merge([
            // category_id는 임의로 설정함.
            'category_id' => 1
        ]);

        $request->merge([
            'user_id' => Auth::id()
        ]);

        if ($request->file('poster')) {
            $path = $request->file('poster')->store('posters', 's3'); // s3에 image 저장.
            $path = Storage::disk('s3')->url($path);
        }
        $input = array_merge($request->all(), ['poster' => $path]);
        $concert = Concert::create($input);

        return response()->json($concert, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Concert $concert)
    {
        return response()->json($concert);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concert $concert)
    {
        // Gate::authorize('update', $concert);

        $concert->update();
        // 수정해야함.
        return response()->json($concert);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Concert $concert)
    {
        $concert->delete();

        return response()->json($concert);
    }
}

<?php

namespace App\Http\Controllers;

use App\Bloger;
use App\Http\Requests\CreateBlogerRequest;
use App\Http\Requests\UpdateBlogerRequest;
use App\Http\Resources\BlogersResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BlogersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogers = QueryBuilder::for(Bloger::class)->allowedSorts([
            'login',
            'password',
            'status',
            'created_at',
            'updated_at',
        ])->allowedFilters([
            'login',
            'password',
            'status',
            AllowedFilter::scope('scopeStatus'),
            AllowedFilter::exact('login'),
        ])->jsonPaginate();

        return BlogersResource::collection($blogers);
//        $bloger = Bloger::find(1);
//        dd($bloger->news);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBlogerRequest $request)
    {
        $bloger = Bloger::create([
            'login' => $request->input('data.attributes.login'),
            'password' => $request->input('data.attributes.password'),
            'status' => $request->input('data.attributes.status'),
        ]);
        return (new BlogersResource($bloger))
            ->response()
            ->header('Location', route('blogers.show', ['bloger' =>
                $bloger]));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Bloger $bloger
     * @return \Illuminate\Http\Response
     */
    public function show(Bloger $bloger)
    {
//        return $bloger;
        return new BlogersResource($bloger);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Bloger $bloger
     * @return \Illuminate\Http\Response
     */
    public function edit(Bloger $bloger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Bloger $bloger
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogerRequest $request, Bloger $bloger)
    {
        $bloger->update($request->input('data.attributes'));
        return new BlogersResource($bloger);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Bloger $bloger
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bloger $bloger)
    {
        $bloger->delete();
        return response(null, 204);
    }
}

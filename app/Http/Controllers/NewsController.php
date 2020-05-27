<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsResource;
use App\News;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = QueryBuilder::for(News::class)->allowedSorts([
            'title',
            'news',
            'list1',
            'created_at',
            'updated_at',
        ])->allowedFilters([
            'title',
            'news',
            'list1',
        ])->jsonPaginate();

        return NewsResource::collection($news);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNewsRequest $request)
    {
        $news = News::create([
            'title' => $request->input('data.attributes.title'),
            'news' => $request->input('data.attributes.news'),
            'image' => $request->input('data.attributes.image'),
            'list1' => $request->input('data.attributes.list1'),
        ]);
        return (new NewsResource($news))
            ->response()
            ->header('Location', route('news.show', ['news' =>
                $news]));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(News $news)
    {
//        return new NewsResource($news);
        $query = QueryBuilder::for(News::where('id', $news->id))
            ->allowedIncludes('blogers')
            ->firstOrFail();
        return new NewsResource($query);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $news->update($request->input('data.attributes'));
        return new NewsResource($news);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();
        return response(null, 204);

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogersCollection;
use App\News;
use Illuminate\Http\Request;

class NewsBlogersRelatedController extends Controller
{
    public function index(News $news)
    {
        return new BlogersCollection($news->blogers);
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\NewsService;

class NewsController extends Controller
{
    public function __construct(private NewsService $news)
    {
    }

    public function index()
    {
        return view('news.index');
    }

    public function current()
    {
        return response()->json($this->news->grouped());
    }
}

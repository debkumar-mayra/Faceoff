<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\UserVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    function index() {
            $filters = request()->all('user','title','status');
            $videos = UserVideo::filter(request()->only('title','user','status'))
            ->ordering(request()->only('fieldName','shortBy'))
            ->orderBy('id','desc')
            ->paginate(request()->perPage ?? $this->per_page)
            ->withQueryString()->through(fn ($video) => [
                'id' => $video->id,
                'user'=>$video->user->full_name,
                'title' => $video->title,
                'video' => $video->video ? URL::route('image', ['path' => $video->video, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                'status' => $video->status,
            ]);

        return Inertia::render('Admin/Video/VideoList',compact('videos','filters'));
    }
}

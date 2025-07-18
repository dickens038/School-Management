<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Event;
use App\Models\GalleryImage;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $role = auth()->user()->role->name ?? '';
            if ($role === 'headmaster') {
                return redirect('/dashboard/headmaster');
            } elseif (in_array($role, ['it', 'it_department', 'it-staff', 'it_department_user'])) {
                return redirect('/dashboard/it');
            } else {
                return redirect('/dashboard');
            }
        }
        $news = News::orderByDesc('published_at')->take(3)->get();
        $events = Event::orderBy('event_date')->where('event_date', '>=', now())->take(3)->get();
        $galleryImages = GalleryImage::orderByDesc('created_at')->take(8)->get();
        return view('home', compact('news', 'events', 'galleryImages'));
    }
} 
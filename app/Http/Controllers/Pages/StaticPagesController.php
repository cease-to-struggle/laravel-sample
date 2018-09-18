<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Status;
use Auth;

class StaticPagesController extends Controller
{
	//TODO:return the home page
	public function home()
	{
		$feed_items = [];

		if(Auth::check()){
			
			$feed_items = Auth::user()->feed()
									  ->paginate(30);
		}

		return view('static-pages.home',compact('feed_items'));
	}

	//TODO:return the help page
	public function help()
	{
		return view('static-pages.help');
	}

	//TODO:return the discription page
	public function about()
	{
		return view('static-pages.about');
	}
}

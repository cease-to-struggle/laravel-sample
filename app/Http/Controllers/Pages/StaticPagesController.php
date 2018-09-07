<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticPagesController extends Controller
{
	//TODO:return the home page
	public function home()
	{
		return view('static-pages.home');
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

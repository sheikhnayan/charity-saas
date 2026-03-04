<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Auction;
use Auth;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        // Find auction using LIKE query for flexible slug matching
        $data = Auction::where(function($query) use ($slug) {
            $query->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(title, " ", "-"), "_", "-"), ".", "-"), "?", ""), "!", "")) LIKE ?', ['%' . strtolower($slug) . '%'])
                  ->orWhereRaw('LOWER(title) LIKE ?', ['%' . str_replace('-', ' ', strtolower($slug)) . '%']);
        })->firstOrFail();
        
        return view('product', compact('data'));
    }

    /**
     * Display the all resource.
     */
    public function all()
    {
        $url = url()->current();
        if( $url == 'fundably.org' || $url == 'https://fundably.org' || $url == 'http://fundably.org' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $data = Auction::where('website_id', $check->id)->get();

        return view('auction', compact('data', 'check'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

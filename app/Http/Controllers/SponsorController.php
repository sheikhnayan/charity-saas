<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sponsor;
use App\Models\Website;
use App\Models\Transaction;

class SponsorController extends Controller
{
    public function index()
    {
        $data = Sponsor::all();
        return view('admin.sponsor.index', compact('data'));
    }

    public function create()
    {
        $data = Website::all();
        return view('admin.sponsor.create', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $add = new Sponsor;
        $add->name = $request->name;
        $add->link = $request->link;
        $add->status = $request->status;
        $add->hide_until = $request->hide_until;
        $add->hide_after = $request->hide_after;
        $add->price = $request->price;
        $add->website_id = $request->website_id;

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets'), $filename);
            $add->image = 'uploads/tickets/' . $filename;
        }
        $add->user_id = $website->user_id;
        $add->save();

        $tran = new Transaction;
        $tran->amount = $request->price;
        $tran->type = 'sponsor';
        $tran->website_id = $request->website_id;
        $tran->transaction_id = null;
        $tran->name = $request->name;
        $tran->status = $request->status;
        $tran->reference_id = $add->id; // Assuming reference_id is not provided in the request
        $tran->save();

        return redirect()->route('admin.sponsor.index')->with('success', 'Sponsor created successfully.');
    }

    public function edit($id)
    {
        $data = Sponsor::findOrFail($id);
        return view('admin.sponsor.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $add = Sponsor::findOrFail($id);
        $add->name = $request->name;
        $add->link = $request->link;
        $add->status = $request->status;
        $add->hide_until = $request->hide_until;
        $add->hide_after = $request->hide_after;
        $add->price = $request->price;

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets'), $filename);
            $add->image = 'uploads/tickets/' . $filename;
        }
        $add->update();

        $tran = Transaction::where('reference_id', $id)->first();
        $tran->amount = $request->price;
        $tran->name = $request->name;
        $tran->status = $request->status;
        $tran->update();


        return redirect()->route('admin.sponsor.index')->with('success', 'Sponsor updated successfully.');
    }

    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);
        $sponsor->delete();
        return redirect()->route('admin.sponsor.index')->with('success', 'Sponsor deleted successfully.');
    }
}

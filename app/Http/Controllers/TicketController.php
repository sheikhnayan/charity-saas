<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketImage;
use App\Models\TicketFeature;
use App\Models\TicketCategory;
use App\Models\PropertyFinancial;
use App\Models\Website;

class TicketController extends Controller
{
    public function websites()
    {
        $data = Website::all();
        return view('admin.ticket.websites', compact('data'));
    }

    public function index($websiteId = null)
    {
        if ($websiteId) {
            $website = Website::findOrFail($websiteId);
            $data = Ticket::with(['website', 'category'])
                ->where('website_id', $websiteId)
                ->get();
            return view('admin.ticket.index', compact('data', 'website'));
        }
        
        // Fallback to all tickets if no website specified
        $data = Ticket::with(['website', 'category'])->get();
        $website = null;
        return view('admin.ticket.index', compact('data', 'website'));
    }

    public function create()
    {
        $data = Website::all();
        $categories = TicketCategory::with('website')->active()->ordered()->get();
        return view('admin.ticket.create', compact('data', 'categories'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validationRules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:ticket,product,property'
        ];
        
        // Add conditional validation for property type
        if ($request->type === 'property') {
            $validationRules['category_id'] = 'required|exists:ticket_categories,id';
            $validationRules['price_per_share'] = 'required|numeric|min:0';
            $validationRules['total_shares'] = 'required|integer|min:1';
        }
        
        $request->validate($validationRules);

        $add = new Ticket;
        $add->name = $request->name;
        
        // Generate unique slug
        $slug = \Illuminate\Support\Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Ticket::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $add->slug = $slug;
        
        $add->description = $request->description;
        $add->status = $request->status;
        $add->hide_until = $request->hide_until;
        $add->hide_after = $request->hide_after;
        $add->type = $request->type;
        $add->website_id = $request->website_id;
        $add->category_id = $request->category_id;
        $add->features_heading = $request->features_heading;
        $add->page_bg_color = $request->page_bg_color ?? '#ffffff';
        
        // Handle property type
        if ($request->type === 'property') {
            $add->price_per_share = $request->price_per_share;
            $add->price_per_share_label = $request->price_per_share_label;
            $add->total_shares = $request->total_shares;
            $add->available_shares = $request->total_shares; // Initially all shares are available
            $add->price = $request->price_per_share * $request->total_shares; // Total value
            $add->quantity = $request->total_shares; // Use shares as quantity
        } else {
            // Regular ticket or product
            $add->price = $request->price;
            $add->quantity = $request->quantity;
            $add->size = $request->size;
        }

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {
            $images = $request->file('image');

            foreach ($images as $key => $value) {
                # code...
                if($key == 0){
                    $file = $value;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    $add->image = 'uploads/tickets/' . $filename;

                    $add->user_id = $website->user_id;
                    $add->save();

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }else{
                    $file = $value;
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    // $add->image = 'uploads/tickets/' . $filename;

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }

            }

        }

        // Handle document uploads for property type
        if ($request->type === 'property' && $request->hasFile('documents')) {
            $documents = [];
            foreach ($request->file('documents') as $document) {
                // Get file info BEFORE moving the file
                $originalName = $document->getClientOriginalName();
                $fileSize = $document->getSize();
                $fileExtension = $document->getClientOriginalExtension();
                
                $filename = time() . '_' . uniqid() . '.' . $fileExtension;
                $document->move(public_path('uploads/property-documents'), $filename);
                
                $documents[] = [
                    'name' => $originalName,
                    'path' => 'uploads/property-documents/' . $filename,
                    'size' => $fileSize,
                    'type' => $fileExtension,
                ];
            }
            $add->documents = $documents;
            $add->save();
        }

        if($request->features){
            foreach($request->features as $feature){
                $newFeature = new TicketFeature;
                $newFeature->ticket_id = $add->id;
                $newFeature->name = $feature['name'];
                $newFeature->value = $feature['value'];
                $newFeature->save();
            }
        }

        // Handle property financials
        if($request->type === 'property' && $request->financials){
            $add->market = $request->market;
            $add->financials()->create($request->financials);
        }

        

        return redirect()->route('admin.ticket.index', $add->website_id)->with('success', 'Ticket created successfully.');
    }

    public function edit($id)
    {
        $data = Ticket::findOrFail($id);
        $websites = Website::all();
        $categories = TicketCategory::with('website')->active()->ordered()->get();
        return view('admin.ticket.edit', compact('data', 'websites', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validationRules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:ticket,product,property'
        ];
        
        // Add conditional validation for property type
        if ($request->type === 'property') {
            $validationRules['category_id'] = 'required|exists:ticket_categories,id';
            $validationRules['price_per_share'] = 'required|numeric|min:0';
            $validationRules['total_shares'] = 'required|integer|min:1';
        }
        
        $request->validate($validationRules);
        
        $add = Ticket::findOrFail($id);
        $add->name = $request->name;
        
        // Update slug if name changed
        if ($add->isDirty('name') || empty($add->slug)) {
            $slug = \Illuminate\Support\Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while (Ticket::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $add->slug = $slug;
        }
        
        $add->description = $request->description;
        $add->status = $request->status;
        $add->hide_until = $request->hide_until;
        $add->hide_after = $request->hide_after;
        $add->type = $request->type;
        $add->category_id = $request->category_id;
        $add->features_heading = $request->features_heading;
        $add->page_bg_color = $request->page_bg_color ?? '#ffffff';
        // Handle property type
        if ($request->type === 'property') {
            $add->price_per_share = $request->price_per_share;
            $add->price_per_share_label = $request->price_per_share_label;
            $add->market = $request->market;
            
            // Calculate available shares difference if total shares changed
            $oldTotalShares = $add->total_shares ?? 0;
            $newTotalShares = $request->total_shares;
            $soldShares = $oldTotalShares - ($add->available_shares ?? 0);
            
            $add->total_shares = $newTotalShares;
            $add->available_shares = $newTotalShares - $soldShares;
            $add->price = $request->price_per_share * $newTotalShares;
            $add->quantity = $newTotalShares;
        } else {
            // Regular ticket or product
            $add->price = $request->price;
            $add->quantity = $request->quantity;
            $add->size = $request->size;
        }

        $website = Website::find($request->website_id);

        if ($request->hasFile('image')) {

            $delimage = TicketImage::where('ticket_id',$add->id)->delete();

            $images = $request->file('image');

            // dd($images);

            foreach ($images as $key => $value) {
                # code...

                $rand = rand(1000,9999);

                if($key == 0){
                    $file = $value;
                    $filename = $rand . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    $add->image = 'uploads/tickets/' . $filename;
                    

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }else{
                    $file = $value;
                    $filename = $rand . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    // $add->image = 'uploads/tickets/' . $filename;

                    $new = new TicketImage;
                    $new->ticket_id = $add->id;
                    $new->image_path = 'uploads/tickets/' . $filename;
                    $new->save();
                }

            }

        }

        // Handle document uploads for property type
        if ($request->type === 'property' && $request->hasFile('documents')) {
            $documents = $add->documents ?? []; // Keep existing documents
            foreach ($request->file('documents') as $document) {
                // Get file info BEFORE moving the file
                $originalName = $document->getClientOriginalName();
                $fileSize = $document->getSize();
                $fileExtension = $document->getClientOriginalExtension();
                
                $filename = time() . '_' . uniqid() . '.' . $fileExtension;
                $document->move(public_path('uploads/property-documents'), $filename);
                
                $documents[] = [
                    'name' => $originalName,
                    'path' => 'uploads/property-documents/' . $filename,
                    'size' => $fileSize,
                    'type' => $fileExtension,
                ];
            }
            $add->documents = $documents;
        }

        if($request->features){

            $delfeature = TicketFeature::where('ticket_id',$add->id)->delete();

            foreach($request->features as $feature){
                $newFeature = new TicketFeature;
                $newFeature->ticket_id = $add->id;
                $newFeature->name = $feature['name'];
                $newFeature->value = $feature['value'];
                $newFeature->save();
            }
        }

        // Handle property financials update
        if($request->type === 'property' && $request->financials){
            if($add->financials){
                $add->financials()->update($request->financials);
            } else {
                $add->financials()->create($request->financials);
            }
        }

        $add->update();

        return redirect()->route('admin.ticket.index', $add->website_id)->with('success', 'Ticket updated successfully.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $websiteId = $ticket->website_id;
        $ticket->delete();
        return redirect()->route('admin.ticket.index', $websiteId)->with('success', 'Ticket deleted successfully.');
    }
}

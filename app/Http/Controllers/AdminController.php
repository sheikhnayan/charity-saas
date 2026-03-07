<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donation;
use App\Models\Setting;
use App\Models\Header;
use App\Models\Footer;
use App\Models\Website;
use App\Models\DirectDeposit;
use App\Models\MailedCheck;
use App\Models\WireTransfer;
use App\Models\Auction;
use App\Models\Tax;
use App\Models\TaxReceipt;
use App\Models\Transaction;
use Auth;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountApproval;
use Illuminate\Support\Str;
use App\Models\PageComment;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != 'admin') {
            $data = Setting::where('user_id', Auth::user()->id)->first();

            return view('user.setting', compact('data'));
        }else{
            $data = Setting::get();

            return view('admin.setting.list', compact('data'));
        }

    }

    public function change_password()
    {
        return view('admin.change-password');
    }

    public function update_password(Request $request)
    {

        if ($request->new_password != $request->confirm_password) {
            # code...
            return redirect()->back()->with('error', 'New Password and Confirm Password do not match');
        }

        $user = Auth::user()->id;

        $data = User::find($user);

        $data->password = Hash::make(request()->new_password);

        $data->save();

        return redirect()->back()->with('success', 'Password updated successfully');
    }

    public function wire_transfer()
    {
        $user = Auth::user();

        $data = WireTransfer::where('user_id',$user->id)->first();

        return view('user.wire_transfer', compact('data'));
    }

    public function wire_transfer_store(Request $request)
    {
        // dd($request->all());
        $data = WireTransfer::where('user_id', Auth::user()->id)->first();
        // dd(Auth::user()->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip = $request->zip;
        $data->paybale_to = $request->paybale_to;
        $data->send_check_to = $request->send_check_to;
        $data->address_to_send = $request->address_to_send;
        $data->city_to_send = $request->city_to_send;
        $data->beneficiary_address = $request->beneficiary_address;
        $data->beneficiary_zip = $request->beneficiary_zip;
        $data->beneficiary_city = $request->beneficiary_city;
        $data->beneficiary_country = $request->beneficiary_country;
        $data->beneficiary_state = $request->beneficiary_state;
        $data->update();

        return redirect()->back()->with('success', 'Direct Deposit Updated successfully');
    }

    public function mailed_deposit()
    {
        $user = Auth::user();

        $data = MailedCheck::where('user_id',$user->id)->first();

        return view('user.mailed_deposit', compact('data'));
    }

    public function direct_deposit()
    {
        $user = Auth::user();

        $data = DirectDeposit::where('user_id',$user->id)->first();

        return view('user.direct_deposit', compact('data'));
    }

    public function direct_deposit_store(Request $request)
    {
        $data = DirectDeposit::where('user_id', Auth::user()->id)->first();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip = $request->zip;
        $data->name_in_bank = $request->name_in_bank;
        $data->bank_name = $request->bank_name;
        $data->account_type = $request->account_type;
        $data->account_number = $request->account_number;
        $data->routing_number = $request->routing_number;
        $data->update();

        return redirect()->back()->with('success', 'Direct Deposit Updated successfully');
    }

    public function tax()
    {
        $user = Auth::user();

        $data = Tax::where('user_id',$user->id)->first();

        return view('user.tax', compact('data'));
    }

    public function tax_store(Request $request)
    {
        // dd(Auth::user()->id);
        $data = Tax::where('user_id', Auth::user()->id)->first();
        $data->name = $request->name;
        $data->business_name = $request->business_name;
        $data->address = $request->address;
        $data->zip = $request->zip;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->tin = $request->tin;
        $data->type_of_tin = $request->type_of_tin;
        $data->update();

        return redirect()->back()->with('success', 'Tax Information Updated successfully');
    }

    public function tax_receipt_list()
    {
        $data = User::where('role', 'user')->latest()->get();

        return view('admin.tax-receipt.index', compact('data'));
    }

    public function tax_receipt()
    {
        $user = Auth::user();

        $data = TaxReceipt::where('user_id',$user->id)->first();

        return view('user.tax_receipt', compact('data'));
    }

    public function tax_receipt_show($id)
    {
        $data = TaxReceipt::where('user_id',$id)->first();

        return view('admin.tax-receipt.show', compact('data'));
    }

    public function tax_list()
    {

        $data = User::where('role', 'user')->latest()->get();


        return view('admin.tax.index', compact('data'));
    }

    public function tax_show($id)
    {
        $data = Tax::where('user_id',$id)->first();

        return view('admin.tax.show', compact('data'));

    }

    public function tax_receipt_store(Request $request)
    {
        // dd($request->all());
        $data = TaxReceipt::where('user_id', Auth::user()->id)->first();
        $data->organization = $request->organization;
        $data->phone_number = $request->phone_number;
        $data->website = $request->website;
        $data->charitable_id = $request->charitable_id;
        $data->reference = $request->reference;
        $data->number_prefix = $request->number_prefix;
        $data->starting_number = $request->starting_number;
        $data->address = $request->address;
        $data->zip = $request->zip;
        $data->city = $request->city;
        $data->state = $request->state;
        $data->country = $request->country;

            if ($request->hasFile('logo')) {
                $data->logo = $request->file('logo')->store('uploads', 'public');
            }

            if ($request->hasFile('signature')) {
                $data->signature = $request->file('signature')->store('uploads', 'public');
            }

        $data->update();

        return redirect()->back()->with('success', 'Tax Receipt Information Updated successfully');
    }

    public function mailed_deposit_store(Request $request)
    {
        $data = MailedCheck::where('user_id', Auth::user()->id)->first();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->city = $request->city;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->zip = $request->zip;
        $data->paybale_to = $request->paybale_to;
        $data->send_check_to = $request->send_check_to;
        $data->address_to_send = $request->address_to_send;
        $data->city_to_send = $request->city_to_send;
        $data->country_to_send = $request->country_to_send;
        $data->state_to_send = $request->state_to_send;
        $data->zip_to_send = $request->zip_to_send;
        $data->update();

        return redirect()->back()->with('success', 'Mailed Deposit Updated successfully');
    }

    public function setting($id)
    {
        $data = Setting::find($id);

        return view('admin.setting.index', compact('data'));
    }

    public function store_menu(Request $request)
    {
        $data = Header::where('id', $request->id)->first();
        $data->status = $request->status;
        $data->color = $request->color;
        $data->background = $request->background;
        $data->menu = $request->menu;
        $data->floating = $request->floating;
        $data->logo_size = $request->logo_size;
        $data->logo_height = $request->logo_height;
        $data->invest_now_button_text = $request->invest_now_button_text ?? 'Invest Now';
        
        // Handle investor exclusives fields for investment websites
        if ($request->has('show_investor_exclusives')) {
            $data->show_investor_exclusives = $request->show_investor_exclusives;
        }
        if ($request->has('investor_exclusives_text')) {
            $data->investor_exclusives_text = $request->investor_exclusives_text;
        }
        if ($request->has('investor_exclusives_url')) {
            $data->investor_exclusives_url = $request->investor_exclusives_url;
        }
        if ($request->has('topbar_background_color')) {
            $data->topbar_background_color = $request->topbar_background_color;
        }
        if ($request->has('topbar_text_color')) {
            $data->topbar_text_color = $request->topbar_text_color;
        }
        
        // Handle contact top bar fields for investment websites
        if ($request->has('show_contact_topbar')) {
            $data->show_contact_topbar = $request->show_contact_topbar;
        }
        if ($request->has('contact_phone')) {
            $data->contact_phone = $request->contact_phone;
        }
        if ($request->has('contact_email')) {
            $data->contact_email = $request->contact_email;
        }
        if ($request->has('contact_address')) {
            $data->contact_address = $request->contact_address;
        }
        if ($request->has('contact_cta_text')) {
            $data->contact_cta_text = $request->contact_cta_text;
        }
        if ($request->has('contact_cta_url')) {
            $data->contact_cta_url = $request->contact_cta_url;
        }
        if ($request->has('contact_topbar_bg_color')) {
            $data->contact_topbar_bg_color = $request->contact_topbar_bg_color;
        }
        if ($request->has('contact_topbar_text_color')) {
            $data->contact_topbar_text_color = $request->contact_topbar_text_color;
        }
        if ($request->has('contact_cta_bg_color')) {
            $data->contact_cta_bg_color = $request->contact_cta_bg_color;
        }
        if ($request->has('contact_cta_text_color')) {
            $data->contact_cta_text_color = $request->contact_cta_text_color;
        }
        
        // Handle header font family
        if ($request->has('header_font_family')) {
            $data->header_font_family = $request->header_font_family;
        }
        
        // Handle section-specific font families
        if ($request->has('menu_font_family')) {
            $data->menu_font_family = $request->menu_font_family;
        }
        if ($request->has('menu_font_size')) {
            $data->menu_font_size = $request->menu_font_size;
        }
        if ($request->has('submenu_background_color')) {
            $data->submenu_background_color = $request->submenu_background_color;
        }
        if ($request->has('contact_topbar_font_family')) {
            $data->contact_topbar_font_family = $request->contact_topbar_font_family;
        }
        if ($request->has('investor_exclusives_font_family')) {
            $data->investor_exclusives_font_family = $request->investor_exclusives_font_family;
        }
        
        // Handle auth button fields
        if ($request->has('show_auth_button')) {
            $data->show_auth_button = $request->show_auth_button;
        }
        if ($request->has('auth_button_text')) {
            $data->auth_button_text = $request->auth_button_text;
        }
        if ($request->has('auth_button_bg_color')) {
            $data->auth_button_bg_color = $request->auth_button_bg_color;
        }
        if ($request->has('auth_button_text_color')) {
            $data->auth_button_text_color = $request->auth_button_text_color;
        }
        
        $data->update();

        if ($request->has('menu_order')) {
            foreach ($request->menu_order as $order => $pageId) {
                \App\Models\Page::where('id', $pageId)->update(['position' => $order]);
            }
        }

        return redirect()->back()->with('success', 'Menu Updated successfully');
    }

    public function store_footer(Request $request)
    {
        $data = Footer::where('id', $request->id)->first();
        $data->status = $request->status;
        $data->color = $request->color;
        $data->privacy = $request->privacy ?? 1; // Default to 1 if not provided
        $data->background = $request->background;
        $data->background_type = $request->background_type ?? 'color';
        $data->menu = $request->menu;
        $data->message = $request->message;
        $data->copy_right = $request->copy_right;
        $data->social = $request->social;
        $data->facebook = $request->facebook;
        $data->instagram = $request->instagram;
        $data->twitter = $request->twitter;
        $data->linkedin = $request->linkedin;
        $data->youtube = $request->youtube;
        $data->pinterest = $request->pinterest;
        $data->tiktok = $request->tiktok;
        $data->blue_sky = $request->blue_sky;
        
        // Handle page link selections (can be null/empty to hide individual links)
        $data->privacy_page_id = $request->privacy_page_id ?: null;
        $data->refund_page_id = $request->refund_page_id ?: null;
        $data->terms_page_id = $request->terms_page_id ?: null;
        
        // Handle contact section fields
        if ($request->has('contact_heading')) {
            $data->contact_heading = $request->contact_heading;
        }
        if ($request->has('contact_heading_color')) {
            $data->contact_heading_color = $request->contact_heading_color;
        }
        if ($request->has('contact_heading_font')) {
            $data->contact_heading_font = $request->contact_heading_font;
        }
        if ($request->has('contact_heading_size')) {
            $data->contact_heading_size = $request->contact_heading_size;
        }
        if ($request->has('contact_email_color')) {
            $data->contact_email_color = $request->contact_email_color;
        }
        if ($request->has('contact_email_font')) {
            $data->contact_email_font = $request->contact_email_font;
        }
        if ($request->has('contact_email_size')) {
            $data->contact_email_size = $request->contact_email_size;
        }
        
        // Handle investment-specific fields
        if ($request->has('disclaimer_text')) {
            $data->disclaimer_text = $request->disclaimer_text;
        }
        if ($request->has('description_text')) {
            $data->description_text = $request->description_text;
        }
        if ($request->has('investment_disclaimer')) {
            $data->investment_disclaimer = $request->investment_disclaimer;
        }
        
        // Handle image uploads
        if ($request->hasFile('background_image_desktop')) {
            $file = $request->file('background_image_desktop');
            $filename = time() . '_desktop_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data->background_image_desktop = $filename;
        }
        
        if ($request->hasFile('background_image_mobile')) {
            $file = $request->file('background_image_mobile');
            $filename = time() . '_mobile_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data->background_image_mobile = $filename;
        }
        
        $data->update();

        return redirect()->back()->with('success', 'Footer Updated successfully');
    }

    public function store(Request $request){
        // dd($request->all());
        $id = $request->id;

        $add = Setting::find($id);
        $add->title = $request->title;
        $add->description = $request->description;
        $add->location = $request->location;
        $add->payout_method = $request->payout_method;
        $add->title2 = $request->title2;
        $add->sub_title = $request->sub_title;
        $add->date = $request->date;
        $add->api_key = $request->api_key;
        $add->api_secret = $request->api_secret;
        $add->goal = $request->goal;
        $add->site_status = $request->site_status;
        $add->payment_method = $request->payment_method;
        $add->time = $request->time;
        $add->participant_name = $request->participant_name;
        $add->team_name = $request->team_name;

        $add->organization = $request->organization;
        $add->phone = $request->phone;
        $add->charitable_id = $request->charitable_id;
        $add->address = $request->address;
        $add->zip = $request->zip;
        $add->city = $request->city;
        $add->country = $request->country;
        $add->state = $request->state;
        $add->privacy = $request->privacy;
        $add->terms = $request->terms;
        $add->refund = $request->refund;
        
        // Investment-specific fields
        if ($request->filled('investment_title')) {
            $add->investment_title = $request->investment_title;
        }
        if ($request->filled('asset_type')) {
            $add->asset_type = $request->asset_type;
        }
        if ($request->filled('offering_type')) {
            $add->offering_type = $request->offering_type;
        }


        if (isset($request->logo)) {
            $file = $request->file('logo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $add->logo = $fileName;
            # code...
        }

        if (isset($request->banner)) {
            $file = $request->file('banner');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $fileName);
            $add->banner = $fileName;
            # code...
        }

        $add->save();
        return redirect()->back()->with('success', 'Setting Updated successfully');

    }

    public function payment_method()
    {
        $data = User::where('role',  'user')->get();

        return view('admin.payment_method.index', compact('data'));
    }

    public function payment_method_details($id)
    {
        $mailed = MailedCheck::where('user_id', $id)->first();
        $direct = DirectDeposit::where('user_id', $id)->first();
        $wire = WireTransfer::where('user_id', $id)->first();

        return view('admin.payment_method.payment_method', compact('mailed', 'direct', 'wire'));
    }


    public function donation()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            # code...
            $data = Transaction::latest()->get();
            $websites = \App\Models\Website::all();

            return view('admin.donation', compact('data', 'websites'));
        }elseif($user->role == 'user'){
            $websites = Website::where('user_id', $user->id)->select('id')->first();
            // $websites = $websites->pluck('id')->toArray();

            // dd($websites);

            $data = Transaction::where('website_id',$websites->id)->get();

            $teachers = \App\Models\Teacher::where('website_id', $websites->id)
                ->orderBy('name')
                ->get();
            $parents = \App\Models\User::where('website_id', $websites->id)
                ->where('role', 'parents')
                ->orderBy('name')
                ->select('id', 'name', 'last_name', 'email')
                ->get();

            return view('user.donation', compact('data', 'websites', 'teachers', 'parents'));
        }elseif($user->role == 'parents'){
            // Get all student IDs that belong to this parent
            $studentIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            
            // Get donations from those students (donations table has user_id)
            $data = Donation::whereIn('user_id', $studentIds)->where('status',1)->latest()->get();
            
            // Get teachers for the parent's website, sorted alphabetically by name (ignoring prefixes)
            $teachers = \App\Models\Teacher::where('website_id', $user->website_id)->get();
            $teachers = $teachers->sort(function($a, $b) {
                // Strip common prefixes for sorting
                $nameA = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $a->name);
                $nameB = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $b->name);
                return strcasecmp($nameA, $nameB);
            })->values();
            
            // Check if parent has seen tutorial
            $showTutorial = !$user->parent_tutorial_seen;

            return view('user.donation', compact('data', 'showTutorial', 'teachers'));
        }elseif($user->role == 'customer'){ 
            $data = Transaction::where('email',$user->email)->get();

            return view('user.donation', compact('data'));
        }
        else{
            $data = Donation::where('user_id',Auth::user()->id)->with('user')->get();

            return view('user.donation', compact('data'));
        }


    }

    public function payments()
    {
        $user = Auth::user();

        if ($user->role == 'parents') {
            // Get all student IDs that belong to this parent
            $studentIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            
            // Get transaction records from those students
            $data = Transaction::where('email', Auth::user()->email)->latest()->get();
            
            // Get teachers for the parent's website, sorted alphabetically by name (ignoring prefixes)
            $teachers = \App\Models\Teacher::where('website_id', $user->website_id)->get();
            $teachers = $teachers->sort(function($a, $b) {
                // Strip common prefixes for sorting
                $nameA = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $a->name);
                $nameB = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $b->name);
                return strcasecmp($nameA, $nameB);
            })->values();
            
            // Check if parent has seen tutorial
            $showTutorial = !$user->parent_tutorial_seen;

            return view('user.payments', compact('data', 'showTutorial', 'teachers'));
        }

        return redirect('/users');
    }

    public function approve($id)
    {
        $data = Donation::find($id);
        $data->status = 1;
        $data->save();

        return redirect()->back()->with('success', 'Donation Approved successfully');
    }

    public function updateTransactionStatus(Request $request)
    {
        try {
            $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();
            
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Map status values
            $statusMap = [
                'completed' => 1,
                'cancelled' => 2,
                'refunded' => 3,
                'pending' => 0
            ];

            if (!array_key_exists($request->status, $statusMap)) {
                return response()->json(['error' => 'Invalid status'], 400);
            }

            $transaction->status = $statusMap[$request->status];
            $transaction->internal_status = strtoupper($request->status);
            $transaction->save();

            return response()->json(['success' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update status: ' . $e->getMessage()], 500);
        }
    }

    public function student_approve($id)
    {
        $data = User::find($id);
        $previousStatus = $data->status;
        $data->status = 1;
        $data->save();

        // Send approval email only if status was changed from inactive to active
        if ($previousStatus != 1 && $data->status == 1) {
            try {
                $website = Website::find($data->website_id);
                if ($website) {
                    // Apply website-specific email settings
                    \App\Services\WebsiteMailService::applyForWebsite($website);
                    if ($data->email !== 'admin@admin') {
                        Mail::to($data->email)->send(new AccountApproval($data, $website));
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't stop the approval process
                \Log::error('Account approval email failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'User Approved successfully');
    }

    public function mass_approve_students(\Illuminate\Http\Request $request)
    {
        try {
            $userIds = $request->input('user_ids', []);
            
            // Validate input
            if (empty($userIds) || !is_array($userIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users selected for approval.',
                    'approved' => 0
                ], 400);
            }

            $approvedCount = 0;
            $failedCount = 0;
            $errors = [];

            // Process each user
            foreach ($userIds as $userId) {
                try {
                    $user = User::find($userId);
                    
                    if (!$user) {
                        $failedCount++;
                        $errors[] = "User ID {$userId} not found.";
                        continue;
                    }

                    $previousStatus = $user->status;
                    $user->status = 1;
                    $user->save();

                    // Send approval email only if status was changed from inactive to active
                    if ($previousStatus != 1 && $user->status == 1) {
                        try {
                            $website = Website::find($user->website_id);
                            if ($website) {
                                // Apply website-specific email settings
                                \App\Services\WebsiteMailService::applyForWebsite($website);
                                if ($user->email !== 'admin@admin') {
                                    Mail::to($user->email)->send(new \App\Mail\AccountApproval($user, $website));
                                }
                            }
                        } catch (\Exception $e) {
                            // Log error but don't count as failure - approval was successful
                            \Log::warning('Account approval email failed for user ' . $user->id . ': ' . $e->getMessage());
                        }
                    }

                    $approvedCount++;
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Error approving user {$userId}: " . $e->getMessage();
                    \Log::error('Mass approval error for user ' . $userId . ': ' . $e->getMessage());
                }
            }

            $message = "Successfully approved {$approvedCount} user(s)";
            if ($failedCount > 0) {
                $message .= " and {$failedCount} failed.";
            }

            return response()->json([
                'success' => true,
                'approved' => $approvedCount,
                'failed' => $failedCount,
                'message' => $message,
                'errors' => $errors
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Mass approval error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during mass approval: ' . $e->getMessage(),
                'approved' => 0
            ], 500);
        }
    }

    public function student()
    {

        if (Auth::user()->role == 'admin') {
            # code...
            $data = User::where('role', '!=','user')->get();

            $websites = \App\Models\Website::all();

            return view('admin.students', compact('data', 'websites'));
        }elseif(Auth::user()->role == 'group_leader'){
            $data = User::with(['parent', 'teacher'])->where('group_id',Auth::user()->id)->where('id','!=',Auth::user()->id)->get();

            return view('user.students', compact('data'));
        }elseif(Auth::user()->role == 'parents'){
            // For parents, show only their children
            $data = User::with(['parent', 'teacher'])->where('parent_id', Auth::user()->id)->get();
            
            // Get teachers for the parent's website from teachers table, sorted alphabetically by name (ignoring prefixes)
            $teachers = \App\Models\Teacher::where('website_id', Auth::user()->website_id)->get();
            $teachers = $teachers->sort(function($a, $b) {
                // Strip common prefixes for sorting
                $nameA = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $a->name);
                $nameB = preg_replace('/^(Mr\.|Ms\.|Mrs\.|Dr\.)\s*/i', '', $b->name);
                return strcasecmp($nameA, $nameB);
            })->values();
            
            // Check if parent has seen tutorial
            $showTutorial = !Auth::user()->parent_tutorial_seen;

            return view('user.students', compact('data', 'teachers', 'showTutorial'));
        }else{
            $websites = Website::where('user_id', Auth::user()->id)->select('id')->get();
            $websites = $websites->pluck('id')->toArray();

            $data = User::with(['parent', 'teacher'])->where('role', '!=','user')->whereIn('website_id', $websites)->get();

            return view('user.students', compact('data'));
        }

    }

    public function addStudentByParent(Request $request)
    {
        // LOG: Method called
        \Log::info('=== addStudentByParent METHOD CALLED ===', [
            'has_photo' => $request->hasFile('photo'),
            'all_files' => array_keys($request->allFiles()),
            'form_data' => $request->except(['photo', 'password'])
        ]);
        
        // CRITICAL: Check if photo field exists but file upload failed
        if ($request->has('photo') && !$request->hasFile('photo')) {
            \Log::error('Photo upload failed - file too large or upload error', [
                'php_upload_max_filesize' => ini_get('upload_max_filesize'),
                'php_post_max_size' => ini_get('post_max_size'),
                'request_method' => $request->method(),
            ]);
            
            $uploadMaxMB = ini_get('upload_max_filesize');
            $postMaxMB = ini_get('post_max_size');
            
            return back()->withErrors([
                'photo' => "Photo upload failed. Your file may be too large for server limits (max: {$uploadMaxMB}). Safari converts HEIC photos to PNG which can be 3-5x larger. Try compressing your image first or use a different photo."
            ])->withInput()->with('show_add_student_modal', true);
        }
        
        // Log photo upload details for debugging
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            \Log::info('Photo upload attempt', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size_bytes' => $file->getSize(),
                'size_mb' => round($file->getSize() / 1024 / 1024, 2),
                'is_valid' => $file->isValid(),
                'error' => $file->getError()
            ]);
            
            // Custom validation for file extension to catch HEIC and other unsupported formats
            $extension = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($extension, $allowedExtensions)) {
                \Log::warning('Photo upload rejected: unsupported extension', ['extension' => $extension]);
                return back()->withErrors([
                    'photo' => "Unsupported file format (.{$extension}). Please upload JPG, JPEG, PNG, or GIF images only."
                ])->withInput()->with('show_add_student_modal', true);
            }
            
            // Check file size before validation (5MB = 5242880 bytes)
            $maxSize = 5 * 1024 * 1024;
            if ($file->getSize() > $maxSize) {
                $sizeMB = round($file->getSize() / 1024 / 1024, 2);
                \Log::warning('Photo upload rejected: file too large', [
                    'size_mb' => $sizeMB,
                    'max_mb' => 5,
                    'original_name' => $file->getClientOriginalName()
                ]);
                return back()->withErrors([
                    'photo' => "Photo upload failed. Your file may be too large for server limits (max: 2M). Safari converts HEIC photos to PNG which can be 3-5x larger. Try compressing your image first or use a different photo."
                ])->withInput()->with('show_add_student_modal', true);
            }
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'goal' => 'nullable|numeric|min:0',
            'tshirt_size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,pjpeg|max:5120'
        ], [
            'photo.image' => 'The photo must be a valid image file.',
            'photo.mimes' => 'The photo must be in JPG, JPEG, PNG, or GIF format.',
            'photo.max' => 'The photo size exceeds 5MB. Please compress or resize your image.',
        ]);

        $parent = Auth::user();

        // Generate random email and password
        $randomEmail = 'student_' . strtolower($request->first_name) . '_' . uniqid() . '@' . $parent->website->domain;
        $randomPassword = Str::random(12);

        // Create student user
        $student = User::create([
            'name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $randomEmail,
            'password' => Hash::make($randomPassword),
            'role' => 'individual',
            'parent_id' => $parent->id,
            'teacher_id' => $request->teacher_id,
            'website_id' => $parent->website_id,
            'goal' => $request->goal ?? 0,
            'tshirt_size' => $request->tshirt_size,
            'description' => $request->description,
            'status' => 1, // Auto-approve for parent-created students
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $filename);
            $student->photo = 'uploads/' . $filename;
            $student->save();
        }

        return redirect()->back()->with('success', 'Participant added successfully!');
    }

    public function userProfile($id)
    {
        $user = User::with(['website', 'teacher', 'parent', 'children', 'donations'])->findOrFail($id);
        
        // Check if the current user is a parent (not admin)
        if (Auth::user()->role == 'parents') {
            return view('user.user-profile', compact('user'));
        }
        
        return view('admin.user-profile', compact('user'));
    }

    public function editStudentProfile($id)
    {
        $user = User::findOrFail($id);
        
        // Check if the current user is a parent and this student belongs to them
        if (Auth::user()->role == 'parents' && $user->parent_id == Auth::user()->id) {
            // Get current website from domain
            $currentDomain = request()->getHost();
            $currentWebsite = \App\Models\Website::where('domain', $currentDomain)->first();
            
            // Fallback to user's website if not found
            if (!$currentWebsite) {
                $currentWebsite = Auth::user()->website;
            }
            
            return view('user.edit-student-profile', compact('user', 'currentWebsite'));
        }
        
        // If not authorized, redirect back
        return redirect()->back()->with('error', 'Unauthorized access');
    }

    public function updateStudentProfile(Request $request, $id)
    {
        // LOG: Method called
        \Log::info('=== updateStudentProfile METHOD CALLED ===', [
            'student_id' => $id,
            'has_photo' => $request->hasFile('photo'),
            'all_files' => array_keys($request->allFiles()),
            'form_data' => $request->except(['photo', 'password'])
        ]);
        
        $student = User::findOrFail($id);
        
        // Check if the current user is a parent and this student belongs to them
        if (Auth::user()->role != 'parents' || $student->parent_id != Auth::user()->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        // LOG: Before validation
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            \Log::info('Photo upload in updateStudentProfile', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size_bytes' => $file->getSize(),
                'size_mb' => round($file->getSize() / 1024 / 1024, 2),
                'is_valid' => $file->isValid(),
                'error' => $file->getError()
            ]);
        }
        
        // Check if photo field was submitted but PHP rejected it (file too large)
        if ($request->has('photo') && !$request->hasFile('photo')) {
            \Log::warning('Photo upload rejected: file too large in updateStudentProfile', [
                'student_id' => $id,
                'has_photo_field' => true,
                'has_file' => false
            ]);
            return back()->withErrors([
                'photo' => "Photo upload failed. Your file may be too large for server limits (max: 2M). Safari converts HEIC photos to PNG which can be 3-5x larger. Try compressing your image first or use a different photo."
            ])->withInput();
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'goal' => 'nullable|numeric|min:0',
            'tshirt_size' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,pjpeg|max:5120'
        ], [
            'photo.image' => 'The photo must be a valid image file.',
            'photo.mimes' => 'The photo must be in JPG, JPEG, PNG, or GIF format.',
            'photo.max' => 'The photo must not exceed 5MB in size.',
        ]);
        
        // Save the name in both name and fist_name fields
        $student->name = $request->name;
        // $student->fist_name = $request->name;
        $student->last_name = $request->last_name;
        $student->goal = $request->goal ?? 0;
        $student->tshirt_size = $request->tshirt_size;
        $student->description = $request->description;
        
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads'), $filename);
            $student->photo = 'uploads/' . $filename;
        }
        
        $student->save();
        
        return redirect()->back()->with('success', 'Student profile updated successfully!');
    }

    public function deleteUser($id)
    {
        // Only admin can delete users
        // if (Auth::user()->role != 'admin') {
        //     return redirect()->back()->with('error', 'Unauthorized access');
        // }

        $user = User::findOrFail($id);
        
        // Only allow deletion of students (individual), parents
        if (!in_array($user->role, ['student', 'individual', 'parents'])) {
            // dd('sss');
            return redirect()->back()->with('error', 'Only students and parents can be deleted');
        }

        DB::beginTransaction();
        
        try {
            if ($user->role === 'parents') {
                // Delete parent: First delete all their children and related data
                $children = User::where('parent_id', $user->id)->get();
                
                foreach ($children as $child) {
                    // Delete child's related data
                    $this->deleteUserRelatedData($child);
                    
                    // Delete the child user
                    $child->delete();
                }
                
                // Now delete parent's own related data
                $this->deleteUserRelatedData($user);
                
                // Delete parent user
                $user->delete();
                
            } elseif ($user->role === 'student' || $user->role === 'individual') {
                // Delete student: Delete all their related data
                $this->deleteUserRelatedData($user);
                
                // Delete the student user
                $user->delete();
            }
            
            DB::commit();
            
            // Redirect based on the role of the person deleting
            if (Auth::user()->role === 'user') {
                return redirect()->route('users.manage-users.index')->with('success', 'User and all related data deleted successfully');
            } else {
                return redirect()->route('admin.student')->with('success', 'User and all related data deleted successfully');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());
            
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to delete all data related to a user
     */
    private function deleteUserRelatedData($user)
    {
        // Delete donations and related transactions
        $donations = Donation::where('user_id', $user->id)->get();
        
        foreach ($donations as $donation) {
            // Delete transactions related to this donation
            Transaction::where('reference_id', $donation->id)
                ->whereIn('type', ['student', 'general'])
                ->delete();
        }
        
        // Delete all donations for this user
        Donation::where('user_id', $user->id)->delete();
        
        // Delete student messages if any (check if model exists)
        if (class_exists('\App\Models\StudentMessage')) {
            \App\Models\StudentMessage::where('student_id', $user->id)->delete();
        }
        
        // Delete notification preferences (check if model exists)
        if (class_exists('\App\Models\NotificationPreference')) {
            \App\Models\NotificationPreference::where('user_id', $user->id)->delete();
        }
        
        // Delete notification tokens (check if model exists)
        if (class_exists('\App\Models\UserNotificationToken')) {
            \App\Models\UserNotificationToken::where('user_id', $user->id)->delete();
        }
        
        // Delete user sessions (check if model exists)
        // if (class_exists('\App\Models\UserSession')) {
        //     \App\Models\UserSession::where('user_id', $user->id)->delete();
        // }
        
        // Delete AB test assignments (check if model exists)
        // if (class_exists('\App\Models\ABTestAssignment')) {
        //     \App\Models\ABTestAssignment::where('user_id', $user->id)->delete();
        // }
        
        // Delete cohort memberships (check if model exists)
        // if (class_exists('\App\Models\CohortMember')) {
        //     \App\Models\CohortMember::where('user_id', $user->id)->delete();
        // }
    }

    public function menu($id)
    {
        $data = Header::find($id);
        $pages = \App\Models\Page::where('website_id',$data->website_id)->orderBy('position')->get();
        $website = \App\Models\Website::find($data->website_id);
        $customFonts = \App\Models\CustomFont::active()->get();

        return view('admin.menu.menu', compact('data', 'pages', 'website', 'customFonts'));
    }

    public function menu_index()
    {
        $data = Header::get();

        return view('admin.menu.index', compact('data'));
    }

    public function footer($id)
    {
        $data = Footer::where('user_id',$id)->first();
        $website = Website::where('user_id', $id)->first();
        $customFonts = \App\Models\CustomFont::active()->get();
        
        // Get all pages for this website
        $pages = $website ? $website->pages()->where('status', 1)->orderBy('name')->get() : collect();

        // dd($id);

        return view('admin.footer.footer', compact('data', 'website', 'customFonts', 'pages'));
    }

    public function footer_index()
    {
        $data = User::where('role','user')->latest()->get();

        return view('admin.footer.index', compact('data'));
    }

    public function auction_index()
    {
        $data = Website::get();

        return view('admin.auction.index', compact('data'));
    }

    public function auction_edit($id)
    {
        $data = Auction::where('website_id', $id)->get();

        $website = Website::find($id);

        return view('admin.auction.auction', compact('data','website'));
    }

    /**
     * Get all bids for an auction (JSON API)
     */
    public function getAuctionBids($auctionId)
    {
        try {
            $auction = Auction::findOrFail($auctionId);
            
            // Get all bids from transactions table where type='auction' and reference_id is the auction_id
            $bids = \App\Models\Transaction::where('type', 'auction')
                ->where('reference_id', $auctionId)
                ->orderBy('amount', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'auction' => [
                    'id' => $auction->id,
                    'title' => $auction->title,
                    'value' => $auction->value
                ],
                'bids' => $bids->map(function($bid) {
                    return [
                        'id' => $bid->id,
                        'name' => $bid->name,
                        'email' => $bid->email,
                        'amount' => $bid->amount,
                        'created_at' => $bid->created_at,
                        'transaction_id' => $bid->transaction_id,
                        'address' => $bid->address,
                        'city' => $bid->city,
                        'state' => $bid->state,
                        'zip' => $bid->zip,
                        'phone' => $bid->phone,
                        'status' => $bid->status
                    ];
                }),
                'total_bids' => $bids->count(),
                'highest_bid' => $bids->first()?->amount,
                'lowest_bid' => $bids->last()?->amount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching bids: ' . $e->getMessage()
            ], 500);
        }
    }

    public function auction_edit_auction($id)
    {
        $data = Auction::find($id);

        return view('admin.auction.edit', compact('data'));
    }

    public function auction_add($id)
    {
        $website = Website::find($id);

        return view('admin.auction.add', compact('website'));
    }

    public function store_auction(Request $request)
    {
        // dd($request->all());
        $data = new Auction();
        $data->website_id = $request->id;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->dead_line = $request->deadline;
        $data->value = $request->value;
        $data->timezone = $request->timezone;
        $data->status = $request->status;
        $data->page_bg_color = $request->page_bg_color ?? '#ffffff';
        $data->save();

        if (isset($request->images)) {
            foreach ($request->images as $key => $value) {
                # code...
                $file = $value;
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $image = new \App\Models\AuctionImage();
                $image->auction_id = $data->id;
                $image->image = $fileName;
                $image->save();
            }
        }

        return redirect()->route('admin.auction.edit',[$data->website_id])->with('success', 'Auction Created successfully');

    }

    public function update_auction(Request $request, $id)
    {
        // dd($request->all());
        $data = Auction::find($id);
        $data->title = $request->title;
        $data->description = $request->description;
        $data->dead_line = $request->deadline;
        $data->value = $request->value;
        $data->timezone = $request->timezone;
        $data->status = $request->status;
        $data->page_bg_color = $request->page_bg_color ?? '#ffffff';
        $data->update();

        // Remove old images
        if (isset($request->delete_images)) {
            foreach ($request->delete_images as $key => $value) {
                # code...
                $image = \App\Models\AuctionImage::find($value);
                if ($image) {
                    // Delete the image file from storage
                    if (file_exists(public_path('uploads/' . $image->image))) {
                        unlink(public_path('uploads/' . $image->image));
                    }
                    // Delete the image record from database
                    $image->delete();
                }
            }
        }

        // $remove = \App\Models\AuctionImage::where('auction_id', $data->id)->delete();

        if (isset($request->images)) {
            foreach ($request->images as $key => $value) {
                # code...
                $file = $value;
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $image = new \App\Models\AuctionImage();
                $image->auction_id = $data->id;
                $image->image = $fileName;
                $image->save();
            }
        }

        return redirect()->route('admin.auction.edit',[$data->website_id])->with('success', 'Auction Updated successfully');

    }

    public function update_auction_status($id, Request $request)
    {
        $auction = Auction::find($id);
        
        if (!$auction) {
            return response()->json(['error' => 'Auction not found'], 404);
        }
        
        $auction->status = $request->status;
        $auction->save();
        
        return response()->json(['success' => 'Status updated successfully']);
    }

    public function uploadImage(Request $request)
    {
        try {
            // Get PHP upload limits
            $maxUploadSize = min(
                $this->parseSize(ini_get('upload_max_filesize')),
                $this->parseSize(ini_get('post_max_size'))
            );
            $maxUploadMB = round($maxUploadSize / 1024 / 1024, 2);
            
            // Check if file was uploaded
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file was uploaded.'
                ], 400);
            }
            
            // Check for upload errors
            $image = $request->file('image');
            if ($image->getError() !== UPLOAD_ERR_OK) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the server limit of {$maxUploadMB}MB.",
                    UPLOAD_ERR_FORM_SIZE => "The uploaded file is too large.",
                    UPLOAD_ERR_PARTIAL => "The file was only partially uploaded. Please try again.",
                    UPLOAD_ERR_NO_FILE => "No file was uploaded.",
                    UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder on server.",
                    UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
                    UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload.",
                ];
                
                $errorCode = $image->getError();
                $errorMessage = $errorMessages[$errorCode] ?? "Unknown upload error (code: {$errorCode})";
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'maxSize' => $maxUploadMB . 'MB'
                ], 400);
            }
            
            // Validate file
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Store in public/uploads directory
            $image->move(public_path('uploads'), $imageName);
            
            $imageUrl = asset('uploads/' . $imageName);

            return response()->json([
                'success' => true,
                'url' => $imageUrl,
                'message' => 'Image uploaded successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $message = 'Validation failed: ';
            
            if (isset($errors['image'])) {
                $message .= implode(' ', $errors['image']);
            } else {
                $message .= 'Invalid file format or size. Maximum size is 2MB.';
            }
            
            return response()->json([
                'success' => false,
                'message' => $message
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Image upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Parse size string (e.g., "8M", "100K") to bytes
     */
    private function parseSize($size)
    {
        $unit = strtoupper(substr($size, -1));
        $value = (int) $size;
        
        switch ($unit) {
            case 'G':
                $value *= 1024;
            case 'M':
                $value *= 1024;
            case 'K':
                $value *= 1024;
        }
        
        return $value;
    }
    
    /**
     * Get upload configuration limits
     */
    public function getUploadConfig()
    {
        $uploadMaxFilesize = $this->parseSize(ini_get('upload_max_filesize'));
        $postMaxSize = $this->parseSize(ini_get('post_max_size'));
        $maxUploadSize = min($uploadMaxFilesize, $postMaxSize);
        
        return response()->json([
            'success' => true,
            'limits' => [
                'maxFileSize' => $maxUploadSize,
                'maxFileSizeMB' => round($maxUploadSize / 1024 / 1024, 2),
                'uploadMaxFilesize' => ini_get('upload_max_filesize'),
                'postMaxSize' => ini_get('post_max_size'),
                'maxExecutionTime' => ini_get('max_execution_time'),
                'memoryLimit' => ini_get('memory_limit'),
            ]
        ]);
    }

    public function uploadVideo(Request $request)
    {
        // Set runtime upload limits for larger videos
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '52M');
        ini_set('max_execution_time', '600');
        ini_set('memory_limit', '512M');
        
        try {
            // Log for debugging
            \Log::info('Video upload attempt', [
                'has_file' => $request->hasFile('video'),
                'request_size' => $request->header('Content-Length'),
                'files' => array_keys($request->allFiles())
            ]);
            
            $request->validate([
                'video' => 'required|file|mimes:mp4,webm,ogg,avi,mov,wmv|max:51200', // 50MB max
            ]);

            $video = $request->file('video');
            $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            
            // Store in public/uploads directory
            $video->move(public_path('uploads'), $videoName);
            
            $videoUrl = asset('uploads/' . $videoName);

            return response()->json([
                'success' => true,
                'url' => $videoUrl,
                'message' => 'Video uploaded successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Video upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display newsletter management dashboard
     */
    public function newsletter_index()
    {
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            // Admin can see all websites
            $websites = Website::with(['activeNewsletterSubscriptions'])->get();
        } else {
            // Regular user can only see their websites
            $websites = Website::where('user_id', $user->id)
                ->with(['activeNewsletterSubscriptions'])
                ->get();
        }

        return view('admin.newsletter.index', compact('websites'));
    }

    /**
     * Manage subscriptions for a specific website
     */
    public function newsletter_manage($website_id)
    {
        $user = Auth::user();
        
        // Check if user has access to this website
        $website = Website::where('id', $website_id);
        if ($user->role != 'admin') {
            $website = $website->where('user_id', $user->id);
        }
        $website = $website->firstOrFail();

        $subscriptions = \App\Models\NewsletterSubscription::where('website_id', $website_id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        $stats = [
            'total' => \App\Models\NewsletterSubscription::where('website_id', $website_id)->count(),
            'active' => \App\Models\NewsletterSubscription::where('website_id', $website_id)->where('status', 'active')->count(),
            'inactive' => \App\Models\NewsletterSubscription::where('website_id', $website_id)->where('status', 'inactive')->count(),
        ];

        return view('admin.newsletter.manage', compact('website', 'subscriptions', 'stats'));
    }

    /**
     * Send email to newsletter subscribers
     */
    public function newsletter_send_email(Request $request)
    {
        $request->validate([
            'website_id' => 'required|exists:websites,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:all,active'
        ]);

        $user = Auth::user();
        
        // Check if user has access to this website
        $website = Website::where('id', $request->website_id);
        if ($user->role != 'admin') {
            $website = $website->where('user_id', $user->id);
        }
        $website = $website->firstOrFail();

        // Get subscribers based on recipient type
        $query = \App\Models\NewsletterSubscription::where('website_id', $request->website_id);
        if ($request->recipient_type === 'active') {
            $query->where('status', 'active');
        }
        $subscribers = $query->get();

        $emailsSent = 0;
        $failedEmails = [];

        foreach ($subscribers as $subscriber) {
            // Skip admin@admin email
            if ($subscriber->email === 'admin@admin') {
                continue;
            }
            try {
                // Apply per-website email settings
                if ($website) { \App\Services\WebsiteMailService::applyForWebsite($website); }
                \Mail::raw($request->message, function ($message) use ($subscriber, $request, $website) {
                    $message->to($subscriber->email)
                           ->subject($request->subject)
                           ->from(config('mail.from.address', 'noreply@' . $website->domain), $website->name);
                });
                $emailsSent++;
            } catch (\Exception $e) {
                $failedEmails[] = $subscriber->email;
                \Log::error('Failed to send newsletter email', [
                    'email' => $subscriber->email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $message = "Newsletter sent successfully! {$emailsSent} emails sent.";
        if (!empty($failedEmails)) {
            $message .= " Failed to send to: " . implode(', ', array_slice($failedEmails, 0, 5));
            if (count($failedEmails) > 5) {
                $message .= " and " . (count($failedEmails) - 5) . " more.";
            }
        }

        return back()->with('success', $message);
    }

    /**
     * Delete a newsletter subscription
     */
    public function newsletter_delete_subscription($id)
    {
        $user = Auth::user();
        
        $subscription = \App\Models\NewsletterSubscription::with('website')->findOrFail($id);
        
        // Check if user has access to this website
        if ($user->role != 'admin' && $subscription->website->user_id != $user->id) {
            abort(403, 'Unauthorized');
        }

        $subscription->delete();

        return back()->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Export newsletter subscriptions to CSV
     */
    public function newsletter_export($website_id)
    {
        $user = Auth::user();
        
        // Check if user has access to this website
        $website = Website::where('id', $website_id);
        if ($user->role != 'admin') {
            $website = $website->where('user_id', $user->id);
        }
        $website = $website->firstOrFail();

        $subscriptions = \App\Models\NewsletterSubscription::where('website_id', $website_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'newsletter_subscriptions_' . $website->name . '_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($subscriptions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['First Name', 'Last Name', 'Email', 'Phone', 'Country Code', 'Status', 'Subscribed Date']);

            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->first_name ?? '',
                    $subscription->last_name ?? '',
                    $subscription->email,
                    $subscription->phone ?? '',
                    $subscription->country_code ?? '',
                    $subscription->status,
                    $subscription->subscribed_at ? $subscription->subscribed_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Comment Management Methods
    public function comments_index()
    {
        $comments = PageComment::with(['website', 'replies'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.comments.index', compact('comments'));
    }

    public function comments_reply(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:5000',
        ]);

        $parentComment = PageComment::findOrFail($id);
        
        // Create admin reply
        PageComment::create([
            'page_identifier' => $parentComment->page_identifier,
            'component_id' => $parentComment->component_id,
            'website_id' => $parentComment->website_id,
            'author_name' => 'Site Administrator',
            'author_email' => Auth::user()->email,
            'comment' => $request->comment,
            'is_approved' => true,
            'is_anonymous' => false,
            'is_admin_reply' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'parent_id' => $id
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully!');
    }

    public function comments_delete($id)
    {
        $comment = PageComment::findOrFail($id);
        
        // Delete the comment and all its replies
        $comment->replies()->delete();
        $comment->delete();
        
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Download transaction invoice as PDF
     */
    public function downloadTransactionInvoice($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        $website = $transaction->website;
        
        $total_with_fee = $transaction->amount + (($transaction->amount / 100) * ($website->paymentSettings->fee ?? 2.9));
        
        $pdf = Pdf::loadView('emails.invoice-pdf', [
            'transaction' => $transaction,
            'website' => $website,
            'total_with_fee' => $total_with_fee
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        return $pdf->download('invoice-' . $transaction->transaction_id . '.pdf');
    }

    /**
     * Resend invoice email for a transaction
     */
    public function resendTransactionInvoice($transactionId)
    {
        $transaction = Transaction::where('transaction_id', $transactionId)->firstOrFail();
        $website = $transaction->website;

        try {
            if ($website) { \App\Services\WebsiteMailService::applyForWebsite($website); }
            
            // Send to customer's email
            if ($transaction->email !== 'admin@admin') {
                \Mail::to($transaction->email)->send(new \App\Mail\TransactionInvoice($transaction, $website));
            }
            
            // Also send to website owner emails that have transaction preference enabled
            if ($website) {
                $websiteEmails = $website->getTransactionEmails();
                foreach ($websiteEmails as $email) {
                    if ($email !== $transaction->email && $email !== 'admin@admin') {  // Don't send duplicate if customer email is in list, skip admin@admin
                        \Mail::to($email)->send(new \App\Mail\TransactionInvoice($transaction, $website));
                    }
                }
            }
            
            return response()->json(['success' => true, 'message' => 'Invoice email sent successfully!']);
        } catch (\Exception $e) {
            \Log::error('Failed to resend transaction invoice', [
                'transaction_id' => $transaction->transaction_id,
                'email' => $transaction->email,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to send invoice email.']);
        }
    }
    
    /**
     * Mark parent tutorial as seen
     */
    public function markTutorialSeen()
    {
        $user = Auth::user();
        
        if ($user && $user->role == 'parents') {
            $user->parent_tutorial_seen = true;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Tutorial marked as seen'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid user or role'
        ], 400);
    }

}

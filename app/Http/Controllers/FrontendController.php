<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Page;
use App\Models\Donation;
use App\Models\Website;
use App\Models\Header;
use App\Models\Footer;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\Auction;
use App\Models\TicektSell;
use App\Models\TicketSellDetail;
use App\Models\Investment;
use App\Models\CustomFont;
use App\Models\DealmakerConfig;
use App\Services\PaymentFunnelService;
use Illuminate\Support\Facades\Auth;
use Mail;

class FrontendController extends Controller
{
    public function productDetails($slug)
    {
        // First, try to find an auction with LIKE query for flexible matching
        $auction = Auction::where(function($query) use ($slug) {
            $query->whereRaw('LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(title, " ", "-"), "_", "-"), ".", "-"), "?", ""), "!", "")) LIKE ?', ['%' . strtolower($slug) . '%'])
                  ->orWhereRaw('LOWER(title) LIKE ?', ['%' . str_replace('-', ' ', strtolower($slug)) . '%']);
        })->first();
        
        if ($auction) {
            // This is an auction - redirect to auction view
            return app(AuctionController::class)->show($slug);
        }
        
        // Not an auction, try to find a ticket/product
        $ticket = Ticket::where('slug', $slug)->with('website')->firstOrFail();
        
        // Get website header/footer/settings
        $website = $ticket->website;
        $user_id = $website->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = Footer::where('user_id', $user_id)->first();
        
        // Load custom fonts for dynamic font support
        $customFonts = \App\Models\CustomFont::active()->get();
        
        // Menu sections only for investment websites (page builder sections)
        // Fundraiser websites use standard page navigation from layouts.nav
        $menuSections = [];
        
        // Calculate actual available shares from sales for property type
        if ($ticket->type === 'property') {
            // Get total shares sold from ticket_sell_details (only successful sales)
            $totalSold = TicketSellDetail::where('ticket_id', $ticket->id)
                ->whereHas('ticketSell', function($query) {
                    $query->where('status', 1);
                })
                ->sum('quantity');
            
            // Update the ticket object with calculated values
            $ticket->available_shares = $ticket->total_shares - $totalSold;
            
            return view('property-details', compact('ticket', 'setting', 'header', 'footer', 'website', 'customFonts', 'menuSections'));
        }elseif($ticket->type === 'product'){

            $data = Page::where('user_id', $user_id)->where('default', 1)->first();
            $menuSections = $this->extractMenuSections($data);

            return view('product-details', compact('ticket', 'setting', 'header', 'footer', 'website', 'customFonts', 'menuSections'));
        }
        return view('product-details', compact('ticket', 'setting', 'header', 'footer', 'website', 'customFonts', 'menuSections'));
        
    }

    public function index()
    {

        $url = url()->current();
        if( $url == 'http://brandallco.com' || $url == 'brandallco.com' || $url == 'https://brandallco.com' || $url == 'http://127.0.0.1:8000') {
            // return redirect()->route('admin.index', 1);
           return $this->dealmakerDemo();
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        // dd($doamin);
        $check = Website::where('domain', $doamin)->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();
        
        // Get active custom fonts
        $customFonts = \App\Models\CustomFont::active()->get();
        
        // Consolidated template - use page-investment.blade.php for both website types
        // Find homepage using is_homepage field (fallback to default for backward compatibility)
        $data = Page::where('user_id', $user_id)
                    ->where(function($query) {
                        $query->where('is_homepage', true)
                              ->orWhere('default', 1);
                    })
                    ->orderBy('is_homepage', 'desc') // Prioritize is_homepage
                    ->first();
        $menuSections = $this->extractMenuSections($data);
        
        if($setting->site_status == 1){
            return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections', 'customFonts'));
        }else{
            $data = null;
            $menuSections = [];
            return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections', 'customFonts'));
        }
    }

    public function donate()
    {
        $data = User::limit(10)->get();


        return view('donate', compact('data'));
    }

    public function invest(Request $request)
    {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);
        $website = Website::where('domain', $domain)->first();
        
        if (!$website) {
            abort(404);
        }
        
        $user_id = $website->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = Footer::where('user_id', $user_id)->first();
        
        // Get custom fonts for the website
        $customFonts = \App\Models\CustomFont::get();
        
        // Get amount from URL parameter if provided
        $amount = $request->get('amount');
        
        // Clean and decode the amount if provided
        if ($amount) {
            // URL decode the amount
            $amount = urldecode($amount);
            // Remove currency symbols and convert to numeric value
            $amount = preg_replace('/[^0-9.,]/', '', $amount);
            // Remove commas
            $amount = str_replace(',', '', $amount);
            // Convert to float and back to ensure it's a clean number
            $amount = floatval($amount);
        }

        // dd($url);

        if($url == 'https://ladyoriginaltee.com/invest') {
            return view('dummy-login', compact('setting', 'header', 'footer', 'website', 'amount', 'customFonts'));
        }
        
        return view('invest', compact('setting', 'header', 'footer', 'website', 'amount', 'customFonts'));
    }

    public function saveInvestmentInfo(Request $request)
    {
        try {

            $url = url()->previous();
            $domain = parse_url($url, PHP_URL_HOST);
            $website = Website::where('domain', $domain)->first();

            if (!$website) {
                return redirect()->back()->with('error', 'Website not found');
            }

            $setting = \App\Models\Setting::where('user_id', $website->user_id)->first();
            $sharePrice = $setting && $setting->share_price ? $setting->share_price : 1.00;
            $shareQuantity = floor($request->investment_amount / $sharePrice);

            // Store all form data collected from the page
            $allFormData = $request->input('form_data', []);
            
            // Collect investor data
            $investorData = array_merge([
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'zip' => $request->input('postalCode') ?: $request->input('zip'),
                'country' => $request->input('country'),
                'accredited_investor' => $request->input('accredited_investor'),
                'incorporation_state' => $request->input('incorporation_state'),
                'ein' => $request->input('ein'),
                'trust_type' => $request->input('trust_type'),
                'custodian' => $request->input('custodian'),
                'ira_type' => $request->input('ira_type'),
                'phone' => $request->input('phone'),
                'individual_name' => $request->input('individual_name'),
                'date_of_birth' => $request->input('date_of_birth'),
                'ssn' => $request->input('ssn') ?: $request->input('taxpayer_id'),
                'primary_ssn' => $request->input('primary_ssn') ?: $request->input('joint.joint_holder_taxpayer_id'),
                'secondary_ssn' => $request->input('secondary_ssn'),
                'taxpayer_id' => $request->input('taxpayer_id'),
                'joint_holder_taxpayer_id' => $request->input('joint.joint_holder_taxpayer_id'),
                'primary_dob' => $request->input('primary_dob'),
                'secondary_dob' => $request->input('secondary_dob'),
                'primary_name' => $request->input('primary_name'),
                'secondary_name' => $request->input('secondary_name'),
                'corporation_name' => $request->input('corporation_name'),
                'trust_name' => $request->input('trust_name'),
                'ira_holder_name' => $request->input('ira_holder_name'),
            ], $allFormData ?: []);
            
            $investment = Investment::create([
                'website_id' => $website->id,
                'investor_name' => $request->investor_name,
                'investor_email' => $request->investor_email,
                'investor_phone' => $request->investor_phone,
                'investment_amount' => $request->investment_amount,
                'investor_type' => $request->investor_type,
                'share_quantity' => $shareQuantity,
                'deal_id' => $setting && $setting->deal_id ? $setting->deal_id : null,
                'status' => 'pending',
                'investor_data' => $investorData
            ]);

            // If user is authenticated, save their investor profile
            if (\Auth::check()) {
                $user = \Auth::user();
                
                // Only save investor profile if user is a customer
                if ($user->role === 'customer' && $request->investor_type) {
                    \App\Models\UserInvestorProfile::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'investor_type' => $request->investor_type,
                            'investor_data' => $investorData,
                        ]
                    );
                    
                    \Log::info('Investor profile saved for user: ' . $user->id);
                }
            }

            // Debug: Log what was actually saved
            \Log::info('=== INVESTMENT CREATED ===');
            \Log::info('Investment ID:', [$investment->id]);
            \Log::info('Investor Type Saved:', [$investment->investor_type]);
            \Log::info('SSN Fields Captured:', [
                'ssn' => $request->input('ssn'),
                'taxpayer_id' => $request->input('taxpayer_id'), 
                'primary_ssn' => $request->input('primary_ssn'),
                'secondary_ssn' => $request->input('secondary_ssn'),
                'joint_holder_taxpayer_id' => $request->input('joint.joint_holder_taxpayer_id')
            ]);
            \Log::info('All Request Data:', $request->all());
            \Log::info('Investor Data Saved:', $investment->investor_data);
            \Log::info('Full Investment Record:', $investment->toArray());
            \Log::info('=== END INVESTMENT DEBUG ===');

            // Track payment initiation for investment
            try {
                $funnelService = new PaymentFunnelService();
                $funnelService->trackPaymentInitiated(
                    'investment',
                    $request->investment_amount,
                    'authorize_net',
                    [
                        'investor_type' => $investment->investor_type,
                        'share_quantity' => $shareQuantity,
                        'investor_data' => $investment->investor_data
                    ],
                    \Auth::id() // Pass user_id if authenticated
                );
            } catch (\Exception $e) {
                \Log::error('Payment funnel tracking error in investment: ' . $e->getMessage());
            }

            // Redirect to payment page like donation and auction
            return redirect('/authorize/payment/investment/'.$investment->id)->with('success', 'Investment Pending Payment');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function processInvestment(Request $request)
    {
        $request->validate([
            'investment_id' => 'required|exists:investments,id',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string'
        ]);

        $investment = \App\Models\Investment::find($request->investment_id);
        
        $investment->update([
            'status' => 'processing',
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id
        ]);

        // Here you would integrate with your payment processor
        // For now, we'll just mark it as completed
        $investment->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Investment processed successfully',
            'redirect_url' => route('invest.thank-you', ['id' => $investment->id])
        ]);
    }

    public function investmentThankYou(Request $request)
    {
        $investment_id = $request->query('id');
        $investment = \App\Models\Investment::find($investment_id);
        
        if (!$investment) {
            return redirect()->route('invest');
        }

        return view('investment.thank-you', compact('investment'));
    }

    public function investmentStatus($id)
    {
        $investment = \App\Models\Investment::find($id);
        
        if (!$investment) {
            return response()->json(['error' => 'Investment not found'], 404);
        }

        return response()->json([
            'status' => $investment->status,
            'kyc_status' => $investment->kyc_status,
            'aml_status' => $investment->aml_status,
            'amount' => $investment->formatted_amount,
            'shares' => $investment->share_quantity
        ]);
    }

    public function investmentContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        // Here you would send an email to the admin
        // For now, we'll just return success
        
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully'
        ]);
    }

    public function investmentTerms()
    {
        return view('investment.terms');
    }

    public function investmentPrivacy()
    {
        return view('investment.privacy');
    }

    public function volunteer()
    {
        return view('volunteer');
    }
    public function photo()
    {
        return view('photo');
    }
    public function about()
    {
        return view('about');
    }
    public function contact()
    {
        return view('contact');
    }

    public function contact_form(Request $request)
    {
        // Get website ID from request or get it from the domain
        $website_id = $request->input('website_id');
        $website = null;
        
        // If no website_id in request, try to find by domain
        if (!$website_id) {
            $host = request()->getHost();
            $website = \App\Models\Website::where('domain', $host)->first();
        } else {
            $website = \App\Models\Website::find($website_id);
        }

        // Get emails that should receive contact form emails (respects individual preferences)
        $emails = [];
        if ($website) {
            $emails = $website->getContactFormEmails();
        }
        
        // Add admin email if no emails configured
        if (empty($emails) && $website && $website->user && $website->user->email) {
            $emails[] = $website->user->email;
        }
        
        // Fallback if no emails configured
        if (empty($emails)) {
            $emails = ['sheikhnayan1997@gmail.com'];
        }

        $subject = 'New Contact Form Submission';
        $html = '
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> ' . e($request->name) . '</p>
            <p><strong>Email:</strong> ' . e($request->email) . '</p>
            <p><strong>Message:</strong><br>' . nl2br(e($request->message)) . '</p>
        ';

        foreach ($emails as $to) {
            // Skip admin@admin email
            if ($to === 'admin@admin') {
                continue;
            }
            // Apply per-website email settings if available (based on domain)
            try {
                $host = request()->getHost();
                $w = \App\Models\Website::where('domain', $host)->first();
                if ($w) { \App\Services\WebsiteMailService::applyForWebsite($w); }
            } catch (\Exception $e) { /* ignore */ }
            \Mail::send([], [], function ($message) use ($to, $subject, $html) {
                $message->to($to)
                    ->subject($subject)
                    ->html($html);
                // Optional reply-to from config
                if (config('mail.reply_to.address')) {
                    $message->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
                }
            });
        }

        return back()->with('success', 'Your message has been sent!');
    }

    public function custom_form(Request $request)
    {
        // Get website ID from request or get it from the domain
        $website_id = $request->input('website_id');
        $website = null;
        
        // If no website_id in request, try to find by domain
        if (!$website_id) {
            $host = request()->getHost();
            $website = \App\Models\Website::where('domain', $host)->first();
        } else {
            $website = \App\Models\Website::find($website_id);
        }

        // Collect all emails: contact_emails from website settings + admin email
        $emails = [];
        
        if ($website && $website->contact_emails) {
            $contactEmails = is_array($website->contact_emails) ? $website->contact_emails : json_decode($website->contact_emails, true);
            $emails = array_merge($emails, (array)$contactEmails);
        }
        
        // Add admin email (website owner's email)
        if ($website && $website->user && $website->user->email) {
            if (!in_array($website->user->email, $emails)) {
                $emails[] = $website->user->email;
            }
        }
        
        // Fallback if no emails configured
        if (empty($emails)) {
            $emails = ['sheikhnayan1997@gmail.com'];
        }

        $subject = 'New Contact Form Submission';
        $html = '
            <h2>Contact Form Submission</h2>
            <p><strong>Name:</strong> ' . e($request->name) . '</p>
            <p><strong>Email:</strong> ' . e($request->email) . '</p>
            <p><strong>Message:</strong><br>' . nl2br(e($request->message)) . '</p>
        ';

        foreach ($emails as $to) {
            // Skip admin@admin email
            if ($to === 'admin@admin') {
                continue;
            }
            // Apply per-website email settings (explicit website context)
            if ($website) { \App\Services\WebsiteMailService::applyForWebsite($website); }
            \Mail::send([], [], function ($message) use ($to, $subject, $html) {
                $message->to($to)
                    ->subject($subject)
                    ->html($html); // <-- use html() instead of setBody()
                if (config('mail.reply_to.address')) {
                    $message->replyTo(config('mail.reply_to.address'), config('mail.reply_to.name'));
                }
            });
        }

        return back()->with('success', 'Your message has been sent!');
    }

    public function leaderBoard()
    {
        return view('leader-board');
    }

    public function student($slug)
    {

        $array = explode('-', $slug);

        $id = $array[0];

        $url = url()->current();
        if( $url == 'fundably.org' || $url == 'https://fundably.org' || $url == 'http://fundably.org' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        // dd($id);

        $data = User::where('id', $id)->first();

        $donations = Donation::where('user_id', $id)->where('status',1)->get();
        
        // Get messages for this student
        $messages = \App\Models\StudentMessage::where('student_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student', compact('data', 'donations', 'messages', 'check'));
    }

    public function donation(Request $request)
    {
        $request->validate([
            'donation_amount' => 'required|numeric',
            // 'user_id' => 'required|exists:users,id',
        ]);

        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        $add = new Donation;
        $add->user_id = $request->user_id;
        $add->amount = $request->donation_amount;
        $add->comment = $request->leave_comment;
        $add->first_name = $request->first_name;
        $add->last_name = $request->last_name;
        $add->email = $request->email;
        $add->website_id = $check->id;
        $add->type = 'student';

        if(isset($request->anonymous_donation)) {
            $add->hide = 1;
        } else {
            $add->hide = 0;

        }

        $add->status = 0;
        $add->save();

        // Track payment initiation for student donation
        try {
            $funnelService = new PaymentFunnelService();
            $funnelService->trackPaymentInitiated(
                'student',
                $request->donation_amount,
                'authorize_net',
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'comment' => $request->leave_comment,
                    'anonymous' => isset($request->anonymous_donation)
                ],
                $request->user_id
            );
        } catch (\Exception $e) {
            \Log::error('Payment funnel tracking error in student donation: ' . $e->getMessage());
        }

        return redirect('/authorize/payment/donation/'.$add->id)->with('success', 'Donation Pending');
    }

    public function sendStudentMessage(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'sender_name' => 'required|string|max:100',
            'sender_email' => 'required|email|max:150',
            'message' => 'required|string|max:5000',
        ]);

        \App\Models\StudentMessage::create([
            'student_id' => $request->student_id,
            'sender_name' => $request->sender_name,
            'sender_email' => $request->sender_email,
            'message' => $request->message,
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Your message has been sent successfully!');
    }

    public function tickets(Request $request){
        // CONDITIONAL_AUTH_CHECK: Check if any tickets are property/investment type
        $hasPropertyTickets = false;
        foreach ($request->ticket as $key => $value) {
            if($value['quantity'] > 0){
                $ticket = Ticket::find($value['id']);
                if($ticket->type === 'property') {
                    $hasPropertyTickets = true;
                    break;
                }
            }
        }
        
        // Only require authentication for investment/property type purchases
        if ($hasPropertyTickets) {
            if (!Auth::check()) {
                return redirect()->back()->withErrors(['auth' => 'You must be logged in to purchase investment properties.'])->withInput();
            }
            
            if (!Auth::user()->email_verified_at) {
                return redirect()->back()->withErrors(['auth' => 'Please verify your email address before purchasing investment properties.'])->withInput();
            }
        }
        
        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        $amount = 0;
        $quantity = 0;
        $validationErrors = [];

        // First pass: validate available shares for property types
        foreach ($request->ticket as $key => $value) {
            if($value['quantity'] > 0){
                $ticket = Ticket::find($value['id']);
                
                // For property type, check available shares
                if($ticket->type === 'property') {
                    // Calculate current available shares
                    $totalSold = TicketSellDetail::where('ticket_id', $ticket->id)
                        ->whereHas('ticketSell', function($query) {
                            $query->where('status', 1);
                        })
                        ->sum('quantity');
                    $availableShares = $ticket->total_shares - $totalSold;
                    
                    // Validate requested quantity against available shares
                    if((int)$value['quantity'] > $availableShares) {
                        $validationErrors[] = "Property '{$ticket->name}': Only {$availableShares} shares available, but {$value['quantity']} requested.";
                    }
                    
                    if($availableShares <= 0) {
                        $validationErrors[] = "Property '{$ticket->name}' is sold out.";
                    }
                }
            }
        }
        
        // Return validation errors if any
        if(!empty($validationErrors)) {
            return redirect()->back()
                ->withErrors($validationErrors)
                ->withInput()
                ->with('error', 'Share purchase validation failed: ' . implode(' ', $validationErrors));
        }

        // Second pass: calculate amounts
        foreach ($request->ticket as $key => $value) {
            if($value['quantity'] > 0){
                
            $ticket = Ticket::find($value['id']);

            // For property type, use price_per_share instead of price
            if($ticket->type === 'property') {
                $a = (float) $ticket->price_per_share * (int) $value['quantity'];
            } else {
                $a = (int) $ticket->price * (int) $value['quantity'];
            }

            $amount += $a;
            $quantity += (int) $value['quantity'];
            }
        }

        // Create ticket sell record with pending status
        $add = new TicektSell;
        $add->quantity = $quantity;
        $add->amount = $amount;
        $add->status = 0;
        $add->website_id = $check->id;
        $add->save();

        // Create ticket sell details with pending status
        foreach ($request->ticket as $key => $value) {
            $ticket= Ticket::find($value['id']);
            
            if((int) $value['quantity'] > 0){
                
            $sell = new TicketSellDetail;
            $sell->ticket_sell_id = $add->id;
            $sell->ticket_id = $value['id'];
            $sell->quantity = $value['quantity'];
            
            // For property type, use price_per_share instead of price
            if($ticket->type === 'property') {
                $sell->amount = (int) $value['quantity'] * (float) $ticket->price_per_share;
            } else {
                $sell->amount = (int) $value['quantity'] * (int) $ticket->price;
            }
            
            $sell->save();
            }
        }

        // Track payment initiation for ticket purchase
        try {
            $funnelService = new PaymentFunnelService();
            $funnelService->trackPaymentInitiated(
                'ticket',
                $amount,
                'authorize_net',
                [
                    'quantity' => $quantity,
                    'tickets' => array_filter($request->ticket, function($item) {
                        return $item['quantity'] > 0;
                    })
                ],
                null // tickets don't have user_id
            );
        } catch (\Exception $e) {
            \Log::error('Payment funnel tracking error in ticket purchase: ' . $e->getMessage());
        }

        return redirect('/authorize/payment/ticket/'.$add->id)->with('success', 'Purchase Pending');
    }

    public function donation_general(Request $request)
    {
        // dd($request->all());


        $url = url()->current();
        $doamin = parse_url($url, PHP_URL_HOST);
        $check = Website::where('domain', $doamin)->first();

        $add = new Donation;
        $add->user_id = $check->user_id;
        $add->amount = $request->donation_amount;
        $add->website_id = $check->id;
        $add->comment = $request->leave_comment;
        $add->first_name = $request->first_name;
        $add->last_name = $request->last_name;
        $add->email = $request->email;
        $add->type = 'general';

        if(isset($request->anonymous_donation)) {
            $add->hide = 1;
        } else {
            $add->hide = 0;

        }

        $add->status = 0;
        $add->save();

        // Track payment initiation for general donation
        try {
            $funnelService = new PaymentFunnelService();
            $funnelService->trackPaymentInitiated(
                'general',
                $request->donation_amount,
                'authorize_net',
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'comment' => $request->leave_comment,
                    'anonymous' => isset($request->anonymous_donation)
                ],
                null // general donations don't have user_id
            );
        } catch (\Exception $e) {
            \Log::error('Payment funnel tracking error in general donation: ' . $e->getMessage());
        }

        return redirect('/authorize/payment/donation/'.$add->id)->with('success', 'Donation Pending');
    }

    public function page($id)
    {
        $url = url()->current();
        $domain = parse_url($url, PHP_URL_HOST);

        // dd($domain);
        
        // Check if this is a main site URL
        $mainSiteUrls = [
            'brandallco.com',
            'www.brandallco.com',
            // '127.0.0.1'
        ];

        
        if (in_array($domain, $mainSiteUrls)) {
            // dd($domain);
            // Main site page logic - look for pages with is_main_site = true
            $data = Page::mainSite()->where('name', str_replace('-', ' ', $id))->first();
            
            if (!$data) {
                abort(404);
            }
            
            // Get active custom fonts
            $customFonts = \App\Models\CustomFont::active()->get();
            
            // For main site pages, we don't need website-specific settings
            $setting = null;
            $header = null;
            $footer = null;
            $check = null;
            $menuSections = $this->extractMenuSections($data);
            
            return view('page-investment', compact('setting', 'header', 'data', 'check', 'footer', 'menuSections', 'customFonts'));
        }
        
        // Existing website-specific page logic
        $check = Website::where('domain', $domain)->first();
        
        if (!$check) {
            abort(404);
        }
        
        // Check if this is an investment website
        if ($check->type == 'investment') {
            // For investment websites, redirect to homepage since everything is on one page
            return redirect('/');
        }
        
        // Get active custom fonts
        $customFonts = \App\Models\CustomFont::active()->get();
        
        // For fundraiser websites, continue with existing multi-page behavior
        $data = Page::where('website_id', $check->id)->where('name', str_replace('-', ' ', $id))->first();
        $user_id = $check->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        $header = Header::where('user_id', $user_id)->first();
        $footer = footer::where('user_id', $user_id)->first();

        // Use consolidated template
        $menuSections = $this->extractMenuSections($data);
        return view('page-investment', compact('setting', 'header', 'data', 'check','footer', 'menuSections', 'customFonts'));
    }

    public function dealmakerDemo()
    {
        // Use the separate DealMaker configuration system
        $setting = DealmakerConfig::getInstance();
        
        return view('dealmaker-demo', compact('setting'))->with('config', $setting);
    }

    /**
     * Extract menu sections from page state for investment websites
     */
    private function extractMenuSections($page)
    {
        if (!$page || !$page->state) {
            return [];
        }

        $state = is_string($page->state) ? json_decode($page->state, true) : $page->state;
        $menuSections = [];

        if (!is_array($state)) {
            return [];
        }

        // Check if state has components array (new format) or is direct array (old format)
        $components = isset($state['components']) ? $state['components'] : $state;

        foreach ($components as $component) {
            // Check if this is an inner-section with menu enabled
            if (isset($component['type']) && $component['type'] === 'inner-section') {
                $innerSectionData = $component['innerSectionData'] ?? [];
                
                if (isset($innerSectionData['addToMenu']) && $innerSectionData['addToMenu'] && 
                    isset($innerSectionData['menuTitle']) && !empty($innerSectionData['menuTitle'])) {
                    
                    $menuSections[] = [
                        'title' => $innerSectionData['menuTitle'],
                        'sectionId' => $innerSectionData['sectionId'] ?? strtolower(str_replace(' ', '-', $innerSectionData['menuTitle']))
                    ];
                }
            }
        }

        return $menuSections;
    }

    public function newsletterSubscribe(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:20',
                'country_code' => 'nullable|string|max:5',
                'website_id' => 'required|exists:websites,id'
            ]);

            // Check if email already exists for this website
            $existingSubscription = \App\Models\NewsletterSubscription::where('email', $request->email)
                ->where('website_id', $request->website_id)
                ->first();

            if ($existingSubscription) {
                if ($existingSubscription->status === 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are already subscribed to our newsletter!'
                    ]);
                } else {
                    // Reactivate subscription and update with new data
                    $existingSubscription->update([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone,
                        'country_code' => $request->country_code ?? '+1',
                        'status' => 'active',
                        'subscribed_at' => now()
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Welcome back! Your subscription has been reactivated.'
                    ]);
                }
            }

            // Create new subscription
            \App\Models\NewsletterSubscription::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'country_code' => $request->country_code ?? '+1',
                'website_id' => $request->website_id,
                'status' => 'active',
                'subscribed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing to our newsletter!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500);
        }
    }
}

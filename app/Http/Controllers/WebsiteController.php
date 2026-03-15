<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use App\Models\Website;
use App\Models\User;
use App\Models\Setting;
use App\Models\Header;
use App\Models\Footer;
use App\Models\DirectDeposit;
use App\Models\MailedCheck;
use App\Models\WireTransfer;
use App\Models\Tax;
use App\Services\HeaderFooterBuilderService;
use Auth;
use Hash;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Website::get();

        return view('admin.website.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.website.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, HeaderFooterBuilderService $builderService)
    {
        // Validate the request
        $validation = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'type' => 'required|in:fundraiser,investment',
        ];

        // Add investment fields for all website types
        $validation['share_price'] = 'nullable|numeric|min:0.01';
        $validation['min_investment'] = 'nullable|numeric|min:1';
        $validation['investment_tiers'] = 'nullable|string';
        $validation['investment_title'] = 'nullable|string';
        $validation['asset_type'] = 'nullable|string|max:255';
        $validation['offering_type'] = 'nullable|string|max:255';
        $validation['asset_type_label'] = 'nullable|string|max:255';
        $validation['offering_type_label'] = 'nullable|string|max:255';
        $validation['additional_information'] = 'nullable|string';
        $validation['invest_page_title'] = 'nullable|string|max:255';
        $validation['invest_amount_title'] = 'nullable|string|max:255';
        $validation['share_price_label'] = 'nullable|string|max:255';
        $validation['minimum_investment_label'] = 'nullable|string|max:255';

        $request->validate($validation);

        try {
            //code...
            $add = new Website;
            $add->user_id = Auth::user()->id;
            $add->name = $request->name;
            $add->domain = $request->domain;
            $add->type = $request->type;
            $add->status = 1;
            $add->custom_sticky_button_text = $request->custom_sticky_button_text;
            
            // Add investment fields for all website types
            $add->share_price = $request->share_price ?? null;
            $add->investment_title = $request->investment_title ?? null;
            $add->min_investment = $request->min_investment ?? null;
            $add->investment_tiers = $request->investment_tiers ?? null;
            $add->asset_type = $request->asset_type ?? 'Common Stock';
            $add->offering_type = $request->offering_type ?? 'Equity';
            $add->asset_type_label = $request->asset_type_label ?? 'ASSET TYPE';
            $add->offering_type_label = $request->offering_type_label ?? 'OFFERING TYPE';
            $add->additional_information = $request->additional_information ?? null;
            $add->invest_page_title = $request->invest_page_title ?? 'Complete Your Investment';
            $add->invest_amount_title = $request->invest_amount_title ?? 'Select Investment Amount';
            $add->share_price_label = $request->share_price_label ?? 'SHARE PRICE';
            $add->minimum_investment_label = $request->minimum_investment_label ?? 'MINIMUM INVESTMENT';
            
            // Process contact emails with individual preferences
            $contactEmails = $request->contact_emails ?? [];
            $processedEmails = [];
            if (is_array($contactEmails)) {
                foreach ($contactEmails as $emailItem) {
                    if (is_array($emailItem) && !empty($emailItem['email'])) {
                        $processedEmails[] = [
                            'email' => $emailItem['email'],
                            'receive_contact_form' => isset($emailItem['receive_contact_form']) && $emailItem['receive_contact_form'] == '1' ? true : false,
                            'receive_transaction_emails' => isset($emailItem['receive_transaction_emails']) && $emailItem['receive_transaction_emails'] == '1' ? true : false
                        ];
                    }
                }
            }
            $add->contact_emails = $processedEmails;
            
            $add->save();

            $user = new User;
            $user->name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';
            $user->website_id = $add->id;
            $user->goal = 50000;
            $user->description = 'test description';
            $user->photo = 'images/1744993540.png';
            $user->save();

            $update = Website::find($add->id);
            $update->user_id = $user->id;
            $update->update();

            $setting = new Setting;
            $setting->user_id = $user->id;
            $setting->logo = 'images/1744993540.png';
            $setting->banner = '1745001151.png';
            $setting->title = $add->name;
            $setting->title2 = $add->name;
            $setting->sub_title = $add->name;
            $setting->date = now();
            $setting->location = 'Canada';
            $setting->time = '13:08';
            $setting->description = 'Test Description';
            $setting->save();

            $header = new Header;
            $header->user_id = $user->id;
            $header->website_id = $add->id;
            $header->background = '#ffffff';
            $header->color = '#000';
            $header->status = 1;
            $header->floating = 1;
            $header->menu = 1;
            $header->save();

            $footer = new Footer;
            $footer->user_id = $user->id;
            $footer->website_id = $add->id;
            $footer->status = 0;
            $footer->color = '#000';
            $footer->privacy = 1;
            $footer->background = '#fff';
            $footer->menu = 1;
            $footer->message = $request->name;
            $footer->copy_right = $request->name;
            $footer->social = 0;
            $footer->facebook = '#';
            $footer->instagram = '#';
            $footer->twitter = '#';
            $footer->linkedin = '#';
            $footer->youtube = '#';
            $footer->pinterest = '#';
            $footer->tiktok = '#';
            $footer->blue_sky = '#';
            $footer->save();

            $builderService->seedDefaultsForWebsite($add);

            $n = new DirectDeposit;
            $n->user_id = $user->id;
            $n->save();

            $m = new MailedCheck;
            $m->user_id = $user->id;
            $m->save();

            $w = new WireTransfer;
            $w->user_id = $user->id;
            $w->save();

            $t = new Tax;
            $t->user_id = $user->id;
            $t->website_id = $add->id;
            $t->save();

            $email = 'nman0171@gmail.com';
            $domain = $request->domain;

            $vhostPath = "/etc/apache2/sites-available/{$domain}.conf";
            $docRoot = "/var/www/charity/public";

                $vhostConfig = "<VirtualHost *:80>
                ServerName {$domain}
                DocumentRoot {$docRoot}

                <Directory {$docRoot}>
                    AllowOverride All
                    Require all granted
                </Directory>

                Alias /.well-known/acme-challenge/ /var/www/letsencrypt/
            </VirtualHost>";

                file_put_contents($vhostPath, $vhostConfig);
                exec("sudo a2ensite {$domain}.conf && sudo systemctl reload apache2");

            Process::run("sudo certbot --apache -d {$domain} --non-interactive --agree-tos -m {$email} --redirect");

            return redirect()->route('admin.website.index')->with('success', 'Website created successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Website::findOrFail($id);
        $this->authorizeWebsiteAccess($data);
        return view('admin.website.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $website = Website::findOrFail($id);
        $this->authorizeWebsiteAccess($website);

        $isWebsiteOwner = Auth::check() && Auth::user()->role === 'user';

        // Validate the request
        $validation = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'domain' => $isWebsiteOwner ? 'sometimes|nullable|string|max:255' : 'required|string|max:255',
            'type' => $isWebsiteOwner ? 'sometimes|nullable|in:fundraiser,investment' : 'required|in:fundraiser,investment',
            'status' => 'required|in:0,1',
        ];
        // Only validate password if present
        if ($request->filled('password')) {
            $validation['password'] = 'string|min:6';
        }
        $request->validate($validation);

        $update = $website;
        $update->name = $request->name;

        // Website owners can edit settings but cannot change website routing/type.
        if (!$isWebsiteOwner) {
            $update->domain = $request->domain;
            $update->type = $request->type;
        }

        $update->status = $request->status;
        $update->custom_sticky_button_text = $request->custom_sticky_button_text;
        // Add investment fields for all website types
        $update->share_price = $request->share_price ?? null;
        $update->google_analytics_id = $request->google_analytics_id ?? null;
        $update->investment_title = $request->investment_title ?? null;
        $update->min_investment = $request->min_investment ?? null;
        $update->investment_tiers = $request->investment_tiers ?? null;
        $update->asset_type = $request->asset_type ?? 'Common Stock';
        $update->offering_type = $request->offering_type ?? 'Equity';
        $update->asset_type_label = $request->asset_type_label ?? 'ASSET TYPE';
        $update->offering_type_label = $request->offering_type_label ?? 'OFFERING TYPE';
        $update->additional_information = $request->additional_information ?? null;
        $update->invest_page_title = $request->invest_page_title ?? 'Complete Your Investment';
        $update->invest_amount_title = $request->invest_amount_title ?? 'Select Investment Amount';
        $update->share_price_label = $request->share_price_label ?? 'SHARE PRICE';
        $update->minimum_investment_label = $request->minimum_investment_label ?? 'MINIMUM INVESTMENT';
        
        // Process contact emails with individual preferences
        $contactEmails = $request->contact_emails ?? [];
        $processedEmails = [];
        if (is_array($contactEmails)) {
            foreach ($contactEmails as $emailItem) {
                if (is_array($emailItem) && !empty($emailItem['email'])) {
                    $processedEmails[] = [
                        'email' => $emailItem['email'],
                        'receive_contact_form' => isset($emailItem['receive_contact_form']) && $emailItem['receive_contact_form'] == '1' ? true : false,
                        'receive_transaction_emails' => isset($emailItem['receive_transaction_emails']) && $emailItem['receive_transaction_emails'] == '1' ? true : false
                    ];
                }
            }
        }
        $update->contact_emails = $processedEmails;
        
        $update->sticky_footer_button_bg = $request->sticky_footer_button_bg ?? null;
        $update->sticky_footer_button_text = $request->sticky_footer_button_text ?? null;
        $update->sticky_footer_text_color = $request->sticky_footer_text_color ?? null;
        $update->sticky_footer_bg_color = $request->sticky_footer_bg_color ?? null;
        // Property details theming
        $update->property_details_bg_color = $request->property_details_bg_color ?? $update->property_details_bg_color;
        $update->property_details_text_color = $request->property_details_text_color ?? $update->property_details_text_color;
        $update->property_details_muted_color = $request->property_details_muted_color ?? $update->property_details_muted_color;
        $update->property_details_heading_color = $request->property_details_heading_color ?? $update->property_details_heading_color;
        $update->property_details_price_color = $request->property_details_price_color ?? $update->property_details_price_color;
        $update->property_details_accent_color = $request->property_details_accent_color ?? $update->property_details_accent_color;
        $update->update();

        // Update related user info
        $user = User::where('website_id', $update->id)->first();
        if ($user) {
            $user->name = $request->first_name;
            $user->last_name = $request->last_name;
            // $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->update();
        }

        if ($isWebsiteOwner) {
            return redirect('/users/setting')->with('success', 'Website updated successfully.');
        }

        return redirect()->route('admin.website.index')->with('success', 'Website updated successfully.');
    }

    protected function authorizeWebsiteAccess(Website $website): void
    {
        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();
        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'user') {
            $ownsByWebsiteId = !empty($user->website_id) && (int) $user->website_id === (int) $website->id;
            $ownsByOwnerId = (int) $website->user_id === (int) $user->id;

            if ($ownsByWebsiteId || $ownsByOwnerId) {
                return;
            }
        }

        abort(403, 'You do not have permission to edit this website.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $website = Website::find($id);
        
        if (!$website) {
            return redirect()->route('admin.website.index')->with('error', 'Website not found!');
        }
        
        try {
            // Start database transaction for safe deletion
            \DB::transaction(function () use ($website, $id) {
                // Delete all related data with website_id
                
                // Delete page comments directly by website_id (new approach)
                \App\Models\PageComment::where('website_id', $id)->delete();
                
                // Delete pages and their remaining comments (for any orphaned comments)
                $pages = \App\Models\Page::where('website_id', $id)->get();
                // foreach ($pages as $page) {
                //     // Delete any remaining page comments by page_id (backup cleanup)
                //     \App\Models\PageComment::where('page_identifier', $page->id)->delete();
                // }
                \App\Models\Page::where('website_id', $id)->delete();
                
                // Delete investments
                \App\Models\Investment::where('website_id', $id)->delete();
                
                // Delete users associated with this website
                \App\Models\User::where('website_id', $id)->delete();
                
                // Delete donations
                \App\Models\Donation::where('website_id', $id)->delete();
                
                // Delete auctions and their images
                $auctions = \App\Models\Auction::where('website_id', $id)->get();
                foreach ($auctions as $auction) {
                    \App\Models\AuctionImage::where('auction_id', $auction->id)->delete();
                }
                \App\Models\Auction::where('website_id', $id)->delete();
                
                // Delete transactions
                \App\Models\Transaction::where('website_id', $id)->delete();
                
                // Delete taxes and tax receipts
                \App\Models\Tax::where('website_id', $id)->delete();
                \App\Models\TaxReceipt::where('website_id', $id)->delete();
                
                // Delete tickets and ticket sales
                $tickets = \App\Models\Ticket::where('website_id', $id)->get();
                foreach ($tickets as $ticket) {
                    // Delete ticket sell details first
                    $ticketSells = \App\Models\TicektSell::where('website_id', $id)->get();
                    foreach ($ticketSells as $sell) {
                        \App\Models\TicketSellDetail::where('ticket_sell_id', $sell->id)->delete();
                    }
                    \App\Models\TicektSell::where('website_id', $id)->delete();
                }
                \App\Models\Ticket::where('website_id', $id)->delete();
                
                // Delete sponsors
                \App\Models\Sponsor::where('website_id', $id)->delete();
                
                // Delete headers
                \App\Models\Header::where('website_id', $id)->delete();
                
                // Delete footers
                \App\Models\Footer::where('website_id', $id)->delete();

                // Delete setting
                \App\Models\Setting::where('user_id', $website->user_id)->delete();
                
                // Finally, delete the website itself
                $website->delete();
            });
            
            return redirect()->route('admin.website.index')->with('success', 'Website and all related data deleted successfully!');
            
        } catch (\Exception $e) {
        dd($e->getMessage());

            return redirect()->route('admin.website.index')->with('error', 'Error deleting website: ' . $e->getMessage());
        }
    }
}

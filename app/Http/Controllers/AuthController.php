<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Website;
use App\Models\Setting;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use App\Mail\AccountApproval;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Login is intentionally global across websites.
        // If the same email exists on multiple websites, we authenticate against
        // the first account whose stored password matches the submitted password.
        $user = null;
        $candidates = User::where('email', $request->email)->get();
        foreach ($candidates as $candidate) {
            if (\Hash::check($request->password, $candidate->password)) {
                $user = $candidate;
                break;
            }
        }

        if ($user) {
            Auth::login($user);
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user) {
                // Check if user is parent or individual and if their status is not active
                if (in_array($user->role, ['parents', 'individual']) && $user->status != 1) {
                    Auth::logout();
                    return redirect('/')->with('error', 'Your account is pending approval. Please wait for administrator approval before logging in.');
                }

                // Check if redirect_to parameter is provided (e.g., from page-investment)
                if ($request->has('redirect_to') && $request->redirect_to) {
                    return redirect($request->redirect_to)->with('success', 'Login successful');
                }

                if ($user->role == 'admin') {
                    return redirect()->intended('/admins')->with('success', 'Login successful');
                } else {
                    return redirect()->intended('/users')->with('success', 'Login successful');
                }
            }
        }

        return redirect('login')->with('error', 'Invalid credentials');
    }

    public function register(Request $request)
    {
        // dd($request->all());
        // Resolve website early so we can scope the email-unique rule
        $url     = url()->current();
        $doamin  = parse_url($url, PHP_URL_HOST);
        $check   = Website::where('domain', $doamin)->first();

        $websiteId = $check ? $check->id : null;

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required', 'string', 'email', 'max:255', 'same:confirm_email',
                // Unique per-website: same email is allowed on a different website
                \Illuminate\Validation\Rule::unique('users')->where(
                    fn ($q) => $q->where('website_id', $websiteId)
                ),
            ],
            'password' => 'required|string|min:8|same:confirm_password',
        ]);

        // Handle teacher_id for individual registrations
        $teacher_id = null;
        if ($request->register_as === 'individual' && $request->teacher_id) {
            $teacher_id = $request->teacher_id;
        }

        if ($url == 'fundably.org' || $url == 'https://fundably.org' || $url == 'http://fundably.org' || $url == 'http://127.0.0.1:8000') {
            return redirect()->route('admin.index', 1);
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'email_verified_at ' => now(),
            'password' => Hash::make($request->password),
            'role' => $request->register_as,
            'teacher_id' => $teacher_id,
            'website_id' => $check->id,
            'status' => 1,
        ]);

        $updat = User::find($user->id);
        $updat->email_verified_at = now();
        $updat->update();

        // dd($updat);

        // Auto-approve and send approval email for parents and individual registrations
        if (in_array($request->register_as, ['parents', 'individual'])) {
            try {
                // Apply website-specific mail configuration
                \App\Services\WebsiteMailService::applyForWebsite($check);
                
                // Send approval email to the registrant
                if ($user->email !== 'admin@admin') {
                    Mail::to($user->email)->send(new AccountApproval($user, $check));
                }
                
                // Also send approval notification to contact emails (same pattern as transaction emails)
                $contactEmails = $check->getContactFormEmails();
                foreach ($contactEmails as $contactEmail) {
                    if ($contactEmail !== $user->email && $contactEmail !== 'admin@admin') {  // Don't send duplicate, skip admin@admin
                        Mail::to($contactEmail)->send(new AccountApproval($user, $check));
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't stop registration process
                \Log::error('Account approval email failed: ' . $e->getMessage());
            }

            // Send admin notification emails for parent registrations
            try {
                // Get admin emails from contact form settings (using new Website model methods)
                $adminEmails = [];
                
                // Get site owner (user with role 'user' for this website)
                $siteOwner = User::where('website_id', $check->id)
                    ->where('role', 'user')
                    ->first();
                if ($siteOwner) {
                    $adminEmails[] = $siteOwner->email;
                }

                // Get contact form emails from Website model (respects preferences)
                $websiteContactEmails = $check->getContactFormEmails();
                $adminEmails = array_merge($adminEmails, $websiteContactEmails);

                // Remove duplicates and send notifications
                $adminEmails = array_unique($adminEmails);
                foreach ($adminEmails as $adminEmail) {
                    if ($adminEmail !== 'admin@admin') {
                        Mail::to($adminEmail)->send(new \App\Mail\AdminRegistrationNotification($user, $check));
                    }
                }
            } catch (\Exception $e) {
                // Log error but don't stop registration process
                \Log::error('Admin registration notification email failed: ' . $e->getMessage());
            }
        }

        $successMessage = "Thanks for signing up! 
        Your account has been approved and is ready to use. 
        Please click the “Login” button at the top of the site, and your login credentials have also been sent to your email inbox. If you don’t see them there please check your spam/junk folder!";

        // If registration came from a page (not login page), redirect back with message
        // Otherwise redirect to login
        $referer = $request->headers->get('referer');
        
        // Always redirect back to preserve the page context for auth-form components
        if ($referer && !str_contains($referer, '/login')) {
            return redirect()->back()->with('success', $successMessage);
        }

        return redirect('login')->with('success', $successMessage);
    }

    public function logout()
    {
        $user = Auth::user();
        
        Auth::logout();
        
        // If user is parent or individual, redirect to homepage instead of login
        if ($user && in_array($user->role, ['parents', 'individual'])) {
            return redirect('/')->with('success', 'Logout successful');
        }
        
        return redirect('login')->with('success', 'Logout successful');
    }

    public function updateProfile(Request $request)
    {
        // dd($request->all());
        
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,pjpeg|max:5120',
            'goal' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'photo.image' => 'The photo must be a valid image file.',
            'photo.mimes' => 'The photo must be in JPG, JPEG, PNG, or GIF format.',
            'photo.max' => 'The photo must not exceed 5MB in size.',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $user->photo = 'images/' . $filename;
        }
        $user->goal = $request->goal;
        $user->description = $request->description;
        $user->teacher_id = $request->teacher_id;
        $user->size = $request->size;
        $user->grade = $request->grade;

        $user->save();

        // Handle investor profile for customers
        if ($user->role === 'customer' && $request->has('investor_type')) {
            $investorType = $request->input('investor_type');
            $investorData = $request->input('investor_data', []);
            
            if ($investorType) {
                \App\Models\UserInvestorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'investor_type' => $investorType,
                        'investor_data' => $investorData,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function saveInvestorProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $investorType = $request->input('investor_type');
            
            if (!$investorType) {
                return response()->json(['success' => false, 'message' => 'Investor type is required'], 422);
            }

            // Collect all investor data
            $investorData = [];
            
            // Individual fields
            if ($investorType === 'individual') {
                $investorData = [
                    'individual_name' => $request->input('individual_name'),
                    'date_of_birth' => $request->input('date_of_birth'),
                    'ssn' => $request->input('ssn'),
                ];
            }
            // Joint fields
            elseif ($investorType === 'joint') {
                $investorData = [
                    'primary_name' => $request->input('primary_name'),
                    'primary_dob' => $request->input('primary_dob'),
                    'primary_ssn' => $request->input('primary_ssn'),
                    'secondary_name' => $request->input('secondary_name'),
                    'secondary_dob' => $request->input('secondary_dob'),
                    'secondary_ssn' => $request->input('secondary_ssn'),
                    'joint_type' => $request->input('joint_type'),
                ];
            }
            // Corporation fields
            elseif ($investorType === 'corporation') {
                $investorData = [
                    'corporation_name' => $request->input('corporation_name'),
                    'ein' => $request->input('ein'),
                    'incorporation_state' => $request->input('incorporation_state'),
                    'accredited_investor' => $request->input('accredited_investor'),
                ];
            }
            // Trust fields
            elseif ($investorType === 'trust') {
                $investorData = [
                    'trust_name' => $request->input('trust_name'),
                    'trust_ein' => $request->input('trust_ein'),
                    'trust_type' => $request->input('trust_type'),
                ];
            }
            // IRA fields
            elseif ($investorType === 'ira') {
                $investorData = [
                    'ira_holder_name' => $request->input('ira_holder_name'),
                    'ira_type' => $request->input('ira_type'),
                    'custodian' => $request->input('custodian'),
                ];
            }

            // Update or create investor profile
            $profile = \App\Models\UserInvestorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'investor_type' => $investorType,
                    'investor_data' => $investorData,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Investor profile saved successfully',
                'profile' => $profile
            ]);

        } catch (\Exception $e) {
            \Log::error('Save investor profile error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }

    public function getInvestorProfile(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $profile = \App\Models\UserInvestorProfile::where('user_id', $user->id)->first();

            return response()->json([
                'success' => true,
                'profile' => $profile
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }
}


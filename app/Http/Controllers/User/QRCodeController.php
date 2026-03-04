<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use Illuminate\Support\Str;

class QRCodeController extends Controller
{
    public function index()
    {
        // Redirect to admin QR codes
        return redirect('/qr-codes');
    }

    public function generate(Request $request)
    {
        return redirect('/qr-codes');
    }

    public function download($id)
    {
        return redirect('/qr-codes');
    }

    /**
     * Generate QR code for a student's donation profile
     */
    public function generateStudentQR(Request $request, $student_id)
    {
        try {
            // Verify the student exists and is the authenticated user's student
            $student = User::findOrFail($student_id);
            
            // Verify user has access to this student
            if (auth()->user()->id !== $student->parent_id && auth()->user()->id !== $student->id) {
                // Check if user is super admin
                if (!auth()->user()->hasRoleForWebsite('admin')) {
                    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
                }
            }

            // Get student's website
            $website = $student->website;
            if (!$website) {
                return response()->json(['success' => false, 'message' => 'Website not found for student'], 422);
            }

            // Generate unique QR identifier
            $qrIdentifier = Str::random(10);

            // Build donation URL for this student
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
                        ? 'https://' : 'http://';
            
            $domainBase = $website->domain ? ($protocol . $website->domain) : (request()->getScheme() . '://' . request()->getHost());
            
            $params = [
                'qr' => $qrIdentifier,
                'website_id' => $website->id,
                'type' => 'donation',
                'student_id' => $student_id
            ];

            $donationUrl = $domainBase . '/qr-donate?' . http_build_query($params);

            // Generate QR code
            $qrCode = base64_encode(
                QrCode::format('png')
                    ->size(500)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($donationUrl)
            );

            return response()->json([
                'success' => true,
                'qr_code_base64' => 'data:image/png;base64,' . $qrCode,
                'qr_identifier' => $qrIdentifier,
                'donation_url' => $donationUrl,
                'student_name' => $student->name . ' ' . $student->last_name,
                'website' => $website->name
            ]);

        } catch (\Exception $e) {
            \Log::error('Error generating student QR: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating QR code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR code for user's profile page
     */
    public function generateProfileQR(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            // Get user's website
            $website = $user->website;
            if (!$website) {
                return response()->json(['success' => false, 'message' => 'Website not found for user'], 422);
            }

            // Build profile URL
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                        (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') 
                        ? 'https://' : 'http://';
            
            $domainBase = $website->domain ? ($protocol . $website->domain) : (request()->getScheme() . '://' . request()->getHost());
            
            $profileUrl = $domainBase . '/profile/' . $user->id . '-' . str_replace(' ', '-', $user->name) . '-' . str_replace(' ', '-', $user->last_name);

            // Generate QR code
            $qrCode = base64_encode(
                QrCode::format('png')
                    ->size(500)
                    ->margin(2)
                    ->errorCorrection('H')
                    ->generate($profileUrl)
            );

            return response()->json([
                'success' => true,
                'qr_code_base64' => 'data:image/png;base64,' . $qrCode,
                'profile_url' => $profileUrl,
                'user_name' => $user->name . ' ' . $user->last_name,
                'website' => $website->name
            ]);

        } catch (\Exception $e) {
            \Log::error('Error generating profile QR: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating QR code: ' . $e->getMessage()
            ], 500);
        }
    }
}

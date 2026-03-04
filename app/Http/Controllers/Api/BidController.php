<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Auction;
use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BidController extends Controller
{
    protected $pushNotificationService;

    public function __construct()
    {
        $this->pushNotificationService = new PushNotificationService();
    }
    /**
     * Store a new bid
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:0.01'
        ]);

        try {
            // Check if auction exists and is active
            $auction = Auction::findOrFail($request->auction_id);
            
            // Get the latest bid amount for this auction
            $latestBid = Bid::where('auction_id', $request->auction_id)
                ->orderBy('amount', 'desc')
                ->first();
            
            $minimumBid = $latestBid ? $latestBid->amount : $auction->starting_price;
            
            if ($request->amount <= $minimumBid) {
                return response()->json([
                    'success' => false,
                    'message' => "Bid must be greater than $" . $minimumBid
                ], 422);
            }

            $bid = Bid::create([
                'auction_id' => $request->auction_id,
                'name' => $request->name,
                'email' => $request->email,
                'amount' => $request->amount
            ]);

            // Send outbid notification to previous highest bidder
            try {
                if ($latestBid && $latestBid->email) {
                    // Find user by email
                    $previousBidder = User::where('email', $latestBid->email)->first();
                    
                    if ($previousBidder) {
                        $this->pushNotificationService->sendAuctionOutbidNotification(
                            $previousBidder->id,
                            $auction->title ?? 'Auction Item',
                            $request->amount,
                            $auction->id
                        );
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Push notification error for auction outbid: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully',
                'bid' => $bid
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error placing bid: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest bid for an auction
     */
    public function getLatestBid($auctionId): JsonResponse
    {
        try {
            $bid = Bid::where('auction_id', $auctionId)
                ->orderBy('amount', 'desc')
                ->first();

            if (!$bid) {
                // Get auction starting price
                $auction = Auction::findOrFail($auctionId);
                return response()->json([
                    'success' => true,
                    'amount' => $auction->starting_price ?? 0,
                    'is_starting_price' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'amount' => $bid->amount,
                'is_starting_price' => false,
                'bid' => $bid
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching latest bid: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get bid history for an auction
     */
    public function getBids($auctionId): JsonResponse
    {
        try {
            $bids = Bid::where('auction_id', $auctionId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'bids' => $bids
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching bids: ' . $e->getMessage()
            ], 500);
        }
    }
}
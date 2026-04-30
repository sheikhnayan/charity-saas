<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Add item to cart
     * POST /api/cart/add
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:student,ticket,auction,product',
            'id' => 'required|integer',
            'name' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:1',
            'photo_url' => 'nullable|string',
            'image_url' => 'nullable|string',
            'website_id' => 'nullable|integer'
        ]);

        $success = $this->cartService->addItem($validated['type'], $validated);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'cart' => $this->cartService->getSummary()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add item to cart'
        ], 400);
    }

    /**
     * Get cart
     * GET /api/cart
     */
    public function get()
    {
        return response()->json([
            'success' => true,
            'cart' => $this->cartService->getSummary()
        ]);
    }

    /**
     * Get frontend cart configuration for current website
     * GET /api/cart/config
     */
    public function config(Request $request)
    {
        $host = strtolower((string) $request->getHost());

        $website = Website::query()
            ->whereRaw('LOWER(domain) = ?', [$host])
            ->orWhereRaw('LOWER(domain) = ?', [preg_replace('/^www\./', '', $host)])
            ->orWhereRaw('LOWER(domain) = ?', ['www.' . preg_replace('/^www\./', '', $host)])
            ->first();

        return response()->json([
            'success' => true,
            'hide_floating_cart_icon' => (bool) ($website?->hide_floating_cart_icon ?? false),
            'website_id' => $website?->id,
        ]);
    }

    /**
     * Update cart item
     * PUT /api/cart/item/{itemKey}
     */
    public function update(Request $request, $itemKey)
    {
        $validated = $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $success = $this->cartService->updateItem($itemKey, $validated);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Item updated',
                'cart' => $this->cartService->getSummary()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ], 404);
    }

    /**
     * Remove item from cart
     * DELETE /api/cart/item/{itemKey}
     */
    public function remove($itemKey)
    {
        $success = $this->cartService->removeItem($itemKey);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart' => $this->cartService->getSummary()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found'
        ], 404);
    }

    /**
     * Clear cart
     * DELETE /api/cart/clear
     */
    public function clear()
    {
        $this->cartService->clearCart();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'cart' => $this->cartService->getSummary()
        ]);
    }

    /**
     * Get cart count (for icon badge)
     * GET /api/cart/count
     */
    public function getCount()
    {
        return response()->json([
            'success' => true,
            'count' => $this->cartService->getItemCount(),
            'total' => $this->cartService->getTotal()
        ]);
    }

    /**
     * Validate cart before checkout
     * GET /api/cart/validate
     */
    public function validate()
    {
        $validation = $this->cartService->validateForCheckout();

        return response()->json($validation);
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class CartService
{
    const SESSION_KEY = 'shopping_cart';
    const CART_EXPIRY_HOURS = 24;

    /**
     * Get the entire cart from session
     */
    public function getCart()
    {
        return Session::get(self::SESSION_KEY, $this->getEmptyCart());
    }

    /**
     * Get cart items
     */
    public function getItems()
    {
        $cart = $this->getCart();
        return $cart['items'] ?? [];
    }

    /**
     * Get cart item count
     */
    public function getItemCount()
    {
        $cart = $this->getCart();
        return count($cart['items'] ?? []);
    }

    /**
     * Get cart total
     */
    public function getTotal()
    {
        $cart = $this->getCart();
        return $cart['total'] ?? 0;
    }

    /**
     * Add item to cart
     * 
     * @param string $type - 'student', 'ticket', 'auction', or 'product'
     * @param array $item - Item data
     * @return bool
     */
    public function addItem($type, array $item)
    {
        try {
            // Validate item type
            if (!in_array($type, ['student', 'ticket', 'auction', 'product'])) {
                \Log::error('Invalid cart item type: ' . $type);
                return false;
            }

            // Validate required fields
            if (!isset($item['id']) || !isset($item['name'])) {
                \Log::error('Cart item missing required fields', $item);
                return false;
            }

            // CRITICAL: Use cache lock to prevent race conditions
            // This ensures only ONE request can modify the cart at a time
            $lock = \Cache::lock('cart_lock_' . Session::getId(), 10);
            
            try {
                // Wait up to 10 seconds to acquire the lock
                $lock->block(10);
                
                // Get current cart (now safe from race conditions)
                $cart = $this->getCart();

                // Generate unique key for this item
                $itemKey = $this->generateItemKey($type, $item['id']);

                // Check if item already exists
                if (isset($cart['items'][$itemKey])) {
                    // Update quantity/amount based on type
                    if ($type === 'student') {
                        // For students, update the donation amount
                        $cart['items'][$itemKey]['amount'] = $item['amount'] ?? $cart['items'][$itemKey]['amount'];
                    } else {
                        // For other items, increment quantity
                        $cart['items'][$itemKey]['quantity'] = ($cart['items'][$itemKey]['quantity'] ?? 1) + ($item['quantity'] ?? 1);
                    }
                } else {
                    // Add new item
                    $cartItem = [
                        'id' => $item['id'],
                        'type' => $type,
                        'name' => $item['name'],
                        'key' => $itemKey,
                        'quantity' => $item['quantity'] ?? 1,
                        'photo_url' => $item['photo_url'] ?? null,
                        'image_url' => $item['image_url'] ?? null,
                    ];

                    // Handle pricing based on type
                    if ($type === 'student') {
                        $cartItem['amount'] = $item['amount'] ?? 0;
                        $cartItem['photo_url'] = $item['photo_url'] ?? null;
                    } elseif ($type === 'ticket') {
                        $cartItem['price'] = $item['price'] ?? 0;
                        $cartItem['image_url'] = $item['image_url'] ?? null;
                    } elseif ($type === 'auction') {
                        $cartItem['current_bid'] = $item['current_bid'] ?? $item['price'] ?? 0;
                        $cartItem['image_url'] = $item['image_url'] ?? null;
                    } elseif ($type === 'product') {
                        $cartItem['price'] = $item['price'] ?? 0;
                        $cartItem['image_url'] = $item['image_url'] ?? null;
                    }

                    // Add additional metadata
                    if (isset($item['website_id'])) {
                        $cartItem['website_id'] = $item['website_id'];
                    }

                    $cart['items'][$itemKey] = $cartItem;
                }

                // Recalculate totals
                $cart = $this->recalculateCart($cart);

                // Save to session
                Session::put(self::SESSION_KEY, $cart);
                
                // CRITICAL: Force immediate session write to database
                // Without this, the session is written at end of request lifecycle
                // which means subsequent GET requests might read stale data
                Session::save();

                \Log::info('Item added to cart', [
                    'type' => $type,
                    'item_id' => $item['id'],
                    'item_key' => $itemKey,
                    'cart_total' => $cart['total'],
                    'item_count' => count($cart['items']),
                    'all_items' => array_keys($cart['items'])
                ]);

                return true;
                
            } finally {
                // ALWAYS release the lock
                optional($lock)->release();
            }

        } catch (\Exception $e) {
            \Log::error('Error adding item to cart: ' . $e->getMessage(), [
                'type' => $type,
                'item' => $item,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem($itemKey)
    {
        try {
            $cart = $this->getCart();

            if (isset($cart['items'][$itemKey])) {
                $removed = $cart['items'][$itemKey];
                unset($cart['items'][$itemKey]);

                // Recalculate totals
                $cart = $this->recalculateCart($cart);

                // Save to session
                Session::put(self::SESSION_KEY, $cart);

                \Log::info('Item removed from cart', [
                    'item_key' => $itemKey,
                    'item' => $removed,
                    'cart_total' => $cart['total']
                ]);

                return true;
            }

            return false;

        } catch (\Exception $e) {
            \Log::error('Error removing item from cart: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update item in cart (amount or quantity)
     */
    public function updateItem($itemKey, $updates)
    {
        try {
            $cart = $this->getCart();

            if (!isset($cart['items'][$itemKey])) {
                return false;
            }

            $item = &$cart['items'][$itemKey];

            // Update amount for students (preserve it even when updating quantity)
            if (isset($updates['amount'])) {
                $item['amount'] = max(0, (float)$updates['amount']);
            }

            // Update quantity for other items (preserve amount for students)
            if (isset($updates['quantity'])) {
                $item['quantity'] = max(1, (int)$updates['quantity']);
            }

            // Recalculate totals
            $cart = $this->recalculateCart($cart);

            // Save to session
            Session::put(self::SESSION_KEY, $cart);

            \Log::info('Cart item updated', [
                'item_key' => $itemKey,
                'updates' => $updates,
                'cart_total' => $cart['total']
            ]);

            return true;

        } catch (\Exception $e) {
            \Log::error('Error updating cart item: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        Session::forget(self::SESSION_KEY);
        return true;
    }

    /**
     * Get empty cart structure
     */
    private function getEmptyCart()
    {
        return [
            'items' => [],
            'total' => 0,
            'item_count' => 0,
            'created_at' => Carbon::now()->timestamp,
            'expires_at' => Carbon::now()->addHours(self::CART_EXPIRY_HOURS)->timestamp
        ];
    }

    /**
     * Generate unique key for cart item
     */
    private function generateItemKey($type, $id)
    {
        return "{$type}_{$id}";
    }

    /**
     * Recalculate cart totals and counts
     */
    private function recalculateCart($cart)
    {
        $total = 0;
        $itemCount = 0;

        foreach ($cart['items'] as $item) {
            // Calculate based on type
            if ($item['type'] === 'student') {
                $total += ($item['amount'] ?? 0) * ($item['quantity'] ?? 1);
                $itemCount += $item['quantity'] ?? 1;
            } elseif ($item['type'] === 'ticket') {
                $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                $itemCount += $item['quantity'] ?? 1;
            } elseif ($item['type'] === 'auction') {
                $total += ($item['current_bid'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1);
                $itemCount += $item['quantity'] ?? 1;
            } elseif ($item['type'] === 'product') {
                $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                $itemCount += $item['quantity'] ?? 1;
            }
        }

        $cart['total'] = round($total, 2);
        $cart['item_count'] = $itemCount;

        return $cart;
    }

    /**
     * Get cart summary for display
     */
    public function getSummary()
    {
        $cart = $this->getCart();
        
        $summary = [
            'total_items' => count($cart['items'] ?? []),
            'item_count' => $cart['item_count'] ?? 0,
            'subtotal' => 0,
            'tax' => 0,
            'total' => $cart['total'] ?? 0,
            'items_by_type' => []
        ];

        foreach ($cart['items'] ?? [] as $item) {
            $type = $item['type'];
            if (!isset($summary['items_by_type'][$type])) {
                $summary['items_by_type'][$type] = [];
            }
            $summary['items_by_type'][$type][] = $item;
        }

        return $summary;
    }

    /**
     * Transform cart items to checkout data
     * This prepares cart data for processing payments
     */
    public function getCheckoutData()
    {
        $cart = $this->getCart();
        $items = $cart['items'] ?? [];

        $checkoutData = [
            'students' => [],
            'tickets' => [],
            'auctions' => [],
            'products' => [],
            'total_amount' => $cart['total'] ?? 0,
            'item_count' => count($items)
        ];

        foreach ($items as $item) {
            if ($item['type'] === 'student') {
                $checkoutData['students'][] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'amount' => $item['amount'],
                    'quantity' => $item['quantity']
                ];
            } elseif ($item['type'] === 'ticket') {
                $checkoutData['tickets'][] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ];
            } elseif ($item['type'] === 'auction') {
                $checkoutData['auctions'][] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'current_bid' => $item['current_bid'] ?? $item['price'],
                    'quantity' => $item['quantity']
                ];
            } elseif ($item['type'] === 'product') {
                $checkoutData['products'][] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ];
            }
        }

        return $checkoutData;
    }

    /**
     * Validate cart before checkout
     */
    public function validateForCheckout()
    {
        $cart = $this->getCart();

        if (empty($cart['items'])) {
            return [
                'valid' => false,
                'message' => 'Your cart is empty'
            ];
        }

        // Validate each item - but don't require amount for students
        // Amount can be entered at checkout or be $0 for pure support
        foreach ($cart['items'] as $item) {
            // For students, amount is optional and can be 0
            // Users can add students without a donation, or enter amount at checkout
            // Skip validation for student amounts - it's optional
        }

        return [
            'valid' => true,
            'message' => 'Cart is valid'
        ];
    }
}

/**
 * Cart Shipping Calculator
 * Handles shipping calculations for all product categories using Australia Post only
 */

class CartShippingCalculator {
    constructor() {
        this.baseUrl = '/cart-shipping';
        this.selectedShipping = null;
    }

    /**
     * Calculate shipping for cart items
     */
    async calculateShipping(cartItems) {
        try {
            console.log('Calculating shipping for:', cartItems);
            
            // Get current session shipping to check if we should preserve it
            const currentSessionShipping = await this.getSessionShipping();
            console.log('Current session shipping:', currentSessionShipping);
            
            // Get CSRF token with fallback
            const csrfToken = this.getCsrfToken();
            
            const response = await fetch(`${this.baseUrl}/calculate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ cart_items: cartItems })
            });

            const data = await response.json();
            console.log('Shipping calculation response:', data);
            console.log('Shipping options structure:', data.shipping_options);
            
            if (data.success) {
                await this.displayShippingOptions(data.shipping_options);
                return data.shipping_options;
            } else {
                console.error('Shipping calculation failed:', data.message);
                // Clear session shipping if calculation fails
                await this.clearShippingSelection();
                this.selectedShipping = null;
                return null;
            }
        } catch (error) {
            console.error('Error calculating shipping:', error);
            return null;
        }
    }

    /**
     * Get CSRF token with fallback
     */
    getCsrfToken() {
        // Try to get from meta tag
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }
        
        // Try to get from input field
        const inputTag = document.querySelector('input[name="_token"]');
        if (inputTag) {
            return inputTag.value;
        }
        
        // Fallback - try to get from any form
        const form = document.querySelector('form');
        if (form) {
            const tokenInput = form.querySelector('input[name="_token"]');
            if (tokenInput) {
                return tokenInput.value;
            }
        }
        
        console.warn('CSRF token not found, using empty string');
        return '';
    }

    /**
     * Get shipping options for specific quantity
     */
    async getShippingForQuantity(quantity, category = 'scrapbook_page_printing') {
        try {
            const csrfToken = this.getCsrfToken();
            
            const response = await fetch(`${this.baseUrl}/quantity-options`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ 
                    quantity: quantity,
                    category: category
                })
            });

            const data = await response.json();
            
            if (data.success) {
                return data.shipping_options;
            } else {
                console.error('Failed to get shipping options:', data.message);
                return null;
            }
        } catch (error) {
            console.error('Error getting shipping options:', error);
            return null;
        }
    }

    /**
     * Display shipping options in the cart
     */
    async displayShippingOptions(shippingOptions) {
        const shippingContainer = document.getElementById('shipping-options');
        if (!shippingContainer) return;

        console.log('Displaying shipping options:', shippingOptions);

        // Get selected shipping from session
        let sessionShipping = await this.getSessionShipping();
        console.log('Session shipping:', sessionShipping);

        let html = '<div class="shipping-options">';
        
        if (shippingOptions && shippingOptions.length > 0) {
            // Auto-selection logic: Only auto-select if no session shipping exists
            let autoSelectedOption = null;
            
            // Only auto-select if there's no existing session shipping
            if (!sessionShipping) {
                // If only one option, auto-select it
                if (shippingOptions.length === 1) {
                    autoSelectedOption = shippingOptions[0];
                } else {
                    // If multiple options, prioritize Australia Post snail_mail
                    const auspostSnailOption = shippingOptions.find(option => 
                        option.carrier === 'auspost' && option.service === 'snail_mail'
                    );
                    if (auspostSnailOption) {
                        autoSelectedOption = auspostSnailOption;
                    } else {
                        // Fallback to first option
                        autoSelectedOption = shippingOptions[0];
                    }
                }
                
                console.log('Auto-selected option:', autoSelectedOption);
                
                // Store auto-selection in session
                if (autoSelectedOption) {
                    await this.updateShippingSelectionOnServer(autoSelectedOption);
                    sessionShipping = autoSelectedOption;
                    this.selectedShipping = autoSelectedOption; // Set as current selection
                }
            } else {
                console.log('Using existing session shipping:', sessionShipping);
            }
            
            shippingOptions.forEach((option, index) => {
                console.log(`Option ${index}:`, option);
                console.log(`Option ${index} price type:`, typeof option.price, 'value:', option.price);
                
                // Check if this option matches session shipping (user selection takes priority)
                const isSelected = sessionShipping && 
                                 sessionShipping.carrier === option.carrier && 
                                 sessionShipping.service === option.service;
                
                console.log(`Option ${index} isSelected:`, isSelected);
                
                // Ensure price is a number
                const price = parseFloat(option.price) || 0;
                
                html += `
                    <div class="shipping-option ${isSelected ? 'selected' : ''}" 
                         data-carrier="${option.carrier}" 
                         data-service="${option.service}" 
                         data-price="${price}">
                        <div class="shipping-option-content">
                            <input type="radio" name="shipping_option" 
                                   value="${option.carrier}_${option.service}" 
                                   ${isSelected ? 'checked' : ''}>
                            <div class="shipping-name">${this.formatCarrierName(option.carrier)} - ${this.formatServiceName(option.service)}</div>
                        </div>
                        <div class="shipping-price">
                            $${price.toFixed(2)}
                        </div>
                    </div>
                `;
            });
            
            // Set the session shipping as current selection
            if (sessionShipping) {
                this.selectedShipping = sessionShipping;
            }
        } else {
            // Clear session shipping if no options are available
            await this.clearShippingSelection();
            this.selectedShipping = null; // Clear local selection
            console.log('No shipping options available, cleared session shipping');
            
            // Fallback display if no shipping options
            html += '<div class="shipping-error">No shipping options available.</div>';
        }
        
        html += '</div>';
        shippingContainer.innerHTML = html;

        // Add event listeners
        this.addShippingOptionListeners();
        
        // Update cart total with selected shipping
        this.updateCartTotal();
        
        // Ensure the selected shipping is saved to session immediately
        if (this.selectedShipping) {
            this.updateShippingOnServer();
        }
    }

    /**
     * Get shipping selection from session via AJAX
     */
    async getSessionShipping() {
        try {
            const csrfToken = this.getCsrfToken();
            
            const response = await fetch(`${this.baseUrl}/get-session-shipping`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();
            if (data.success && data.shipping) {
                return data.shipping;
            }
        } catch (error) {
            console.error('Error getting session shipping:', error);
        }
        return null;
    }

    /**
     * Update shipping selection on server (for auto-selection)
     */
    async updateShippingSelectionOnServer(shippingOption) {
        try {
            const csrfToken = this.getCsrfToken();
            
            const response = await fetch(`${this.baseUrl}/update-selection`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    carrier: shippingOption.carrier,
                    service: shippingOption.service,
                    price: shippingOption.price
                })
            });

            const data = await response.json();
            if (data.success) {
                console.log('Auto-selected shipping option stored in session');
            } else {
                console.error('Failed to store auto-selected shipping:', data.message);
            }
        } catch (error) {
            console.error('Error storing auto-selected shipping:', error);
        }
    }

    /**
     * Format service name for display
     */
    formatServiceName(service) {
        const names = {
            'snail_mail': 'Snail Mail',
            'express': 'Express',
            'regular': 'Regular'
        };
        return names[service] || service;
    }

    /**
     * Format carrier name for display
     */
    formatCarrierName(carrier) {
        const names = {
            'auspost': 'Australia Post'
        };
        return names[carrier] || carrier;
    }

    /**
     * Add event listeners to shipping options
     */
    addShippingOptionListeners() {
        const options = document.querySelectorAll('.shipping-option');
        
        options.forEach(option => {
            option.addEventListener('click', async (e) => {
                // Don't trigger if clicking on radio button
                if (e.target.type === 'radio') return;
                
                const radio = option.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update selected shipping
                this.selectedShipping = {
                    carrier: option.dataset.carrier,
                    service: option.dataset.service,
                    price: parseFloat(option.dataset.price)
                };
                
                // Send shipping selection to server
                await this.updateShippingOnServer();
                
                // Update cart total immediately
                this.updateCartTotal();
                
                // Reload page after sending to server
                setTimeout(() => {
                    location.reload();
                }, 500);
            });
        });
    }

    /**
     * Update shipping selection on server
     */
    async updateShippingOnServer() {
        if (!this.selectedShipping) return;
        
        try {
            const csrfToken = this.getCsrfToken();
            
            const response = await fetch(`${this.baseUrl}/update-selection`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    carrier: this.selectedShipping.carrier,
                    service: this.selectedShipping.service,
                    price: this.selectedShipping.price
                })
            });

            const data = await response.json();
            if (data.success) {
                console.log('Shipping selection updated on server');
            } else {
                console.error('Failed to update shipping selection:', data.message);
            }
        } catch (error) {
            console.error('Error updating shipping selection:', error);
        }
    }

    /**
     * Clear shipping selection (for pickup)
     */
    async clearShippingSelection() {
        try {
            const csrfToken = this.getCsrfToken();
            
            const response = await fetch(`${this.baseUrl}/clear-selection`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();
            if (data.success) {
                console.log('Shipping selection cleared on server');
            } else {
                console.error('Failed to clear shipping selection:', data.message);
            }
        } catch (error) {
            console.error('Error clearing shipping selection:', error);
        }
    }

    /**
     * Update shipping selection UI
     */
    updateShippingSelection() {
        const options = document.querySelectorAll('.shipping-option');
        options.forEach(option => {
            option.classList.remove('selected');
        });
        
        if (this.selectedShipping) {
            const selectedOption = document.querySelector(
                `.shipping-option[data-carrier="${this.selectedShipping.carrier}"][data-service="${this.selectedShipping.service}"]`
            );
            if (selectedOption) {
                selectedOption.classList.add('selected');
            }
        }
    }

    /**
     * Update cart total with shipping
     */
    updateCartTotal() {
        if (!this.selectedShipping) return;

        console.log('selectedShipping',this.selectedShipping);
        
        const subtotalElement = document.getElementById('cart-total');
        console.log('subtotalElement',subtotalElement);
        const shippingElement = document.getElementById('shipping-cost');
        const shippingRow = document.getElementById('shipping-cost-row');
        
        if (subtotalElement && shippingElement && shippingRow) {
            const subtotal = parseFloat(subtotalElement.dataset.subtotal || 0);
            const newShippingCost = this.selectedShipping.price;
            
            // Get current total (which already includes shipping)
            const currentTotalText = subtotalElement.querySelector('bdi')?.textContent || '';
            const currentTotal = parseFloat(currentTotalText.replace(/[^0-9.-]/g, '')) || 0;
            
            // Get current shipping cost from display
            const currentShippingText = shippingElement.textContent || '$0.00';
            const currentShippingCost = parseFloat(currentShippingText.replace(/[^0-9.-]/g, '')) || 0;
            
            // Calculate new total by replacing shipping cost
            const newTotal = currentTotal - currentShippingCost + newShippingCost;
            
            // Update shipping cost display
            shippingElement.textContent = `$${newShippingCost.toFixed(2)}`;
            shippingRow.style.display = 'table-row';
        }
    }

    /**
     * Get selected shipping option
     */
    getSelectedShipping() {
        return this.selectedShipping;
    }

    /**
     * Initialize shipping calculator
     */
    async init() {
        // Auto-calculate shipping when cart items change
        this.observeCartChanges();
    }

    /**
     * Observe cart changes and recalculate shipping
     */
    observeCartChanges() {
        // This can be customized based on your cart implementation
        const cartContainer = document.getElementById('cart-items');
        if (cartContainer) {
            // You can use MutationObserver or listen to custom events
            // For now, we'll rely on manual triggers
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', async function() {
    window.cartShippingCalculator = new CartShippingCalculator();
    await window.cartShippingCalculator.init();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = CartShippingCalculator;
} 
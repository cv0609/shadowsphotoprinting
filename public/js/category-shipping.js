/**
 * Category-wise Shipping Calculator
 */
console.log('Category-shipping.js file loaded successfully');

class CategoryShippingCalculator {
    constructor() {
        this.categorySelections = {};
        this.categoryShippingOptions = {};
        this.baseSubtotal = 0;
        this.isInitialLoad = true;
        this.hasSessionData = false;
        this.init();
    }

    init() {
        console.log('CategoryShippingCalculator initializing...');
        
        // Get the current total from CartService (backend) - DO NOT OVERRIDE
        const cartTotalText = $('#cart-total').text();
        const cartTotalData = $('#cart-total').data('subtotal');
        
        console.log('Raw cart total text:', cartTotalText);
        console.log('Raw cart total data-subtotal:', cartTotalData);
        
        this.currentTotal = parseFloat(cartTotalText.replace(/[^0-9.-]+/g, '') || 0);
        this.baseSubtotal = parseFloat(cartTotalData || 0);
        this.currentShippingInTotal = this.currentTotal - this.baseSubtotal;
        
        console.log('=== INITIAL VALUES ===');
        console.log('Current total (from CartService):', this.currentTotal);
        console.log('Base subtotal:', this.baseSubtotal);
        console.log('Current shipping in total:', this.currentShippingInTotal);
        console.log('========================');
        
        // Show current shipping amount if it exists
        if (this.currentShippingInTotal > 0) {
            $('#total-shipping-amount').text('$' + this.currentShippingInTotal.toFixed(2));
            $('#total-shipping-cost').show();
            console.log('Set initial shipping amount to:', this.currentShippingInTotal.toFixed(2));
        }
        
        // Wait a bit for DOM to be fully ready
        setTimeout(() => {
            console.log('Starting category shipping calculation...');
            this.calculateCategoryShippingAndUpdateSession(true); // Auto-calculate, select snail mail, and update session
        }, 500);
        
        this.bindEvents();
    }

    bindEvents() {
        // Handle category shipping selection changes
        $(document).on('change', '.category-shipping-option', (e) => {
            const category = $(e.target).data('category');
            const service = $(e.target).val();
            const price = parseFloat($(e.target).data('price') || 0);
            
            console.log(`User changed shipping for ${category}: ${service} $${price}`);
            console.log('Radio button details:', {
                name: $(e.target).attr('name'),
                value: $(e.target).val(),
                checked: $(e.target).prop('checked'),
                category: category,
                service: service,
                price: price
            });
            
            // Update the category selection
            this.updateCategorySelection(category, service, price);
            
            // Ensure the radio button stays checked for this category
            this.ensureRadioButtonChecked(category, service);
            
            // Always update session immediately when user makes a selection
            this.updateSessionWithCurrentSelections();
            
            // Only calculate total if this is a real user change (not programmatic)
            if (!this.isInitialLoad) {
                this.calculateTotalShipping();
            }
            
            // Refresh the page after a longer delay to ensure session is saved
            setTimeout(() => {
                console.log('Refreshing page to show updated shipping selections...');
                console.log('Final categorySelections before refresh:', this.categorySelections);
                window.location.reload();
            }, 1000);
        });

        // Handle pickup/shipping radio button changes
        $(document).on('change', 'input[name="order_type"]', (e) => {
            const orderType = $(e.target).val();
            console.log('Order type changed to:', orderType);
            
            if (orderType === '1') { // Pickup
                console.log('Pickup selected - clearing shipping session');
                this.clearShippingSession();
                
                // Refresh page after clearing session
                setTimeout(() => {
                    console.log('Refreshing page after pickup selection...');
                    window.location.reload();
                }, 500);
            } else if (orderType === '0' || orderType === '2') { // Shipping (both 0 and 2)
                console.log('Shipping selected - restoring shipping session');
                this.restoreShippingSession();
                
                // Refresh page after restoring session
                setTimeout(() => {
                    console.log('Refreshing page after shipping selection...');
                    window.location.reload();
                }, 500);
            }
        });

        // Handle cart item changes
        $(document).on('change', 'input[name="product_quantity[]"]', () => {
            console.log('Cart quantity changed, recalculating shipping...');
            setTimeout(() => {
                this.calculateCategoryShippingAndUpdateSession(true);
            }, 100);
        });

        // Handle item removal
        $(document).on('click', '.product-remove a', () => {
            console.log('Item removed, recalculating shipping...');
            setTimeout(() => {
                this.calculateCategoryShippingAndUpdateSession(true);
            }, 100);
        });
    }

    getCartItemsForShipping() {
        const cartItems = [];
        
        console.log('Looking for cart items in DOM...');
        console.log('Cart summary element:', $('.cart-summary').length);
        console.log('Table rows found:', $('.cart-summary tbody tr').length);
        
        // Read cart items from the table rows
        $('.cart-summary tbody tr').each(function(index) {
            const $row = $(this);
            
            console.log(`Row ${index}:`, {
                hasActions: $row.find('.actions').length > 0,
                hasTh: $row.find('th').length > 0,
                hasDataProductId: $row.data('product-id'),
                hasQuantityInput: $row.find('input[name="product_quantity[]"]').length > 0,
                rowHtml: $row.html().substring(0, 100) + '...'
            });
            
            // Skip the actions row (update cart button row)
            if ($row.find('.actions').length > 0) {
                console.log(`Row ${index}: Skipping actions row`);
                return;
            }
            
            // Skip header rows
            if ($row.find('th').length > 0) {
                console.log(`Row ${index}: Skipping header row`);
                return;
            }
            
            // Get product information from the row
            const productName = $row.find('.product-name').text().trim();
            const quantityInput = $row.find('input[name="product_quantity[]"]');
            const quantity = quantityInput.val();
            const productId = $row.data('product-id');
            const productType = $row.data('product-type');
            const isTestPrint = $row.data('is-test-print');
            
            console.log(`Row ${index} data:`, {
                productName,
                quantity,
                productId,
                productType,
                isTestPrint,
                hasQuantityInput: quantityInput.length > 0
            });
            
            // Only add if we have valid data
            if (productId && quantity && quantity > 0) {
                cartItems.push({
                    product_id: parseInt(productId),
                    quantity: parseInt(quantity),
                    product_type: productType || 'product',
                    is_test_print: isTestPrint || '0',
                    product_name: productName
                });
                console.log(`Row ${index}: Added to cart items`);
            } else {
                console.log(`Row ${index}: Skipped - invalid data`);
            }
        });
        
        console.log('Final cart items for shipping:', cartItems);
        return cartItems;
    }

    calculateCategoryShipping(autoCalculate = true) {
        const cartItems = this.getCartItemsForShipping();
        
        console.log('Calculating category shipping for items:', cartItems);
        console.log('Auto calculate:', autoCalculate);
        
        if (cartItems.length === 0) {
            // Try fallback method - get cart items from server
            console.log('No cart items found in DOM, trying fallback...');
            this.getCartItemsFromServer();
            return;
        }

        $('#category-shipping-options').html('<div class="shipping-loading">Calculating shipping options...</div>');

        $.ajax({
            url: '/cart-shipping/calculate-per-category',
            method: 'POST',
            data: {
                cart_items: cartItems,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Category shipping response:', response);
                if (response.success) {
                    this.categoryShippingOptions = response.category_shipping_options;
                    console.log('Category shipping options structure:', this.categoryShippingOptions);
                    this.displayCategoryShippingOptions(autoCalculate);
                } else {
                    $('#category-shipping-options').html('<div class="shipping-error">Error calculating shipping: ' + (response.message || 'Unknown error') + '</div>');
                }
            },
            error: (xhr, status, error) => {
                console.error('Error calculating category shipping:', error);
                console.error('Response:', xhr.responseText);
                $('#category-shipping-options').html('<div class="shipping-error">Error calculating shipping</div>');
            }
        });
    }

    // New method: Calculate shipping, auto-select snail mail, update session, and fetch updated total
    calculateCategoryShippingAndUpdateSession(autoCalculate = true) {
        const cartItems = this.getCartItemsForShipping();
        
        console.log('=== CALCULATE AND UPDATE SESSION ===');
        console.log('Calculating category shipping for items:', cartItems);
        console.log('Auto calculate:', autoCalculate);
        
        if (cartItems.length === 0) {
            console.log('No cart items found in DOM, trying fallback...');
            this.getCartItemsFromServer();
            return;
        }

        $('#category-shipping-options').html('<div class="shipping-loading">Calculating shipping options...</div>');

        $.ajax({
            url: '/cart-shipping/calculate-per-category',
            method: 'POST',
            data: {
                cart_items: cartItems,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Category shipping response:', response);
                if (response.success) {
                    this.categoryShippingOptions = response.category_shipping_options;
                    console.log('Category shipping options structure:', this.categoryShippingOptions);
                    
                    if (autoCalculate) {
                        // Check if there's existing session data
                        this.loadExistingSessionData();
                    } else {
                        this.displayCategoryShippingOptions(false);
                    }
                } else {
                    $('#category-shipping-options').html('<div class="shipping-error">Error calculating shipping: ' + (response.message || 'Unknown error') + '</div>');
                }
            },
            error: (xhr, status, error) => {
                console.error('Error calculating category shipping:', error);
                console.error('Response:', xhr.responseText);
                $('#category-shipping-options').html('<div class="shipping-error">Error calculating shipping</div>');
            }
        });
    }

    getCartItemsFromServer() {
        $.ajax({
            url: '/cart-shipping/get-cart-items',
            method: 'GET',
            success: (response) => {
                if (response.success && response.cart_items.length > 0) {
                    console.log('Got cart items from server:', response.cart_items);
                    this.calculateCategoryShippingWithItems(response.cart_items, true);
                } else {
                    $('#category-shipping-options').html('<div class="no-shipping">No items in cart</div>');
                }
            },
            error: (xhr, status, error) => {
                console.error('Error getting cart items from server:', error);
                $('#category-shipping-options').html('<div class="no-shipping">No items in cart</div>');
            }
        });
    }

    calculateCategoryShippingWithItems(cartItems, autoCalculate = true) {
        $('#category-shipping-options').html('<div class="shipping-loading">Calculating shipping options...</div>');

        $.ajax({
            url: '/cart-shipping/calculate-per-category',
            method: 'POST',
            data: {
                cart_items: cartItems,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Category shipping response:', response);
                if (response.success) {
                    this.categoryShippingOptions = response.category_shipping_options;
                    
                    if (autoCalculate) {
                        // Check if there's existing session data
                        this.loadExistingSessionData();
                    } else {
                        this.displayCategoryShippingOptions(false);
                    }
                } else {
                    $('#category-shipping-options').html('<div class="shipping-error">Error calculating shipping: ' + (response.message || 'Unknown error') + '</div>');
                }
            },
            error: (xhr, status, error) => {
                console.error('Error calculating category shipping:', error);
                console.error('Response:', xhr.responseText);
                $('#category-shipping-options').html('<div class="shipping-error">Error calculating shipping</div>');
            }
        });
    }

    displayCategoryShippingOptions(autoCalculate = true) {
        if (Object.keys(this.categoryShippingOptions).length === 0) {
            $('#category-shipping-options').html('<div class="no-shipping">No shipping options available</div>');
            return;
        }

        let html = '<div class="category-shipping-container">';
        
        // Check if this is a combined order
        if (this.categoryShippingOptions.combined_order) {
            // Combined order - show single shipping option with same design layout
            html += '<h4 style="font-size:14px;" class="choose-prefered-option-heading">Choose your preferred shipping method:</h4>';
            
            const combinedOptions = this.categoryShippingOptions.combined_order;
            
            html += '<div class="category-shipping-category">';
            // html += '<h5>Combined Order Shipping</h5>';
            html += '<div class="shipping-options text-center">';
            
            // Snail Mail option
            if (combinedOptions.snail_mail) {
                html += '<div class="shipping-option">';
                html += '<input type="radio" name="shipping_combined" class="category-shipping-option" data-category="combined_order" data-service="snail_mail" data-price="' + combinedOptions.snail_mail.price + '" value="snail_mail" checked>';
                html += '<label>';
                html += '<span class="service-name">snail</span>';
                html += '<span class="service-price">$' + parseFloat(combinedOptions.snail_mail.price).toFixed(2) + '</span>';
                html += '</label>';
                html += '</div>';
            }
            
            // Express option
            if (combinedOptions.express) {
                html += '<div class="shipping-option">';
                html += '<input type="radio" name="shipping_combined" class="category-shipping-option" data-category="combined_order" data-service="express" data-price="' + combinedOptions.express.price + '" value="express">';
                html += '<label>';
                html += '<span class="service-name">express</span>';
                html += '<span class="service-price">$' + parseFloat(combinedOptions.express.price).toFixed(2) + '</span>';
                html += '</label>';
                html += '</div>';
            }
            
            html += '</div>';
            html += '</div>';
            
        } else {
            // Separate order - show category-wise shipping options
            html += '<h3>Shipping Options</h3>';
            html += '<p class="choose-prefered-option-heading" style="font-size:14px;">Choose your preferred shipping method:</p>';
            
            Object.keys(this.categoryShippingOptions).forEach(category => {
                const options = this.categoryShippingOptions[category];
                const categoryName = this.getCategoryDisplayName(category);
                
                console.log(`Processing category: ${category}`, options);
                
                // Special handling for photo_print_scrapbook_combined - no outer wrapper
                if (category === 'photo_print_scrapbook_combined') {
                    html += '<div class="shipping-options text-center">';
                    
                    if (Array.isArray(options)) {
                        console.log(`Category ${category} has array options:`, options);
                        options.forEach(option => {
                            console.log(`Processing option:`, option);
                            html += this.createShippingOptionHtml(category, option);
                        });
                    }
                    
                    html += '</div>';
                } else {
                    // Regular categories with wrapper
                    html += `<div class="category-shipping-group" data-category="${category}">`;
                    // html += `<h4>${categoryName}</h4>`;
                    html += '<div class="shipping-options text-center">';
                    
                    if (Array.isArray(options)) {
                        // All categories now return arrays of options
                        console.log(`Category ${category} has array options:`, options);
                        options.forEach(option => {
                            console.log(`Processing option:`, option);
                            html += this.createShippingOptionHtml(category, option);
                        });
                    } else {
                        // Fallback for old format (should not happen anymore)
                        console.log(`Category ${category} has object options (fallback):`, options);
                        if (options.snail_mail) {
                            console.log(`Processing snail_mail option:`, options.snail_mail);
                            html += this.createShippingOptionHtml(category, options.snail_mail);
                        }
                        if (options.express) {
                            console.log(`Processing express option:`, options.express);
                            html += this.createShippingOptionHtml(category, options.express);
                        }
                    }
                    
                    html += '</div></div>';
                }
            });
        }
        
        html += '</div>';
        
        // Add Test Total Shipping section
        html += '<div class="test-total-shipping-section">';
        // html += '<h4>Test Total Shipping</h4>';
        html += '<div class="test-total-display" style="font-size:13px;">';
        html += '<span class="test-total-label">Total Shipping: </span>';
        html += '<span class="test-total-amount">$0.00</span>';
        html += '</div>';
        html += '</div>';
        
        $('#category-shipping-options').html(html);
        
        // Check radio buttons based on session data
        this.checkRadioButtonsFromSession();
        
        // Only auto-select if autoCalculate is true and no session data exists
        // AND if user hasn't already made selections
        if (autoCalculate && Object.keys(this.categorySelections).length === 0 && !this.hasSessionData) {
            console.log('No user selections found, running auto-selection...');
            this.autoSelectShippingOptions();
        } else {
            console.log('Skipping auto-selection - user has selections or session data exists');
            console.log('Current selections:', this.categorySelections);
            console.log('Has session data:', this.hasSessionData);
        }
        
        // Update test total shipping display after options are displayed
        this.updateTestTotalShipping();
    }

    createShippingOptionHtml(category, option) {
        const isChecked = this.categorySelections[category]?.service === option.service ? 'checked' : '';
        const price = parseFloat(option.price) || 0;
        
        return `
            <div class="shipping-option">
                <input type="radio" 
                       name="shipping_${category}" 
                       class="category-shipping-option"
                       data-category="${category}"
                       data-service="${option.service}"
                       data-price="${price}"
                       value="${option.service}"
                       ${isChecked}>
                <label>
                    <span class="service-name">${this.getServiceDisplayName(option.service)}</span>
                    <span class="service-price">$${price.toFixed(2)}</span>
                </label>
            </div>
        `;
    }

    getCategoryDisplayName(category) {
        const names = {
            'scrapbook_page_printing': 'Scrapbook Page Printing',
            'photo_prints': 'Photo Prints',
            'photo_print_scrapbook_combined': 'Photo Print & Scrapbook Combined',
            'canvas': 'Canvas Prints',
            'photo_enlargements': 'Photo Enlargements',
            'posters': 'Posters',
            'hand_craft': 'Hand Craft',
            'photos_for_sale': 'Photos for Sale',
            'gift_card': 'Gift Card',
            'test_prints': 'Test Prints'
        };
        return names[category] || category.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    getServiceDisplayName(service) {
        return service === 'snail_mail' ? 'snail' : 'express';
    }

    autoSelectShippingOptions() {
        console.log('=== AUTO SELECT SHIPPING OPTIONS ===');
        console.log('Available categories:', Object.keys(this.categoryShippingOptions));
        
        // Check if this is a combined order
        if (this.categoryShippingOptions.combined_order) {
            console.log('Combined order detected, auto-selecting snail mail option');
            const combinedOptions = this.categoryShippingOptions.combined_order;
            
            if (combinedOptions.snail_mail) {
                this.updateCategorySelection('combined_order', 'snail_mail', combinedOptions.snail_mail.price);
                $('input[name="shipping_combined"][value="snail_mail"]').prop('checked', true);
            }
        } else if (this.categoryShippingOptions.photo_print_scrapbook_combined) {
            // Photo Print + Scrapbook combined order - use tier-based pricing
            console.log('Photo Print + Scrapbook combined order detected, auto-selecting snail mail option');
            const combinedOptions = this.categoryShippingOptions.photo_print_scrapbook_combined;
            
            if (Array.isArray(combinedOptions) && combinedOptions.length > 0) {
                const snailMailOption = combinedOptions.find(option => option.service === 'snail_mail');
                if (snailMailOption) {
                    this.updateCategorySelection('photo_print_scrapbook_combined', 'snail_mail', snailMailOption.price);
                    $('input[name="shipping_photo_print_scrapbook_combined"][value="snail_mail"]').prop('checked', true);
                }
            }
        } else {
            // Separate order - process each category
            Object.keys(this.categoryShippingOptions).forEach(category => {
                const options = this.categoryShippingOptions[category];
                let snailMailOption = null;
                
                console.log(`Processing category: ${category}`, options);
                
                if (Array.isArray(options) && options.length > 0) {
                    // Find snail mail option
                    snailMailOption = options.find(option => option.service === 'snail_mail');
                    console.log(`Found snail mail option for ${category}:`, snailMailOption);
                    // If no snail mail found, use first option as fallback
                    if (!snailMailOption && options.length > 0) {
                        snailMailOption = options[0];
                        console.log(`Using fallback option for ${category}:`, snailMailOption);
                    }
                } else if (options && typeof options === 'object') {
                    // Fallback for old format
                    snailMailOption = options.snail_mail || options.express;
                    console.log(`Using old format option for ${category}:`, snailMailOption);
                }
                
                if (snailMailOption && !this.categorySelections[category]) {
                    console.log(`Auto-selecting for ${category}:`, snailMailOption);
                    this.updateCategorySelection(category, snailMailOption.service, snailMailOption.price);
                    // Use the ensureRadioButtonChecked method to avoid conflicts
                    this.ensureRadioButtonChecked(category, snailMailOption.service);
                } else {
                    console.log(`Skipping ${category} - already selected or no option available`);
                }
            });
        }
        
        console.log('Final category selections before calculateTotalShipping:', this.categorySelections);
        console.log('=== END AUTO SELECT SHIPPING OPTIONS ===');
        
        // Don't call calculateTotalShipping here - let the session-based flow handle it
        // this.calculateTotalShipping();
    }

    // Auto-select snail mail for all categories (for session update)
    autoSelectAllSnailMail() {
        console.log('=== AUTO SELECT ALL SNAIL MAIL ===');
        console.log('Available categories:', Object.keys(this.categoryShippingOptions));
        
        this.categorySelections = {}; // Reset selections
        
        Object.keys(this.categoryShippingOptions).forEach(category => {
            const options = this.categoryShippingOptions[category];
            let snailMailOption = null;
            
            console.log(`Processing category: ${category}`, options);
            
            if (Array.isArray(options) && options.length > 0) {
                // Find snail mail option
                snailMailOption = options.find(option => option.service === 'snail_mail');
                console.log(`Found snail mail option for ${category}:`, snailMailOption);
                // If no snail mail found, use first option as fallback
                if (!snailMailOption && options.length > 0) {
                    snailMailOption = options[0];
                    console.log(`Using fallback option for ${category}:`, snailMailOption);
                }
            } else if (options && typeof options === 'object') {
                // Fallback for old format
                snailMailOption = options.snail_mail || options.express;
                console.log(`Using old format option for ${category}:`, snailMailOption);
            }
            
            if (snailMailOption) {
                console.log(`Auto-selecting snail mail for ${category}:`, snailMailOption);
                this.updateCategorySelection(category, snailMailOption.service, snailMailOption.price);
            } else {
                console.log(`No option available for ${category}`);
            }
        });
        
        // Update the test total shipping display after all selections are made
        this.updateTestTotalShipping();
        
        console.log('Final category selections for session update:', this.categorySelections);
        console.log('=== END AUTO SELECT ALL SNAIL MAIL ===');
    }

    // Update session with snail mail selections
    updateSessionWithSnailMail() {
        console.log('=== UPDATE SESSION WITH SNAIL MAIL ===');
        
        // Calculate total shipping from snail mail selections
        let totalShipping = 0;
        Object.values(this.categorySelections).forEach(selection => {
            if (selection.price) {
                totalShipping += parseFloat(selection.price);
            }
        });
        
        console.log('Total shipping from snail mail selections:', totalShipping);
        
        // Update session
        const shippingData = {
            service: 'snail_mail',
            carrier: 'Australia Post',
            price: totalShipping,
            category_selections: this.categorySelections
        };
        
        $.ajax({
            url: '/cart-shipping/save-shipping-session',
            method: 'POST',
            data: {
                shipping_data: shippingData,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Session updated with snail mail selections:', response);
            },
            error: (xhr, status, error) => {
                console.error('Error updating session with snail mail:', error);
            }
        });
        
        console.log('=== END UPDATE SESSION WITH SNAIL MAIL ===');
    }

    // Fetch updated cart total from session
    fetchUpdatedCartTotal() {
        console.log('=== FETCH UPDATED CART TOTAL ===');
        
        $.ajax({
            url: '/cart/get-updated-total', // You'll need to create this route
            method: 'GET',
            success: (response) => {
                console.log('Updated cart total response:', response);
                if (response.success) {
                    // Update the cart total display
                    this.currentTotal = parseFloat(response.total);
                    $('#cart-total').html(`<bdi><span>$</span>${this.currentTotal.toFixed(2)}</bdi>`);
                    
                    // Update shipping amount display
                    const shippingAmount = this.currentTotal - this.baseSubtotal;
                    $('#total-shipping-amount').text('$' + shippingAmount.toFixed(2));
                    $('#total-shipping-cost').show();
                    
                    // Display the shipping options with snail mail selected
                    this.displayCategoryShippingOptions(false);
                    
                    // Sync checked radio buttons with session
                    this.syncCheckedRadioButtonsWithSession();
                    
                    // Ensure session consistency
                    this.ensureSessionConsistency();
                    
                    // Update test total shipping display
                    this.updateTestTotalShipping();
                    
                    // Mark initial load as complete
                    this.isInitialLoad = false;
                    
                    console.log('Cart total updated to:', this.currentTotal);
                    console.log('Initial load completed, user changes will now update totals');
                }
            },
            error: (xhr, status, error) => {
                console.error('Error fetching updated cart total:', error);
                // Fallback: display shipping options normally
                this.displayCategoryShippingOptions(false);
                
                // Mark initial load as complete even on error
                this.isInitialLoad = false;
            }
        });
        
        console.log('=== END FETCH UPDATED CART TOTAL ===');
    }

    // Load existing session data and apply it
    loadExistingSessionData() {
        console.log('=== LOAD EXISTING SESSION DATA ===');
        
        $.ajax({
            url: '/cart-shipping/get-session-shipping',
            method: 'GET',
            success: (response) => {
                console.log('Session shipping data:', response);
                console.log('Response success:', response.success);
                console.log('Response shipping:', response.shipping);
                console.log('Response shipping category_selections:', response.shipping?.category_selections);
                console.log('Response shipping keys:', response.shipping ? Object.keys(response.shipping) : 'No shipping data');
                
                if (response.success && response.shipping && response.shipping.category_selections) {
                    // Use existing session data
                    this.categorySelections = response.shipping.category_selections;
                    console.log('Loaded existing category selections:', this.categorySelections);
                    console.log('Category selections keys:', Object.keys(this.categorySelections));
                    
                    // Display shipping options with existing selections
                    this.displayCategoryShippingOptions(false);
                    
                    // Wait a bit for DOM to be ready, then force refresh radio display
                    setTimeout(() => {
                        this.forceRefreshRadioDisplay();
                        console.log('Radio buttons refreshed from session data');
                    }, 200);
                    
                    // Update test total shipping with session data
                    this.updateTestTotalShipping();
                    
                    // Update session with existing data to ensure consistency
                    this.updateSessionWithExistingData(response.shipping);
                    
                    // Fetch updated cart total from session
                    this.fetchUpdatedCartTotal();
                    
                    // Mark that we have session data to prevent auto-selection
                    this.hasSessionData = true;
                } else {
                    // No existing session data, use default snail mail
                    console.log('No existing session data, using default snail mail');
                    console.log('Response shipping is null/undefined:', !response.shipping);
                    console.log('Response shipping category_selections is null/undefined:', !response.shipping?.category_selections);
                    this.autoSelectAllSnailMail();
                    this.updateSessionWithSnailMail();
                    this.fetchUpdatedCartTotal();
                }
            },
            error: (xhr, status, error) => {
                console.error('Error loading session data:', error);
                // Fallback to default snail mail
                console.log('Error loading session, using default snail mail');
                this.autoSelectAllSnailMail();
                this.updateSessionWithSnailMail();
                this.fetchUpdatedCartTotal();
            }
        });
        
        console.log('=== END LOAD EXISTING SESSION DATA ===');
    }

    // Update session with existing data
    updateSessionWithExistingData(shippingData) {
        console.log('=== UPDATE SESSION WITH EXISTING DATA ===');
        
        $.ajax({
            url: '/cart-shipping/save-shipping-session',
            method: 'POST',
            data: {
                shipping_data: shippingData,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Session updated with existing data:', response);
            },
            error: (xhr, status, error) => {
                console.error('Error updating session with existing data:', error);
            }
        });
        
        console.log('=== END UPDATE SESSION WITH EXISTING DATA ===');
    }

    // Sync checked radio buttons with session
    syncCheckedRadioButtonsWithSession() {
        console.log('=== SYNC CHECKED RADIO BUTTONS WITH SESSION ===');
        
        // Get all checked radio buttons
        const checkedRadios = $('.category-shipping-option:checked');
        console.log('Found checked radio buttons:', checkedRadios.length);
        
        // Update category selections based on checked radio buttons
        this.categorySelections = {};
        let totalShipping = 0;
        
        checkedRadios.each((index, radio) => {
            const $radio = $(radio);
            const category = $radio.data('category');
            const service = $radio.val();
            const price = parseFloat($radio.data('price') || 0);
            
            console.log(`Radio ${index}: ${category} - ${service} - $${price}`);
            
            this.categorySelections[category] = {
                service: service,
                price: price
            };
            
            totalShipping += price;
        });
        
        console.log('Updated category selections:', this.categorySelections);
        console.log('Total shipping from checked radios:', totalShipping);
        
        // Update session with current selections
        const shippingData = {
            service: 'mixed', // Since we have category-wise selections
            carrier: 'Australia Post',
            price: totalShipping,
            category_selections: this.categorySelections
        };
        
        $.ajax({
            url: '/cart-shipping/save-shipping-session',
            method: 'POST',
            data: {
                shipping_data: shippingData,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Session updated with checked radio selections:', response);
            },
            error: (xhr, status, error) => {
                console.error('Error updating session with checked radios:', error);
            }
        });
        
        console.log('=== END SYNC CHECKED RADIO BUTTONS WITH SESSION ===');
    }

    updateCategorySelection(category, service, price) {
        this.categorySelections[category] = {
            service: service,
            price: parseFloat(price)
        };
        console.log(`Updated ${category}: ${service} $${price}`);
        console.log('Current selections:', this.categorySelections);
        
        // Update test total shipping in real-time
        this.updateTestTotalShipping();
    }
    
    ensureRadioButtonChecked(category, service) {
        // Uncheck all radio buttons for this category first
        $(`input[name="shipping_${category}"]`).prop('checked', false);
        
        // Check only the selected radio button for this category
        $(`input[name="shipping_${category}"][value="${service}"]`).prop('checked', true);
        
        console.log(`Ensured ${category} has ${service} selected`);
        
        // Debug: Log all radio button states for this category
        $(`input[name="shipping_${category}"]`).each(function() {
            console.log(`Radio button ${category}: name="${$(this).attr('name')}", value="${$(this).val()}", checked=${$(this).prop('checked')}`);
        });
    }

    calculateTotalShipping() {
        console.log('=== CALCULATE TOTAL SHIPPING ===');
        console.log('Category selections:', this.categorySelections);
        
        if (Object.keys(this.categorySelections).length === 0) {
            console.log('No category selections, hiding shipping cost');
            $('#total-shipping-cost').hide();
            return;
        }

        // Calculate total directly from selected options
        let totalAmount = 0;
        console.log('Calculating total from selections:', this.categorySelections);
        
        Object.values(this.categorySelections).forEach(selection => {
            if (selection.price) {
                const price = parseFloat(selection.price);
                totalAmount += price;
                console.log(`Adding ${selection.service}: $${price} (Total so far: $${totalAmount})`);
            }
        });

        const currentShippingAmount = parseFloat($('#total-shipping-amount').text().replace('$', '') || 0);
        
        console.log('Current shipping amount from DOM:', currentShippingAmount);
        console.log('Calculated new shipping amount:', totalAmount);
        console.log('Difference:', Math.abs(totalAmount - currentShippingAmount));
        
        $('#total-shipping-amount').text('$' + totalAmount.toFixed(2));
        $('#total-shipping-cost').show();
        
        // Update the test total shipping display as well
        this.updateTestTotalShipping();
        
        // Store in session for checkout
        console.log('Updating Laravel session with shipping data...');
        this.saveShippingToSession('mixed', totalAmount);
        
        // Always fetch updated total from Laravel session instead of calculating locally
        this.fetchUpdatedCartTotal();
        
        console.log('Final total calculated:', totalAmount);
        console.log('=== END CALCULATE TOTAL SHIPPING ===');
    }

    getMostSelectedService() {
        const serviceCounts = { snail_mail: 0, express: 0 };
        
        Object.values(this.categorySelections).forEach(selection => {
            if (selection.service) {
                serviceCounts[selection.service]++;
            }
        });
        
        return serviceCounts.express > serviceCounts.snail_mail ? 'express' : 'snail_mail';
    }

    saveShippingToSession(service, amount) {
        const shippingData = {
            service: service,
            carrier: 'Australia Post',
            price: amount,
            category_selections: this.categorySelections
        };
        
        // Store in session via AJAX
        $.ajax({
            url: '/cart-shipping/save-shipping-session',
            method: 'POST',
            data: {
                shipping_data: shippingData,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Shipping session saved:', response);
            },
            error: (xhr, status, error) => {
                console.error('Error saving shipping session:', error);
            }
        });
    }

    updateCartTotal() {
        // Always fetch the total from Laravel session instead of calculating locally
        this.fetchUpdatedCartTotal();
    }

    // Method to check radio buttons based on session data
    checkRadioButtonsFromSession() {
        console.log('=== CHECK RADIO BUTTONS FROM SESSION ===');
        console.log('Current category selections:', this.categorySelections);
        
        let checkedCount = 0;
        Object.keys(this.categorySelections).forEach(category => {
            const selection = this.categorySelections[category];
            const service = selection.service;
            const price = selection.price;
            
            console.log(`Checking radio for ${category}: ${service} $${price}`);
            
            // Find and check the appropriate radio button
            const radioSelector = `input[name="shipping_${category}"][value="${service}"]`;
            const $radio = $(radioSelector);
            
            console.log(`Looking for radio: ${radioSelector}`);
            console.log(`Found ${$radio.length} radio buttons`);
            
            if ($radio.length > 0) {
                $radio.prop('checked', true);
                console.log(`✓ Checked radio for ${category}: ${service}`);
                checkedCount++;
            } else {
                console.log(`✗ Radio not found for ${category}: ${service}`);
                // Try alternative selector
                const altSelector = `input[data-category="${category}"][value="${service}"]`;
                const $altRadio = $(altSelector);
                if ($altRadio.length > 0) {
                    $altRadio.prop('checked', true);
                    console.log(`✓ Checked radio using alternative selector for ${category}: ${service}`);
                    checkedCount++;
                } else {
                    console.log(`✗ Alternative radio not found for ${category}: ${service}`);
                }
            }
        });
        
        console.log(`Total radio buttons checked: ${checkedCount}`);
        console.log('=== END CHECK RADIO BUTTONS FROM SESSION ===');
    }

    // Method to sync with external total updates (called by existing cart JavaScript)
    syncWithExternalTotal() {
        const cartTotalText = $('#cart-total').text();
        const newTotal = parseFloat(cartTotalText.replace(/[^0-9.-]+/g, '') || 0);
        
        if (newTotal !== this.currentTotal) {
            console.log('=== SYNC WITH EXTERNAL TOTAL ===');
            console.log('External total:', newTotal);
            console.log('Current total:', this.currentTotal);
            console.log('Updating current total to match external total');
            this.currentTotal = newTotal;
            console.log('=== END SYNC ===');
        }
    }

    // Method to update session with current selections (called immediately when user changes selection)
    updateSessionWithCurrentSelections() {
        console.log('=== UPDATE SESSION WITH CURRENT SELECTIONS ===');
        console.log('Current categorySelections before update:', this.categorySelections);
        
        if (Object.keys(this.categorySelections).length > 0) {
            let totalShipping = 0;
            Object.values(this.categorySelections).forEach(selection => {
                if (selection.price) {
                    totalShipping += parseFloat(selection.price);
                }
            });
            
            const shippingData = {
                service: 'mixed',
                carrier: 'Australia Post',
                price: totalShipping,
                category_selections: this.categorySelections
            };
            
            console.log('Updating session with current selections:', shippingData);
            console.log('Category selections being saved:', this.categorySelections);
            
            $.ajax({
                url: '/cart-shipping/save-shipping-session',
                method: 'POST',
                data: {
                    shipping_data: shippingData,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    console.log('Session updated with current selections:', response);
                    console.log('Session save successful, data saved:', shippingData);
                },
                error: (xhr, status, error) => {
                    console.error('Error updating session with current selections:', error);
                }
            });
        } else {
            console.log('No category selections to update in session');
        }
        
        console.log('=== END UPDATE SESSION WITH CURRENT SELECTIONS ===');
    }

    // Method to ensure session is always updated with current state (called on page load)
    ensureSessionConsistency() {
        console.log('=== ENSURE SESSION CONSISTENCY ===');
        
        // If we have category selections, update session
        if (Object.keys(this.categorySelections).length > 0) {
            let totalShipping = 0;
            Object.values(this.categorySelections).forEach(selection => {
                if (selection.price) {
                    totalShipping += parseFloat(selection.price);
                }
            });
            
            const shippingData = {
                service: 'mixed',
                carrier: 'Australia Post',
                price: totalShipping,
                category_selections: this.categorySelections
            };
            
            $.ajax({
                url: '/cart-shipping/save-shipping-session',
                method: 'POST',
                data: {
                    shipping_data: shippingData,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => {
                    console.log('Session consistency ensured:', response);
                },
                error: (xhr, status, error) => {
                    console.error('Error ensuring session consistency:', error);
                }
            });
        }
        
        console.log('=== END ENSURE SESSION CONSISTENCY ===');
    }

    // Method to clear shipping session when pickup is selected
    clearShippingSession() {
        console.log('=== CLEAR SHIPPING SESSION ===');
        
        // Clear local selections
        this.categorySelections = {};
        this.hasSessionData = false;
        
        // Clear session via AJAX
        $.ajax({
            url: '/cart-shipping/clear-shipping-session',
            method: 'POST',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log('Shipping session cleared:', response);
                // Hide shipping options
                $('#category-shipping-options').hide();
                $('#total-shipping-cost').hide();
            },
            error: (xhr, status, error) => {
                console.error('Error clearing shipping session:', error);
            }
        });
        
        console.log('=== END CLEAR SHIPPING SESSION ===');
    }

    // Method to restore shipping session when shipping is selected
    restoreShippingSession() {
        console.log('=== RESTORE SHIPPING SESSION ===');
        
        // Show shipping options
        $('#category-shipping-options').show();
        
        // Reload shipping options and session data
        this.calculateCategoryShippingAndUpdateSession(true);
        
        console.log('=== END RESTORE SHIPPING SESSION ===');
    }

    // Method to update test total shipping in real-time
    updateTestTotalShipping() {
        console.log('=== UPDATE TEST TOTAL SHIPPING ===');
        
        let totalShipping = 0;
        Object.values(this.categorySelections).forEach(selection => {
            if (selection.price) {
                totalShipping += parseFloat(selection.price);
            }
        });
        
        console.log('Calculated test total shipping:', totalShipping);
        console.log('Category selections used:', this.categorySelections);
        
        // Update the test total display
        const $testTotalElement = $('.test-total-amount');
        console.log('Test total element found:', $testTotalElement.length);
        console.log('Test total element text before update:', $testTotalElement.text());
        
        $testTotalElement.text('$' + totalShipping.toFixed(2));
        
        console.log('Test total element text after update:', $testTotalElement.text());
        console.log('=== END UPDATE TEST TOTAL SHIPPING ===');
    }

    // Method to force refresh radio button display
    forceRefreshRadioDisplay() {
        console.log('=== FORCE REFRESH RADIO DISPLAY ===');
        
        // Uncheck all radio buttons first
        $('.category-shipping-option').prop('checked', false);
        
        // Then check the ones from session
        this.checkRadioButtonsFromSession();
        
        // Update test total after checking radio buttons
        this.updateTestTotalShipping();
        
        console.log('=== END FORCE REFRESH RADIO DISPLAY ===');
    }
}

// Initialize when document is ready
$(document).ready(function() {
    console.log('=== CATEGORY SHIPPING INITIALIZATION ===');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Document ready, creating CategoryShippingCalculator...');
    
    try {
        window.categoryShippingCalculator = new CategoryShippingCalculator();
        console.log('CategoryShippingCalculator created successfully');
    } catch (error) {
        console.error('Error creating CategoryShippingCalculator:', error);
    }
    
    console.log('=== END CATEGORY SHIPPING INITIALIZATION ===');
}); 
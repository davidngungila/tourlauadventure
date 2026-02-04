@extends('admin.layouts.app')

@section('title', (isset($tour) ? 'Edit' : 'Create') . ' Last Minute Deal - Lau Paradise Adventures')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="ri-time-line me-2"></i>
                        {{ isset($tour) ? 'Edit' : 'Create' }} Last Minute Deal
                    </h4>
                    <a href="{{ route('admin.tours.last-minute-deals') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line me-1"></i>Back
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($tour) ? route('admin.tours.last-minute-deals.update', $tour->id) : route('admin.tours.last-minute-deals.store') }}">
                        @csrf
                        @if(isset($tour))
                            @method('PUT')
                        @endif

                        @if(isset($tour))
                            <!-- Edit Mode - Show Tour Info -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h5><i class="ri-information-line me-2"></i>Editing Deal for: <strong>{{ $tour->name }}</strong></h5>
                                        <p class="mb-0">
                                            <strong>Tour Code:</strong> {{ $tour->tour_code }} | 
                                            <strong>Destination:</strong> {{ $tour->destination->name ?? 'N/A' }} |
                                            <strong>Original Price:</strong> ${{ number_format($tour->last_minute_original_price ?? $tour->price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Create Mode - Select Tour -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label class="form-label">Select Tour <span class="text-danger">*</span></label>
                                    <select name="tour_id" class="form-select @error('tour_id') is-invalid @enderror" required>
                                        <option value="">-- Select a Tour --</option>
                                        @foreach($tours ?? [] as $t)
                                            <option value="{{ $t->id }}" {{ old('tour_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }} - {{ $t->destination->name ?? 'N/A' }} 
                                                ({{ $t->duration_days }} Days) - ${{ number_format($t->starting_price ?? $t->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tour_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Only active, published tours that are not already last-minute deals are shown.</small>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Percentage <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="discount_percentage" 
                                           class="form-control @error('discount_percentage') is-invalid @enderror" 
                                           value="{{ old('discount_percentage', $tour->last_minute_discount_percentage ?? '') }}" 
                                           min="1" 
                                           max="100" 
                                           step="0.01"
                                           required
                                           id="discountPercentage">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter discount percentage (1-100%)</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deal Expires At <span class="text-danger">*</span></label>
                                <input type="datetime-local" 
                                       name="expires_at" 
                                       class="form-control @error('expires_at') is-invalid @enderror" 
                                       value="{{ old('expires_at', isset($tour) && $tour->last_minute_deal_expires_at ? $tour->last_minute_deal_expires_at->format('Y-m-d\TH:i') : '') }}" 
                                       required
                                       min="{{ date('Y-m-d\TH:i') }}">
                                @error('expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Select when this deal expires</small>
                            </div>
                        </div>

                        @if(isset($tour))
                            <!-- Price Preview for Edit Mode -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="mb-3">Price Preview</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Original Price:</strong>
                                                    <p class="h5 mb-0">$<span id="originalPrice">{{ number_format($tour->last_minute_original_price ?? $tour->price, 2) }}</span></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Discount:</strong>
                                                    <p class="h5 mb-0 text-danger"><span id="discountAmount">0</span>%</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Deal Price:</strong>
                                                    <p class="h5 mb-0 text-success">$<span id="dealPrice">0.00</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Price Preview for Create Mode -->
                            <div class="row mb-4" id="pricePreview" style="display: none;">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="mb-3">Price Preview</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Original Price:</strong>
                                                    <p class="h5 mb-0">$<span id="originalPrice">0.00</span></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Discount:</strong>
                                                    <p class="h5 mb-0 text-danger"><span id="discountAmount">0</span>%</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Deal Price:</strong>
                                                    <p class="h5 mb-0 text-success">$<span id="dealPrice">0.00</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i>
                                    {{ isset($tour) ? 'Update' : 'Create' }} Deal
                                </button>
                                <a href="{{ route('admin.tours.last-minute-deals') }}" class="btn btn-secondary">
                                    <i class="ri-close-line me-1"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const discountInput = document.getElementById('discountPercentage');
    const tourSelect = document.querySelector('[name="tour_id"]');
    const originalPriceSpan = document.getElementById('originalPrice');
    const discountAmountSpan = document.getElementById('discountAmount');
    const dealPriceSpan = document.getElementById('dealPrice');
    const pricePreview = document.getElementById('pricePreview');
    
    function calculatePrice() {
        let originalPrice = 0;
        
        if (tourSelect && tourSelect.value) {
            // Get price from selected option text or fetch via AJAX
            const optionText = tourSelect.options[tourSelect.selectedIndex].text;
            const priceMatch = optionText.match(/\$([\d,]+\.?\d*)/);
            if (priceMatch) {
                originalPrice = parseFloat(priceMatch[1].replace(/,/g, ''));
            }
        } else if (originalPriceSpan) {
            // Edit mode - use existing price
            originalPrice = parseFloat(originalPriceSpan.textContent.replace(/,/g, ''));
        }
        
        const discount = parseFloat(discountInput.value) || 0;
        const discountAmount = (originalPrice * discount) / 100;
        const dealPrice = originalPrice - discountAmount;
        
        if (originalPriceSpan) originalPriceSpan.textContent = originalPrice.toFixed(2);
        if (discountAmountSpan) discountAmountSpan.textContent = discount.toFixed(1);
        if (dealPriceSpan) dealPriceSpan.textContent = dealPrice.toFixed(2);
        
        if (pricePreview && tourSelect && tourSelect.value) {
            pricePreview.style.display = 'block';
        }
    }
    
    if (discountInput) {
        discountInput.addEventListener('input', calculatePrice);
    }
    
    if (tourSelect) {
        tourSelect.addEventListener('change', calculatePrice);
    }
    
    // Initial calculation
    calculatePrice();
});
</script>
@endsection













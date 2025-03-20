{{-- filters.blade.php --}}
@php
    // Initialize counters
    $totalBrands = 0;
    $totalModels = 0;
    $hasYear = 0;
    
    // Count all items first (total counts)
    if(request('brand')) {
        $totalBrands = is_array(request('brand')) ? count(request('brand')) : 1;
    }
    
    if(request('model')) {
        $totalModels = is_array(request('model')) ? count(request('model')) : 1;
    }
    
    if(request('year')) {
        $hasYear = 1;
    }
    
    // Calculate total items and set the reference variable
    $totalItems = $totalBrands + $totalModels + $hasYear;
    
    // Display logic
    $isShowingAll = request('show_all', false);
    $maxSpans = $isShowingAll ? PHP_INT_MAX : 10;
    $displayedCount = 0;
@endphp


<div class="d-flex align-items-center flex-wrap gap-3" id="filters-container">
    {{-- Brand Filters --}}
    @if(request('brand'))
        @foreach((array)request('brand') as $brandSlug)
            @if($displayedCount < $maxSpans)
                <span class="filter-badge">
                    {{ $brandSlug }}
                    <a href="#" class="remove-filter">×</a>
                </span>
                @php $displayedCount++; @endphp
            @endif
        @endforeach
    @endif

    {{-- Model Filters --}}
    @if(request('model'))
        @foreach((array)request('model') as $modelSlug)
            @if($displayedCount < $maxSpans)
                <span class="filter-badge">
                    {{ $modelSlug }}
                    <a href="#" class="remove-filter">×</a>
                </span>
                @php $displayedCount++; @endphp
            @endif
        @endforeach
    @endif

    {{-- Year Filter --}}
    @if(request('year') && $displayedCount < $maxSpans)
        <span class="filter-badge">
            {{ request('year') }}
            <a href="#" class="remove-filter">×</a>
        </span>
        @php $displayedCount++; @endphp
    @endif
</div>

@if($totalItems > 10 && !$isShowingAll)
    <button id="show-more-filters" class="show-more-btn">
        Show More ({{ $totalItems - 10 }} more)
    </button>
@endif
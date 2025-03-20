<!-- <div class="row">
    <div class="col-lg-12">
        <div class="next-prev-btn">
            <ul>
                @if ($paginator->onFirstPage())

                @else
                    <li><a href="{{ $paginator->previousPageUrl() }}" class="two">{{ __('translate.Prev') }}</a></li>
                @endif

                @foreach ($elements as $element)
                    @if (!is_array($element))
                        <li><a href="javascript:;">...</a></li>
                    @else
                        @if (count($element) < 2)
                        @else
                            @foreach ($element as $key => $el)
                                @if ($key == $paginator->currentPage())
                                    <li ><a class="active" href="javascript::void()">{{ $key }}</a></li>
                                @else
                                    <li><a href="{{ $el }}">{{ $key }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li><a href="{{ $paginator->nextPageUrl() }}" class="two">{{ __('translate.Next') }}</a></li>
                @endif

            </ul>
        </div>
    </div>
</div> -->

<div class="row pb-5 ">   
    <div class="col-lg-12">
        <div class="next-prev-btn">
            <ul class="pagination pagination-sm">
                @if ($paginator->onFirstPage())
                @else
                    <li class="page-item d-inline-block">
                        <a href="{{ $paginator->previousPageUrl() }}" class="page-link fs-6">{{ __('translate.Prev') }}</a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    @if (!is_array($element))
                        <li class="page-item d-inline-block"><a href="javascript:;" class="page-link fs-6">...</a></li>
                    @else
                        @if (count($element) < 2)
                        @else
                            @foreach ($element as $key => $el)
                                @if ($key == $paginator->currentPage())
                                    <li class="page-item active d-inline-block"><a class="page-link fs-6" href="javascript::void()">{{ $key }}</a></li>
                                @else
                                    <li class="page-item d-inline-block"><a href="{{ $el }}" class="page-link fs-6">{{ $key }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <li class="page-item d-inline-block"><a href="{{ $paginator->nextPageUrl() }}" class="page-link fs-6">{{ __('translate.Next') }}</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>



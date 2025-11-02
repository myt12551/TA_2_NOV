<div {{ $attributes->merge(['class' => 'card shadow-sm border-0 mb-4']) }}>
    @isset($header)
        <div class="card-header bg-white border-bottom">
            {{ $header }}
        </div>
    @endisset
    
    <div class="card-body">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="card-footer bg-white border-top">
            {{ $footer }}
        </div>
    @endisset
</div>
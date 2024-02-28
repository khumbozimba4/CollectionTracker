<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary me-2']) }}>
    {{ $slot }}
</button>

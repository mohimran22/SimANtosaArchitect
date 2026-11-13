{{-- Tempel di <head> layout utama. Hanya aktif jika GA4_MEASUREMENT_ID diisi. --}}
@if (config('services.ga4.measurement_id'))
<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.measurement_id') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', @json(config('services.ga4.measurement_id')));
</script>
@endif
@if(session('success'))
    <input type="hidden" id="successMessage" value="{{ session('success') }}">
@endif

@if(session('error'))
    <input type="hidden" id="errorMessage" value="{{ session('error') }}">
@endif

@if (session('success'))
  <div
    class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm"
  >
    {{ session('success') }}
  </div>
@endif

@if (session('error'))
  <div
    class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm"
  >
    {{ session('error') }}
  </div>
@endif

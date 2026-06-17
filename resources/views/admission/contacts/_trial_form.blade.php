<form method="POST" action="{{ route('admission.contacts.trial.issue', $contact) }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Assign to Batch</label>
        <select name="batch_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-orange-500 bg-slate-50">
            <option value="">Select a batch...</option>
            @foreach($batches as $batch)
            <option value="{{ $batch->id }}">{{ $batch->course->name }} — {{ $batch->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Trial Login Email</label>
        <input type="email" name="trial_email" required
               value="trial_{{ mt_rand(1000,9999) }}@toppershope.com"
               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-orange-500 font-mono">
        <p class="text-[10px] text-slate-400 mt-1">A temporary password will be generated automatically.</p>
    </div>
    <div>
        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Duration (Days)</label>
        <input type="number" name="days" value="5" min="1" max="30" required
               class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm outline-none focus:border-indigo-500 focus:ring-1 focus:ring-orange-500">
    </div>
    <button type="submit"
            class="w-full py-2.5 bg-orange-50 text-orange-600 font-black text-sm rounded-xl hover:bg-orange-100 transition border border-indigo-200">
        Issue Trial Access
    </button>
</form>

@extends('layouts.app')

@section('title', $destination->title)
@section('page-title', $destination->title)
@section('page-subtitle', 'Detail & Rencana Perjalanan')


@section('header-actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('destinations.edit', $destination) }}"
           class="flex items-center gap-2 px-4 py-2 rounded-full border border-sand-400/30 text-sand-400 font-body text-sm hover:bg-sand-400 hover:text-forest-900 transition-all duration-200">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        <a href="{{ route('destinations.index') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-full border border-white/10 text-white/60 font-body text-sm hover:border-white/20 hover:text-white transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6 animate-fade-in">

    {{-- Destination hero card --}}
    <div class="card rounded-3xl overflow-hidden">
        <div class="relative h-56 md:h-72">
            @if($destination->image)
                <img src="{{ asset('storage/' . $destination->image) }}"
                     alt="{{ $destination->title }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-forest-700 via-forest-800 to-forest-900 flex items-center justify-center">
                    <span class="text-8xl">🏝️</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-forest-900 via-forest-900/30 to-transparent"></div>

            {{-- Overlay info --}}
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <div class="flex items-end justify-between">
                    <div>
                        <h2 class="font-display text-3xl font-black">{{ $destination->title }}</h2>
                        <p class="text-white/50 font-body text-sm mt-1">
                            {{ \Carbon\Carbon::parse($destination->departure_date)->format('d F Y') }}
                        </p>
                    </div>
                    @if($destination->status)
                        <span class="px-3 py-1.5 rounded-full bg-green-500/25 border border-green-500/40 text-green-400 text-sm font-body">✓ Tercapai</span>
                    @else
                        <span class="px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-white/60 text-sm font-body">○ Belum Tercapai</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats strip --}}
        <div class="grid grid-cols-3 divide-x divide-white/5 border-t border-white/5">
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Durasi</p>
                <p class="font-display text-xl font-bold">{{ $destination->duration }} <span class="text-sm font-body text-white/50">Hari</span></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Budget</p>
                <p class="font-display text-xl font-bold text-sand-400">{{ number_format($destination->budget / 1000000, 1) }} <span class="text-sm font-body text-sand-400/60">Juta</span></p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[10px] text-white/30 font-body uppercase tracking-widest mb-1">Rencana</p>
                <p class="font-display text-xl font-bold">{{ $destination->plans->count() }} <span class="text-sm font-body text-white/50">Hari</span></p>
            </div>
        </div>
    </div>

    {{-- Travel Plans --}}
    <div x-data="plansManager()" x-init="loadPlans()">

        {{-- Plans header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display text-xl font-bold">Rencana Perjalanan</h3>
                <p class="text-white/40 font-body text-sm mt-0.5">Jadwal kegiatan per hari</p>
            </div>
            <button @click="showAddModal = true"
                    class="flex items-center gap-2 px-4 py-2 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm hover:bg-sand-300 transition-all hover:shadow-lg hover:shadow-sand-400/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Rencana
            </button>
        </div>

        {{-- Plans timeline --}}
        @if($destination->plans->isEmpty())
        <div class="card rounded-3xl p-14 text-center">
            <span class="text-5xl block mb-4 animate-float">📅</span>
            <h4 class="font-display text-xl font-bold mb-2">Belum ada rencana perjalanan</h4>
            <p class="text-white/40 font-body text-sm mb-6 max-w-xs mx-auto">Tambahkan jadwal kegiatan untuk setiap hari perjalananmu.</p>
            <button @click="showAddModal = true"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-sand-400/20 border border-sand-400/30 text-sand-400 font-body text-sm hover:bg-sand-400 hover:text-forest-900 transition-all">
                + Tambah Rencana Pertama
            </button>
        </div>
        @else
        <div class="space-y-4">
            @foreach($destination->plans->groupBy('hari') as $hari => $activities)
            <div class="card rounded-2xl overflow-hidden">
                {{-- Day header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sand-400/15 border border-sand-400/25 flex items-center justify-center">
                            <span class="font-display font-black text-sand-400 text-sm">{{ $hari }}</span>
                        </div>
                        <div>
                            <h4 class="font-display font-bold">Hari ke-{{ $hari }}</h4>
                            <p class="text-white/30 text-xs font-body">
                                {{ \Carbon\Carbon::parse($destination->departure_date)->addDays($hari - 1)->format('l, d M Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-body text-white/30">{{ $activities->count() }} kegiatan</span>
                        <button
                            @click="openAddActivity({{ $hari }})"
                            class="w-7 h-7 rounded-lg bg-sand-400/15 hover:bg-sand-400 hover:text-forest-900 text-sand-400 flex items-center justify-center transition-all duration-200 text-xs"
                            title="Tambah kegiatan di hari ini">
                            +
                        </button>
                    </div>
                </div>

                {{-- Activities --}}
                <div class="divide-y divide-white/5">
                    @foreach($activities->sortBy('jam') as $plan)
                    <div class="flex items-start gap-4 px-5 py-4 group hover:bg-white/2 transition-colors">
                        {{-- Time --}}
                        <div class="shrink-0 text-right w-12">
                            <span class="text-sand-400 font-body text-sm font-medium">{{ \Carbon\Carbon::parse($plan->jam)->format('H:i') }}</span>
                        </div>

                        {{-- Dot --}}
                        <div class="shrink-0 mt-1.5 relative">
                            <div class="w-2.5 h-2.5 rounded-full bg-sand-400/50 border border-sand-400"></div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-body text-sm text-white/80 leading-relaxed">{{ $plan->kegiatan }}</p>
                            @if($plan->lokasi)
                            <p class="text-white/30 text-xs font-body mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                                {{ $plan->lokasi }}
                            </p>
                            @endif
                        </div>

                        {{-- Actions (visible on hover) --}}
                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-all duration-200 shrink-0">
                            <button @click="openEditPlan({{ $plan->toJson() }})"
                                    class="w-7 h-7 rounded-lg bg-white/5 hover:bg-sand-400/20 hover:text-sand-400 text-white/40 flex items-center justify-center transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button @click="deletePlan({{ $plan->id }})"
                                    class="w-7 h-7 rounded-lg bg-white/5 hover:bg-red-500/20 hover:text-red-400 text-white/40 flex items-center justify-center transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ════ MODAL: Add/Edit Plan ════ --}}
        <div x-show="showAddModal || showEditModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="closeModals()"></div>

            {{-- Modal box --}}
            <div class="relative w-full max-w-md card rounded-3xl p-6 space-y-5 shadow-2xl"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="flex items-center justify-between">
                    <h3 class="font-display text-lg font-bold" x-text="showEditModal ? 'Edit Kegiatan' : 'Tambah Kegiatan'"></h3>
                    <button @click="closeModals()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/50 hover:text-white transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitPlan()">
                    <input type="hidden" name="destination_id" value="{{ $destination->id }}">

                    <div class="space-y-4">
                        {{-- Hari --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-body text-white/50 uppercase tracking-wider">Hari ke- *</label>
                            <input type="number" x-model="form.hari" min="1" max="{{ $destination->duration }}"
                                   placeholder="1"
                                   class="input-field w-full px-4 py-3 rounded-xl font-body text-sm">
                            <p class="text-white/25 text-xs font-body">Maks. {{ $destination->duration }} hari</p>
                        </div>

                        {{-- Jam --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-body text-white/50 uppercase tracking-wider">Jam *</label>
                            <input type="time" x-model="form.jam"
                                   class="input-field w-full px-4 py-3 rounded-xl font-body text-sm"
                                   style="color-scheme: dark;">
                        </div>

                        {{-- Kegiatan --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-body text-white/50 uppercase tracking-wider">Kegiatan *</label>
                            <textarea x-model="form.kegiatan" rows="3"
                                      placeholder="Contoh: Berangkat naik kereta menuju Malang"
                                      class="input-field w-full px-4 py-3 rounded-xl font-body text-sm resize-none"></textarea>
                        </div>

                        {{-- Lokasi --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-body text-white/50 uppercase tracking-wider">Lokasi (opsional)</label>
                            <input type="text" x-model="form.lokasi"
                                   placeholder="Stasiun Gambir, Jakarta"
                                   class="input-field w-full px-4 py-3 rounded-xl font-body text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <button type="button" @click="closeModals()"
                                class="text-white/40 font-body text-sm hover:text-white transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex items-center gap-2 px-6 py-2.5 rounded-full bg-sand-400 text-forest-900 font-body font-medium text-sm hover:bg-sand-300 transition-all hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span x-text="showEditModal ? 'Simpan Perubahan' : 'Tambah Kegiatan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function plansManager() {
    return {
        showAddModal: false,
        showEditModal: false,
        editingId: null,
        form: { hari: 1, jam: '07:00', kegiatan: '', lokasi: '' },

        loadPlans() {
            // Plans are loaded server-side, this manages modals
        },

        openAddActivity(hari = 1) {
            this.form = { hari: hari, jam: '07:00', kegiatan: '', lokasi: '' };
            this.editingId = null;
            this.showAddModal = true;
            this.showEditModal = false;
        },

        openEditPlan(plan) {
            this.form = {
                hari: plan.hari,
                jam: plan.jam ? plan.jam.substring(0, 5) : '07:00',
                kegiatan: plan.kegiatan,
                lokasi: plan.lokasi || ''
            };
            this.editingId = plan.id;
            this.showEditModal = true;
            this.showAddModal = false;
        },

        closeModals() {
            this.showAddModal = false;
            this.showEditModal = false;
            this.editingId = null;
        },

        async submitPlan() {
            const destinationId = {{ $destination->id }};
            const url = this.editingId
                ? `/plans/${this.editingId}`
                : `/plans`;

            const method = this.editingId ? 'PUT' : 'POST';
            const token = document.querySelector('meta[name="csrf-token"]')?.content
                        || '{{ csrf_token() }}';

            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ...this.form, destination_id: destinationId })
                });

                if (res.ok) {
                    window.location.reload();
                } else {
                    const err = await res.json();
                    alert('Terjadi kesalahan: ' + (err.message || 'Coba lagi'));
                }
            } catch(e) {
                // Fallback: submit via form if fetch fails
                window.location.reload();
            }
        },

        async deletePlan(id) {
            if (!confirm('Hapus kegiatan ini?')) return;
            const token = '{{ csrf_token() }}';
            try {
                const res = await fetch(`/plans/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                });
                if (res.ok) window.location.reload();
            } catch(e) {
                window.location.reload();
            }
        }
    }
}
</script>
@endpush

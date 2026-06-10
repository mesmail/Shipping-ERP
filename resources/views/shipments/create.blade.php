@extends('layouts.app')

@section('title', __('shipments.new'))
@section('page-title', __('shipments.new'))

@section('content')
<form method="POST" action="{{ route('shipments.store') }}" id="shipmentForm"
      x-data="shipmentForm()" @submit.prevent="submitForm">
    @csrf

    <div class="space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('shipments.index') }}" class="hover:text-blue-600">{{ __('shipments.plural') }}</a>
            <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
            <span class="text-gray-900">{{ __('shipments.new') }}</span>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Left column - Main info --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Basic Info --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-blue-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات الشحنة' : 'Shipment Information' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">{{ __('shipments.shipment_date') }} <span class="text-red-500">*</span></label>
                                <input type="date" name="shipment_date" value="{{ old('shipment_date', date('Y-m-d')) }}"
                                       class="form-input @error('shipment_date') border-red-500 @enderror" required>
                                @error('shipment_date') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.shipment_type') }}</label>
                                <select name="shipment_type" class="form-select">
                                    @foreach(['standard','express','economy','fragile','documents','oversized'] as $type)
                                        <option value="{{ $type }}" {{ old('shipment_type','standard') == $type ? 'selected':'' }}>
                                            {{ __('shipments.type.'.$type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.delivery_type') }}</label>
                                <select name="delivery_type" class="form-select">
                                    @foreach(['door_to_door','branch_pickup','locker'] as $dt)
                                        <option value="{{ $dt }}" {{ old('delivery_type','door_to_door') == $dt ? 'selected':'' }}>
                                            {{ __('shipments.delivery_types.'.$dt) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.origin_branch') }} <span class="text-red-500">*</span></label>
                                <select name="origin_branch_id" class="form-select @error('origin_branch_id') border-red-500 @enderror" required>
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الفرع' : 'Select Branch' }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('origin_branch_id') == $branch->id ? 'selected':'' }}>
                                            {{ $branch->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('origin_branch_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.destination_branch') }} <span class="text-red-500">*</span></label>
                                <select name="destination_branch_id" class="form-select @error('destination_branch_id') border-red-500 @enderror" required>
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الفرع' : 'Select Branch' }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('destination_branch_id') == $branch->id ? 'selected':'' }}>
                                            {{ $branch->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_branch_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.assigned_driver') }}</label>
                                <select name="driver_id" class="form-select">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'بدون سائق' : 'No Driver' }}</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected':'' }}>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">{{ __('shipments.notes') }}</label>
                            <textarea name="notes" rows="2" class="form-textarea">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Sender Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user text-blue-500"></i>
                            {{ __('shipments.sender.title') }}
                        </h3>
                        <button type="button" @click="toggleSenderSearch()"
                                class="btn-secondary btn-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            {{ __('shipments.sender.select_existing') }}
                        </button>
                    </div>
                    <div class="card-body">
                        {{-- Existing sender search --}}
                        <div x-show="showSenderSearch" x-transition class="mb-4 p-3 bg-blue-50 rounded-lg">
                            <div class="flex gap-2">
                                <input type="text" x-model="senderSearch"
                                       @input.debounce.300ms="searchCustomers('sender')"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث بالاسم أو الهاتف...' : 'Search by name or phone...' }}"
                                       class="form-input flex-1 text-sm">
                                <button type="button" @click="showSenderSearch = false" class="btn-secondary btn-sm">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div x-show="senderResults.length > 0" class="mt-2 bg-white rounded-lg border border-gray-200 max-h-40 overflow-y-auto">
                                <template x-for="c in senderResults" :key="c.id">
                                    <button type="button" @click="selectSender(c)"
                                            class="w-full text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-3 py-2 hover:bg-blue-50 border-b border-gray-100 last:border-0 flex items-center justify-between gap-3">
                                        <div>
                                            <div class="text-sm font-medium" x-text="c.full_name"></div>
                                            <div class="text-xs text-gray-500" x-text="c.phone"></div>
                                        </div>
                                        <i class="fa-solid fa-check text-blue-600 text-xs"></i>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="sender_id" :value="selectedSenderId">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('shipments.sender.full_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="sender_name" x-model="senderName"
                                       value="{{ old('sender_name') }}"
                                       class="form-input @error('sender_name') border-red-500 @enderror" required>
                                @error('sender_name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.sender.phone') }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="sender_phone" x-model="senderPhone"
                                       value="{{ old('sender_phone') }}"
                                       class="form-input @error('sender_phone') border-red-500 @enderror" required>
                                @error('sender_phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.sender.phone_alt') }}</label>
                                <input type="tel" name="sender_phone_alt" value="{{ old('sender_phone_alt') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.sender.email') }}</label>
                                <input type="email" name="sender_email" value="{{ old('sender_email') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.sender.city') }}</label>
                                <input type="text" name="sender_city" value="{{ old('sender_city') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.sender.country') }}</label>
                                <select name="sender_country" class="form-select">
                                    <option value="Malaysia" {{ old('sender_country','Malaysia') == 'Malaysia' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}</option>
                                    <option value="Yemen" {{ old('sender_country') == 'Yemen' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label">{{ __('shipments.sender.address') }}</label>
                                <input type="text" name="sender_address" value="{{ old('sender_address') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.sender.id_number') }}</label>
                                <input type="text" name="sender_id_number" value="{{ old('sender_id_number') }}" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Receiver Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user-check text-green-500"></i>
                            {{ __('shipments.receiver.title') }}
                        </h3>
                        <button type="button" @click="toggleReceiverSearch()"
                                class="btn-secondary btn-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            {{ __('shipments.receiver.select_existing') }}
                        </button>
                    </div>
                    <div class="card-body">
                        {{-- Existing receiver search --}}
                        <div x-show="showReceiverSearch" x-transition class="mb-4 p-3 bg-green-50 rounded-lg">
                            <div class="flex gap-2">
                                <input type="text" x-model="receiverSearch"
                                       @input.debounce.300ms="searchCustomers('receiver')"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث بالاسم أو الهاتف...' : 'Search by name or phone...' }}"
                                       class="form-input flex-1 text-sm">
                                <button type="button" @click="showReceiverSearch = false" class="btn-secondary btn-sm">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div x-show="receiverResults.length > 0" class="mt-2 bg-white rounded-lg border border-gray-200 max-h-40 overflow-y-auto">
                                <template x-for="c in receiverResults" :key="c.id">
                                    <button type="button" @click="selectReceiver(c)"
                                            class="w-full text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} px-3 py-2 hover:bg-green-50 border-b border-gray-100 last:border-0">
                                        <div class="text-sm font-medium" x-text="c.full_name"></div>
                                        <div class="text-xs text-gray-500" x-text="c.phone"></div>
                                    </button>
                                </template>
                            </div>
                            <input type="hidden" name="receiver_id" :value="selectedReceiverId">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">{{ __('shipments.receiver.full_name') }} <span class="text-red-500">*</span></label>
                                <input type="text" name="receiver_name" x-model="receiverName"
                                       value="{{ old('receiver_name') }}"
                                       class="form-input @error('receiver_name') border-red-500 @enderror" required>
                                @error('receiver_name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.receiver.phone') }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="receiver_phone" x-model="receiverPhone"
                                       value="{{ old('receiver_phone') }}"
                                       class="form-input @error('receiver_phone') border-red-500 @enderror" required>
                                @error('receiver_phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.receiver.phone_alt') }}</label>
                                <input type="tel" name="receiver_phone_alt" value="{{ old('receiver_phone_alt') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.receiver.city') }}</label>
                                <input type="text" name="receiver_city" value="{{ old('receiver_city') }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.receiver.country') }}</label>
                                <select name="receiver_country" class="form-select">
                                    <option value="Yemen" {{ old('receiver_country','Yemen') == 'Yemen' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                                    <option value="Malaysia" {{ old('receiver_country') == 'Malaysia' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label">{{ __('shipments.receiver.address') }}</label>
                                <input type="text" name="receiver_address" value="{{ old('receiver_address') }}" class="form-input">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label">{{ __('shipments.receiver.delivery_instructions') }}</label>
                                <textarea name="delivery_instructions" rows="2" class="form-textarea">{{ old('delivery_instructions') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Packages --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-purple-500"></i>
                            {{ __('shipments.packages.title') }}
                        </h3>
                        <button type="button" @click="addPackage()" class="btn-primary btn-sm">
                            <i class="fa-solid fa-plus"></i>
                            {{ __('shipments.packages.add') }}
                        </button>
                    </div>
                    <div class="card-body space-y-3">
                        <template x-for="(pkg, index) in packages" :key="pkg.id">
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 flex items-center justify-between border-b border-gray-200">
                                    <span class="font-medium text-sm text-gray-700">
                                        {{ app()->getLocale() === 'ar' ? 'الطرد' : 'Package' }} #<span x-text="index + 1"></span>
                                    </span>
                                    <button type="button" @click="removePackage(index)"
                                            class="text-red-500 hover:text-red-700 text-sm" x-show="packages.length > 1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                <div class="p-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                    <div class="col-span-2">
                                        <label class="form-label text-xs">{{ __('shipments.packages.description') }}</label>
                                        <input type="text" :name="`packages[${index}][description]`" x-model="pkg.description"
                                               class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ __('shipments.packages.length') }}</label>
                                        <input type="number" :name="`packages[${index}][length]`" x-model.number="pkg.length"
                                               @input="calcWeights(index)" min="0" step="0.01"
                                               class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ __('shipments.packages.width') }}</label>
                                        <input type="number" :name="`packages[${index}][width]`" x-model.number="pkg.width"
                                               @input="calcWeights(index)" min="0" step="0.01"
                                               class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ __('shipments.packages.height') }}</label>
                                        <input type="number" :name="`packages[${index}][height]`" x-model.number="pkg.height"
                                               @input="calcWeights(index)" min="0" step="0.01"
                                               class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ __('shipments.packages.actual_weight') }}</label>
                                        <input type="number" :name="`packages[${index}][actual_weight]`" x-model.number="pkg.actual_weight"
                                               @input="calcWeights(index)" min="0" step="0.001"
                                               class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ __('shipments.packages.volumetric_weight') }}</label>
                                        <input type="text" :value="pkg.volumetric_weight.toFixed(3)"
                                               class="form-input text-sm bg-gray-50 text-gray-500" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs font-semibold text-blue-700">{{ __('shipments.packages.chargeable_weight') }}</label>
                                        <input type="text" :value="pkg.chargeable_weight.toFixed(3)"
                                               class="form-input text-sm bg-blue-50 text-blue-700 font-semibold" readonly>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Totals --}}
                        <div class="bg-blue-50 rounded-xl p-4 grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-700" x-text="packages.length"></div>
                                <div class="text-xs text-gray-600 mt-1">{{ __('shipments.packages.total_packages') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-700" x-text="totalActualWeight.toFixed(3) + ' kg'"></div>
                                <div class="text-xs text-gray-600 mt-1">{{ __('shipments.packages.total_actual_weight') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-700" x-text="totalVolumetricWeight.toFixed(3) + ' kg'"></div>
                                <div class="text-xs text-gray-600 mt-1">{{ __('shipments.packages.total_volumetric_weight') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-700" x-text="totalChargeableWeight.toFixed(3) + ' kg'"></div>
                                <div class="text-xs text-gray-600 mt-1">{{ __('shipments.packages.total_chargeable_weight') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right sidebar --}}
            <div class="space-y-6">
                {{-- Submit --}}
                <div class="card">
                    <div class="card-body space-y-3">
                        <button type="submit" class="btn-primary w-full justify-center btn-lg" :disabled="submitting">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span x-text="submitting ? '{{ app()->getLocale() === 'ar' ? 'جاري الحفظ...' : 'Saving...' }}' : '{{ __('app.actions.save') }}'"></span>
                        </button>
                        <a href="{{ route('shipments.index') }}" class="btn-secondary w-full justify-center">
                            <i class="fa-solid fa-xmark"></i>
                            {{ __('app.actions.cancel') }}
                        </a>
                    </div>
                </div>

                {{-- Summary card --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 text-sm">
                            {{ app()->getLocale() === 'ar' ? 'ملخص الشحنة' : 'Shipment Summary' }}
                        </h3>
                    </div>
                    <div class="card-body space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('shipments.packages.total_packages') }}</span>
                            <span class="font-semibold" x-text="packages.length"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ __('shipments.packages.total_actual_weight') }}</span>
                            <span class="font-semibold" x-text="totalActualWeight.toFixed(3) + ' kg'"></span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-700 font-medium">{{ __('shipments.packages.total_chargeable_weight') }}</span>
                            <span class="font-bold text-blue-700" x-text="totalChargeableWeight.toFixed(3) + ' kg'"></span>
                        </div>
                    </div>
                </div>

                {{-- Validation errors --}}
                @if($errors->any())
                    <div class="card border-red-200">
                        <div class="card-body">
                            <h4 class="text-sm font-semibold text-red-700 mb-2 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                {{ app()->getLocale() === 'ar' ? 'يرجى تصحيح الأخطاء' : 'Please fix the errors' }}
                            </h4>
                            <ul class="text-xs text-red-600 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function shipmentForm() {
    return {
        submitting: false,
        showSenderSearch: false,
        showReceiverSearch: false,
        senderSearch: '',
        receiverSearch: '',
        senderResults: [],
        receiverResults: [],
        selectedSenderId: null,
        selectedReceiverId: null,
        senderName: '{{ old('sender_name') }}',
        senderPhone: '{{ old('sender_phone') }}',
        receiverName: '{{ old('receiver_name') }}',
        receiverPhone: '{{ old('receiver_phone') }}',
        packages: [{ id: Date.now(), description: '', length: 0, width: 0, height: 0, actual_weight: 0, volumetric_weight: 0, chargeable_weight: 0 }],

        get totalActualWeight() { return this.packages.reduce((s, p) => s + (parseFloat(p.actual_weight) || 0), 0); },
        get totalVolumetricWeight() { return this.packages.reduce((s, p) => s + (parseFloat(p.volumetric_weight) || 0), 0); },
        get totalChargeableWeight() { return this.packages.reduce((s, p) => s + (parseFloat(p.chargeable_weight) || 0), 0); },

        calcWeights(i) {
            const p = this.packages[i];
            const vol = Math.round(((p.length || 0) * (p.width || 0) * (p.height || 0)) / 5000 * 1000) / 1000;
            p.volumetric_weight = vol;
            p.chargeable_weight = Math.max(parseFloat(p.actual_weight) || 0, vol);
        },

        addPackage() {
            this.packages.push({ id: Date.now(), description: '', length: 0, width: 0, height: 0, actual_weight: 0, volumetric_weight: 0, chargeable_weight: 0 });
        },

        removePackage(i) {
            if (this.packages.length > 1) this.packages.splice(i, 1);
        },

        toggleSenderSearch() { this.showSenderSearch = !this.showSenderSearch; },
        toggleReceiverSearch() { this.showReceiverSearch = !this.showReceiverSearch; },

        async searchCustomers(type) {
            const q = type === 'sender' ? this.senderSearch : this.receiverSearch;
            if (q.length < 2) { this[type === 'sender' ? 'senderResults' : 'receiverResults'] = []; return; }
            const res = await fetch(`/api/customers/search?q=${encodeURIComponent(q)}&_token={{ csrf_token() }}`);
            const data = await res.json();
            this[type === 'sender' ? 'senderResults' : 'receiverResults'] = data;
        },

        selectSender(c) {
            this.selectedSenderId = c.id;
            this.senderName = c.full_name;
            this.senderPhone = c.phone;
            document.querySelector('[name="sender_name"]').value = c.full_name;
            document.querySelector('[name="sender_phone"]').value = c.phone;
            if (document.querySelector('[name="sender_phone_alt"]')) document.querySelector('[name="sender_phone_alt"]').value = c.phone_alt || '';
            if (document.querySelector('[name="sender_email"]')) document.querySelector('[name="sender_email"]').value = c.email || '';
            if (document.querySelector('[name="sender_address"]')) document.querySelector('[name="sender_address"]').value = c.address || '';
            if (document.querySelector('[name="sender_city"]')) document.querySelector('[name="sender_city"]').value = c.city || '';
            this.showSenderSearch = false;
        },

        selectReceiver(c) {
            this.selectedReceiverId = c.id;
            this.receiverName = c.full_name;
            this.receiverPhone = c.phone;
            document.querySelector('[name="receiver_name"]').value = c.full_name;
            document.querySelector('[name="receiver_phone"]').value = c.phone;
            if (document.querySelector('[name="receiver_phone_alt"]')) document.querySelector('[name="receiver_phone_alt"]').value = c.phone_alt || '';
            if (document.querySelector('[name="receiver_address"]')) document.querySelector('[name="receiver_address"]').value = c.address || '';
            if (document.querySelector('[name="receiver_city"]')) document.querySelector('[name="receiver_city"]').value = c.city || '';
            this.showReceiverSearch = false;
        },

        submitForm() {
            this.submitting = true;
            document.getElementById('shipmentForm').submit();
        }
    }
}
</script>
@endpush

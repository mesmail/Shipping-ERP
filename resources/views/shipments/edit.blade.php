@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تعديل الشحنة' : 'Edit Shipment')
@section('page-title', app()->getLocale() === 'ar' ? 'تعديل الشحنة' : 'Edit Shipment')

@section('content')
<form method="POST" action="{{ route('shipments.update', $shipment) }}" id="shipmentForm"
      x-data="shipmentForm()" @submit.prevent="submitForm">
    @csrf
    @method('PUT')

    <div class="space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('shipments.index') }}" class="hover:text-blue-600">{{ app()->getLocale() === 'ar' ? 'الشحنات' : 'Shipments' }}</a>
            <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
            <a href="{{ route('shipments.show', $shipment) }}" class="hover:text-blue-600 font-mono">{{ $shipment->tracking_number }}</a>
            <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
            <span class="text-gray-900">{{ app()->getLocale() === 'ar' ? 'تعديل' : 'Edit' }}</span>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center gap-3">
                <i class="fa-solid fa-circle-xmark text-red-500"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

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
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تاريخ الشحن' : 'Shipment Date' }} <span class="text-red-500">*</span></label>
                                <input type="date" name="shipment_date"
                                       value="{{ old('shipment_date', $shipment->shipment_date->format('Y-m-d')) }}"
                                       class="form-input @error('shipment_date') border-red-500 @enderror" required>
                                @error('shipment_date') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نوع الشحنة' : 'Shipment Type' }}</label>
                                <select name="shipment_type" class="form-select">
                                    @foreach(['standard','express','economy','fragile','documents','oversized'] as $type)
                                        <option value="{{ $type }}" {{ old('shipment_type', $shipment->shipment_type) == $type ? 'selected':'' }}>
                                            {{ __('shipments.type.'.$type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'نوع التوصيل' : 'Delivery Type' }}</label>
                                <select name="delivery_type" class="form-select">
                                    @foreach(['door_to_door','branch_pickup','locker'] as $dt)
                                        <option value="{{ $dt }}" {{ old('delivery_type', $shipment->delivery_type) == $dt ? 'selected':'' }}>
                                            {{ __('shipments.delivery_types.'.$dt) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'فرع الإرسال' : 'Origin Branch' }} <span class="text-red-500">*</span></label>
                                <select name="origin_branch_id" class="form-select @error('origin_branch_id') border-red-500 @enderror" required>
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الفرع' : 'Select Branch' }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('origin_branch_id', $shipment->origin_branch_id) == $branch->id ? 'selected':'' }}>
                                            {{ $branch->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('origin_branch_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'فرع الوصول' : 'Destination Branch' }} <span class="text-red-500">*</span></label>
                                <select name="destination_branch_id" class="form-select @error('destination_branch_id') border-red-500 @enderror" required>
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الفرع' : 'Select Branch' }}</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('destination_branch_id', $shipment->destination_branch_id) == $branch->id ? 'selected':'' }}>
                                            {{ $branch->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_branch_id') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'السائق' : 'Driver' }}</label>
                                <select name="driver_id" class="form-select">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'بدون سائق' : 'No Driver' }}</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id', $shipment->driver_id) == $driver->id ? 'selected':'' }}>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">{{ app()->getLocale() === 'ar' ? 'ملاحظات' : 'Notes' }}</label>
                            <textarea name="notes" rows="2" class="form-textarea">{{ old('notes', $shipment->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Sender Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user text-blue-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات المرسل' : 'Sender Information' }}
                        </h3>
                        <button type="button" @click="toggleSenderSearch()" class="btn-secondary btn-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            {{ app()->getLocale() === 'ar' ? 'اختيار من القائمة' : 'Select Existing' }}
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
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }} <span class="text-red-500">*</span></label>
                                <input type="text" name="sender_name" x-model="senderName"
                                       value="{{ old('sender_name', $shipment->sender_name) }}"
                                       class="form-input @error('sender_name') border-red-500 @enderror" required>
                                @error('sender_name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="sender_phone" x-model="senderPhone"
                                       value="{{ old('sender_phone', $shipment->sender_phone) }}"
                                       class="form-input @error('sender_phone') border-red-500 @enderror" required>
                                @error('sender_phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'هاتف بديل' : 'Alt Phone' }}</label>
                                <input type="tel" name="sender_phone_alt" value="{{ old('sender_phone_alt', $shipment->sender_phone_alt) }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                                <input type="email" name="sender_email" value="{{ old('sender_email', $shipment->sender_email) }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}</label>
                                <input type="text" name="sender_city" value="{{ old('sender_city', $shipment->sender_city) }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الدولة' : 'Country' }}</label>
                                <select name="sender_country" class="form-select">
                                    <option value="Malaysia" {{ old('sender_country', $shipment->sender_country) == 'Malaysia' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}</option>
                                    <option value="Yemen" {{ old('sender_country', $shipment->sender_country) == 'Yemen' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}</label>
                                <input type="text" name="sender_address" value="{{ old('sender_address', $shipment->sender_address) }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الهوية' : 'ID Number' }}</label>
                                <input type="text" name="sender_id_number" value="{{ old('sender_id_number', $shipment->sender_id_number) }}" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Receiver Information --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user-check text-green-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات المستلم' : 'Receiver Information' }}
                        </h3>
                        <button type="button" @click="toggleReceiverSearch()" class="btn-secondary btn-sm">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            {{ app()->getLocale() === 'ar' ? 'اختيار من القائمة' : 'Select Existing' }}
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
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }} <span class="text-red-500">*</span></label>
                                <input type="text" name="receiver_name" x-model="receiverName"
                                       value="{{ old('receiver_name', $shipment->receiver_name) }}"
                                       class="form-input @error('receiver_name') border-red-500 @enderror" required>
                                @error('receiver_name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone' }} <span class="text-red-500">*</span></label>
                                <input type="tel" name="receiver_phone" x-model="receiverPhone"
                                       value="{{ old('receiver_phone', $shipment->receiver_phone) }}"
                                       class="form-input @error('receiver_phone') border-red-500 @enderror" required>
                                @error('receiver_phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'هاتف بديل' : 'Alt Phone' }}</label>
                                <input type="tel" name="receiver_phone_alt" value="{{ old('receiver_phone_alt', $shipment->receiver_phone_alt) }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'المدينة' : 'City' }}</label>
                                <input type="text" name="receiver_city" value="{{ old('receiver_city', $shipment->receiver_city) }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'الدولة' : 'Country' }}</label>
                                <select name="receiver_country" class="form-select">
                                    <option value="Yemen" {{ old('receiver_country', $shipment->receiver_country) == 'Yemen' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                                    <option value="Malaysia" {{ old('receiver_country', $shipment->receiver_country) == 'Malaysia' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}</label>
                                <input type="text" name="receiver_address" value="{{ old('receiver_address', $shipment->receiver_address) }}" class="form-input">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="form-label">{{ app()->getLocale() === 'ar' ? 'تعليمات التسليم' : 'Delivery Instructions' }}</label>
                                <textarea name="delivery_instructions" rows="2" class="form-textarea">{{ old('delivery_instructions', $shipment->delivery_instructions) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Packages --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-boxes-stacked text-purple-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'الطرود' : 'Packages' }}
                        </h3>
                        <button type="button" @click="addPackage()" class="btn-primary btn-sm">
                            <i class="fa-solid fa-plus"></i>
                            {{ app()->getLocale() === 'ar' ? 'إضافة طرد' : 'Add Package' }}
                        </button>
                    </div>
                    <div class="card-body space-y-3">
                        <template x-for="(pkg, index) in packages" :key="pkg.id">
                            <div class="border border-gray-200 rounded-xl overflow-hidden">
                                <div class="bg-gray-50 px-4 py-2 flex items-center justify-between border-b border-gray-200">
                                    <span class="font-medium text-sm text-gray-700">
                                        {{ app()->getLocale() === 'ar' ? 'الطرد' : 'Package' }} #<span x-text="index + 1"></span>
                                        <span x-show="pkg.existing_id" class="text-xs text-blue-600 {{ app()->getLocale() === 'ar' ? 'mr-2' : 'ml-2' }}">
                                            ({{ app()->getLocale() === 'ar' ? 'موجود' : 'Existing' }})
                                        </span>
                                    </span>
                                    <button type="button" @click="removePackage(index)"
                                            class="text-red-500 hover:text-red-700 text-sm" x-show="packages.length > 1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                <div class="p-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                    <input type="hidden" :name="`packages[${index}][id]`" :value="pkg.existing_id || ''">
                                    <div class="col-span-2">
                                        <label class="form-label text-xs">{{ app()->getLocale() === 'ar' ? 'الوصف' : 'Description' }}</label>
                                        <input type="text" :name="`packages[${index}][description]`" x-model="pkg.description"
                                               class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ app()->getLocale() === 'ar' ? 'الطول (سم)' : 'Length (cm)' }}</label>
                                        <input type="number" :name="`packages[${index}][length]`" x-model.number="pkg.length"
                                               @input="calcWeights(index)" min="0" step="0.01" class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ app()->getLocale() === 'ar' ? 'العرض (سم)' : 'Width (cm)' }}</label>
                                        <input type="number" :name="`packages[${index}][width]`" x-model.number="pkg.width"
                                               @input="calcWeights(index)" min="0" step="0.01" class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ app()->getLocale() === 'ar' ? 'الارتفاع (سم)' : 'Height (cm)' }}</label>
                                        <input type="number" :name="`packages[${index}][height]`" x-model.number="pkg.height"
                                               @input="calcWeights(index)" min="0" step="0.01" class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ app()->getLocale() === 'ar' ? 'الوزن الفعلي (كجم)' : 'Actual Weight (kg)' }}</label>
                                        <input type="number" :name="`packages[${index}][actual_weight]`" x-model.number="pkg.actual_weight"
                                               @input="calcWeights(index)" min="0" step="0.001" class="form-input text-sm">
                                    </div>
                                    <div>
                                        <label class="form-label text-xs">{{ app()->getLocale() === 'ar' ? 'الوزن الحجمي' : 'Volumetric Weight' }}</label>
                                        <input type="text" :value="pkg.volumetric_weight.toFixed(3)"
                                               class="form-input text-sm bg-gray-50 text-gray-500" readonly>
                                    </div>
                                    <div>
                                        <label class="form-label text-xs font-semibold text-blue-700">{{ app()->getLocale() === 'ar' ? 'الوزن المحسوب' : 'Chargeable Weight' }}</label>
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
                                <div class="text-xs text-gray-600 mt-1">{{ app()->getLocale() === 'ar' ? 'إجمالي الطرود' : 'Total Packages' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-700" x-text="totalActualWeight.toFixed(3) + ' kg'"></div>
                                <div class="text-xs text-gray-600 mt-1">{{ app()->getLocale() === 'ar' ? 'الوزن الفعلي' : 'Actual Weight' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-700" x-text="totalVolumetricWeight.toFixed(3) + ' kg'"></div>
                                <div class="text-xs text-gray-600 mt-1">{{ app()->getLocale() === 'ar' ? 'الوزن الحجمي' : 'Volumetric Weight' }}</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-700" x-text="totalChargeableWeight.toFixed(3) + ' kg'"></div>
                                <div class="text-xs text-gray-600 mt-1">{{ app()->getLocale() === 'ar' ? 'الوزن المحسوب' : 'Chargeable Weight' }}</div>
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
                            <span x-text="submitting ? '{{ app()->getLocale() === 'ar' ? 'جاري الحفظ...' : 'Saving...' }}' : '{{ app()->getLocale() === 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}'"></span>
                        </button>
                        <a href="{{ route('shipments.show', $shipment) }}" class="btn-secondary w-full justify-center">
                            <i class="fa-solid fa-xmark"></i>
                            {{ app()->getLocale() === 'ar' ? 'إلغاء' : 'Cancel' }}
                        </a>
                    </div>
                </div>

                {{-- Shipment info --}}
                <div class="card bg-gray-50">
                    <div class="card-body">
                        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">
                            {{ app()->getLocale() === 'ar' ? 'معلومات الشحنة' : 'Shipment Info' }}
                        </h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'رقم التتبع' : 'Tracking' }}</span>
                                <span class="font-mono font-bold text-gray-900">{{ $shipment->tracking_number }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>{{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}</span>
                                @include('shipments._status_badge', ['status' => $shipment->status])
                            </div>
                            <div class="flex justify-between">
                                <span>{{ app()->getLocale() === 'ar' ? 'تاريخ الإنشاء' : 'Created' }}</span>
                                <span>{{ $shipment->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Summary card --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 text-sm">
                            {{ app()->getLocale() === 'ar' ? 'ملخص الطرود' : 'Package Summary' }}
                        </h3>
                    </div>
                    <div class="card-body space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ app()->getLocale() === 'ar' ? 'عدد الطرود' : 'Total Packages' }}</span>
                            <span class="font-semibold" x-text="packages.length"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ app()->getLocale() === 'ar' ? 'الوزن الفعلي' : 'Actual Weight' }}</span>
                            <span class="font-semibold" x-text="totalActualWeight.toFixed(3) + ' kg'"></span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-gray-700 font-medium">{{ app()->getLocale() === 'ar' ? 'الوزن المحسوب' : 'Chargeable Weight' }}</span>
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
        selectedSenderId: {{ $shipment->sender_id ?? 'null' }},
        selectedReceiverId: {{ $shipment->receiver_id ?? 'null' }},
        senderName: '{{ addslashes(old('sender_name', $shipment->sender_name)) }}',
        senderPhone: '{{ addslashes(old('sender_phone', $shipment->sender_phone)) }}',
        receiverName: '{{ addslashes(old('receiver_name', $shipment->receiver_name)) }}',
        receiverPhone: '{{ addslashes(old('receiver_phone', $shipment->receiver_phone)) }}',

        packages: @json(
            $shipment->packages->count()
                ? $shipment->packages->map(fn($p) => [
                    'id'                => $p->id,
                    'existing_id'       => $p->id,
                    'description'       => $p->description ?? '',
                    'length'            => (float)($p->length ?? 0),
                    'width'             => (float)($p->width ?? 0),
                    'height'            => (float)($p->height ?? 0),
                    'actual_weight'     => (float)($p->actual_weight ?? 0),
                    'volumetric_weight' => (float)($p->volumetric_weight ?? 0),
                    'chargeable_weight' => (float)($p->chargeable_weight ?? 0),
                ])
                : [['id' => 1, 'existing_id' => null, 'description' => '', 'length' => 0, 'width' => 0, 'height' => 0, 'actual_weight' => 0, 'volumetric_weight' => 0, 'chargeable_weight' => 0]]
        ),

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
            this.packages.push({ id: Date.now(), existing_id: null, description: '', length: 0, width: 0, height: 0, actual_weight: 0, volumetric_weight: 0, chargeable_weight: 0 });
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

@extends('layouts.app')

@section('title', __('shipments.edit'))
@section('page-title', __('shipments.edit'))

@section('content')
<form method="POST" action="{{ route('shipments.update', $shipment) }}" id="editShipmentForm">
    @csrf @method('PUT')

    <div class="space-y-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('shipments.index') }}" class="hover:text-blue-600">{{ __('shipments.plural') }}</a>
            <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
            <a href="{{ route('shipments.show', $shipment) }}" class="hover:text-blue-600 font-mono">{{ $shipment->tracking_number }}</a>
            <i class="fa-solid fa-chevron-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs"></i>
            <span class="text-gray-900">{{ __('app.actions.edit') }}</span>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 space-y-6">

                {{-- Basic Info --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-blue-500"></i>
                            {{ app()->getLocale() === 'ar' ? 'معلومات الشحنة' : 'Shipment Information' }}
                        </h3>
                        <span class="font-mono text-xs text-blue-700 bg-blue-50 px-2 py-1 rounded">{{ $shipment->tracking_number }}</span>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">{{ __('shipments.shipment_date') }} <span class="text-red-500">*</span></label>
                                <input type="date" name="shipment_date" value="{{ old('shipment_date', $shipment->shipment_date->format('Y-m-d')) }}" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.shipment_type') }}</label>
                                <select name="shipment_type" class="form-select">
                                    @foreach(['standard','express','economy','fragile','documents','oversized'] as $type)
                                        <option value="{{ $type }}" {{ old('shipment_type',$shipment->shipment_type) == $type ? 'selected':'' }}>{{ __('shipments.type.'.$type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.delivery_type') }}</label>
                                <select name="delivery_type" class="form-select">
                                    @foreach(['door_to_door','branch_pickup','locker'] as $dt)
                                        <option value="{{ $dt }}" {{ old('delivery_type',$shipment->delivery_type) == $dt ? 'selected':'' }}>{{ __('shipments.delivery_types.'.$dt) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.origin_branch') }} <span class="text-red-500">*</span></label>
                                <select name="origin_branch_id" class="form-select" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('origin_branch_id',$shipment->origin_branch_id) == $branch->id ? 'selected':'' }}>{{ $branch->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.destination_branch') }} <span class="text-red-500">*</span></label>
                                <select name="destination_branch_id" class="form-select" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('destination_branch_id',$shipment->destination_branch_id) == $branch->id ? 'selected':'' }}>{{ $branch->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">{{ __('shipments.assigned_driver') }}</label>
                                <select name="driver_id" class="form-select">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'بدون سائق' : 'No Driver' }}</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id',$shipment->driver_id) == $driver->id ? 'selected':'' }}>{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">{{ __('shipments.notes') }}</label>
                            <textarea name="notes" rows="2" class="form-textarea">{{ old('notes', $shipment->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Sender --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user text-blue-500"></i>
                            {{ __('shipments.sender.title') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="form-label">{{ __('shipments.sender.full_name') }} <span class="text-red-500">*</span></label><input type="text" name="sender_name" value="{{ old('sender_name',$shipment->sender_name) }}" class="form-input" required></div>
                            <div><label class="form-label">{{ __('shipments.sender.phone') }} <span class="text-red-500">*</span></label><input type="tel" name="sender_phone" value="{{ old('sender_phone',$shipment->sender_phone) }}" class="form-input" required></div>
                            <div><label class="form-label">{{ __('shipments.sender.phone_alt') }}</label><input type="tel" name="sender_phone_alt" value="{{ old('sender_phone_alt',$shipment->sender_phone_alt) }}" class="form-input"></div>
                            <div><label class="form-label">{{ __('shipments.sender.email') }}</label><input type="email" name="sender_email" value="{{ old('sender_email',$shipment->sender_email) }}" class="form-input"></div>
                            <div><label class="form-label">{{ __('shipments.sender.city') }}</label><input type="text" name="sender_city" value="{{ old('sender_city',$shipment->sender_city) }}" class="form-input"></div>
                            <div>
                                <label class="form-label">{{ __('shipments.sender.country') }}</label>
                                <select name="sender_country" class="form-select">
                                    <option value="Malaysia" {{ old('sender_country',$shipment->sender_country) == 'Malaysia' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}</option>
                                    <option value="Yemen" {{ old('sender_country',$shipment->sender_country) == 'Yemen' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2"><label class="form-label">{{ __('shipments.sender.address') }}</label><input type="text" name="sender_address" value="{{ old('sender_address',$shipment->sender_address) }}" class="form-input"></div>
                            <div><label class="form-label">{{ __('shipments.sender.id_number') }}</label><input type="text" name="sender_id_number" value="{{ old('sender_id_number',$shipment->sender_id_number) }}" class="form-input"></div>
                        </div>
                    </div>
                </div>

                {{-- Receiver --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fa-solid fa-user-check text-green-500"></i>
                            {{ __('shipments.receiver.title') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div><label class="form-label">{{ __('shipments.receiver.full_name') }} <span class="text-red-500">*</span></label><input type="text" name="receiver_name" value="{{ old('receiver_name',$shipment->receiver_name) }}" class="form-input" required></div>
                            <div><label class="form-label">{{ __('shipments.receiver.phone') }} <span class="text-red-500">*</span></label><input type="tel" name="receiver_phone" value="{{ old('receiver_phone',$shipment->receiver_phone) }}" class="form-input" required></div>
                            <div><label class="form-label">{{ __('shipments.receiver.phone_alt') }}</label><input type="tel" name="receiver_phone_alt" value="{{ old('receiver_phone_alt',$shipment->receiver_phone_alt) }}" class="form-input"></div>
                            <div><label class="form-label">{{ __('shipments.receiver.city') }}</label><input type="text" name="receiver_city" value="{{ old('receiver_city',$shipment->receiver_city) }}" class="form-input"></div>
                            <div>
                                <label class="form-label">{{ __('shipments.receiver.country') }}</label>
                                <select name="receiver_country" class="form-select">
                                    <option value="Yemen" {{ old('receiver_country',$shipment->receiver_country) == 'Yemen' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                                    <option value="Malaysia" {{ old('receiver_country',$shipment->receiver_country) == 'Malaysia' ? 'selected':'' }}>{{ app()->getLocale() === 'ar' ? 'ماليزيا' : 'Malaysia' }}</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2"><label class="form-label">{{ __('shipments.receiver.address') }}</label><input type="text" name="receiver_address" value="{{ old('receiver_address',$shipment->receiver_address) }}" class="form-input"></div>
                            <div class="sm:col-span-2"><label class="form-label">{{ __('shipments.receiver.delivery_instructions') }}</label><textarea name="delivery_instructions" rows="2" class="form-textarea">{{ old('delivery_instructions',$shipment->delivery_instructions) }}</textarea></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="card">
                    <div class="card-body space-y-3">
                        <button type="submit" class="btn-primary w-full justify-center">
                            <i class="fa-solid fa-floppy-disk"></i> {{ __('app.actions.save') }}
                        </button>
                        <a href="{{ route('shipments.show', $shipment) }}" class="btn-secondary w-full justify-center">
                            <i class="fa-solid fa-xmark"></i> {{ __('app.actions.cancel') }}
                        </a>
                    </div>
                </div>

                {{-- Current Packages Summary --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-gray-900 text-sm">{{ __('shipments.packages.title') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-sm text-gray-500 mb-3">{{ app()->getLocale() === 'ar' ? 'لتعديل الطرود، استخدم صفحة التفاصيل' : 'To edit packages, use the detail page' }}</p>
                        <a href="{{ route('shipments.show', $shipment) }}" class="btn-secondary btn-sm w-full justify-center">
                            <i class="fa-solid fa-boxes-stacked"></i>
                            {{ $shipment->total_packages }} {{ __('shipments.packages.title') }}
                        </a>
                    </div>
                </div>

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

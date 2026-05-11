@extends('layouts.app')
@section('title', __('module.catering') . ' – ' . $event->title)
@section('page-title', __('module_number', ['num' => 6]) . ': ' . __('module.catering'))
@section('content')
    <div class="mb-4">
        <a href="{{ route('events.show', $event) }}"
            class="text-sm text-green-600 hover:text-green-700">{{ __('back_to_event') }}</a>
        <p class="text-xs text-gray-500 mt-1">{{ __('catering.criteria_hint') }}</p>
    </div>

    <form method="POST" action="{{ route('events.modules.catering.update', $event) }}" x-data="{ cateringType: '{{ $module->catering_type ?? 'external_caterer' }}', hasStands: {{ $module->food_stands_count > 0 ? 'true' : 'false' }} }">
        @csrf @method('PUT')
        <div class="space-y-6">

            
            <div class="card">
                <h3 class="section-title">{{ __('catering.caterer_info') }}</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="form-label">{{ __('catering.catering_type') }}</label>
                        <select name="catering_type" x-model="cateringType" class="form-select">
                            <option value="external_caterer">{{ __('catering.type_external') }}</option>
                            <option value="own_kitchen">{{ __('catering.type_own_kitchen') }}</option>
                            <option value="gastronomy">{{ __('catering.type_gastronomy') }}</option>
                            <option value="mixed">{{ __('catering.type_mixed') }}</option>
                            <option value="none">{{ __('catering.type_none') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">{{ __('catering.caterer_name') }}</label>
                        <input type="text" name="catering_company_name" value="{{ $module->catering_company_name }}"
                            class="form-input">
                    </div>
                </div>
                <div>
                    <label class="form-label">{{ __('catering.certifications_label') }}</label>
                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <label
                            class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                            <input type="checkbox" name="caterer_has_umweltzeichen" value="1"
                                {{ $module->caterer_has_umweltzeichen ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-sm">🌿 {{ __('catering.cert_umweltzeichen') }}</span>
                        </label>
                        <label
                            class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                            <input type="checkbox" name="caterer_is_100pct_vegan_bio" value="1"
                                {{ $module->caterer_is_100pct_vegan_bio ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-sm">🌱 {{ __('catering.cert_vegan_bio') }}</span>
                        </label>
                        <label
                            class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                            <input type="checkbox" name="caterer_has_bio_certification" value="1"
                                {{ $module->caterer_has_bio_certification ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-sm">{{ __('catering.cert_bio') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            
            <div class="card">
                <h3 class="section-title">{{ __('catering.muss_title') }} <span
                        class="muss-badge">{{ __('muss_required') }}</span></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @php
                        $mussCriteria = [
                            ['reusable_cups_and_dishes', __('catering.c2')],
                            ['drinks_in_bulk_containers', __('catering.c3')],
                            ['food_waste_eco_disposal', __('catering.c4')],
                            ['no_open_front_coolers', __('catering.c5')],
                            ['no_gas_patio_heaters', __('catering.c6')],
                            ['free_tap_water', __('catering.c7')],
                            ['two_seasonal_regional_ingredients', __('catering.c8')],
                            ['two_regional_drinks', __('catering.c9')],
                            ['one_bio_drink_and_ingredient', __('catering.c10')],
                            ['one_fair_trade_product', __('catering.c11')],
                            ['sustainable_seafood', __('catering.c12')],
                            ['no_endangered_species', __('catering.c13')],
                            ['free_range_eggs', __('catering.c14')],
                            ['vegetarian_option', __('catering.c15')],
                            ['staff_informed', __('catering.c16')],
                            ['quality_communicated_to_guests', __('catering.c17')],
                        ];
                    @endphp
                    @foreach ($mussCriteria as [$field, $label])
                        <label
                            class="flex items-start gap-3 p-3 rounded-lg border border-gray-100 hover:border-green-200 cursor-pointer">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox mt-0.5">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            
            <div class="card">
                <h3 class="section-title">{{ __('catering.bio_share_title') }}</h3>
                <div class="space-y-3">
                    @foreach ([['bio_100pct', __('catering.bio_100')], ['bio_50pct_main_and_drinks', __('catering.bio_50_main_drinks')], ['bio_30pct_main_and_drinks', __('catering.bio_30_main_drinks')], ['bio_50pct_main_only', __('catering.bio_50_main_only')], ['bio_30pct_main_only', __('catering.bio_30_main_only')]] as [$field, $label])
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="bio_level" value="{{ $field }}"
                                {{ $module->$field ? 'checked' : '' }} class="h-4 w-4 text-green-600 border-gray-300">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            
            <div class="card">
                <h3 class="section-title">{{ __('catering.veg_vegan_title') }}</h3>
                <div class="grid grid-cols-2 gap-3">
                    <label
                        class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                        <input type="checkbox" name="vegetarian_full_menu" value="1"
                            {{ $module->vegetarian_full_menu ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm">{{ __('catering.c32') }}</span>
                    </label>
                    <label
                        class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200">
                        <input type="checkbox" name="vegan_full_menu" value="1"
                            {{ $module->vegan_full_menu ? 'checked' : '' }} class="form-checkbox">
                        <span class="text-sm">{{ __('catering.c33') }}</span>
                    </label>
                </div>
            </div>

            
            <div class="card">
                <h3 class="section-title">{{ __('catering.fairtrade_title') }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach ([['fair_trade_coffee', __('catering.ft_coffee')], ['fair_trade_tea', __('catering.ft_tea')], ['fair_trade_cocoa', __('catering.ft_cocoa')], ['fair_trade_chocolate', __('catering.ft_chocolate')]] as [$field, $label])
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            
            <div class="card">
                <h3 class="section-title">{{ __('catering.additional_soll_title') }}</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ([['no_still_mineral_water', __('catering.c34')], ['origin_labeled_on_menu', __('catering.c17a')], ['food_waste_calculated', __('catering.c30a')], ['leftover_food_solution', __('catering.c30b')], ['special_diet_allergies', __('catering.c31a')], ['special_diet_religious', __('catering.c31b')], ['eco_cleaning_dishwash', __('catering.c28')], ['regional_typical_dishes', __('catering.c27')]] as [$field, $label])
                        <label
                            class="flex items-center gap-2 p-3 border border-gray-100 rounded-lg cursor-pointer hover:border-green-200 text-sm">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="mt-4">
                    <label class="form-label">{{ __('catering.local_specialties_count') }}</label>
                    <input type="number" name="local_specialties_count"
                        value="{{ $module->local_specialties_count ?? 0 }}" class="form-input max-w-xs" min="0"
                        max="4">
                </div>
            </div>

            
            <div class="card">
                <h3 class="section-title">{{ __('catering.food_stands_title') }}</h3>
                <div class="mb-4">
                    <label class="form-label">{{ __('catering.food_stands_count') }}</label>
                    <input type="number" name="food_stands_count" value="{{ $module->food_stands_count ?? 0 }}"
                        class="form-input max-w-xs" min="0" x-on:change="hasStands = $event.target.value > 0">
                </div>
                <div x-show="hasStands" class="space-y-3">
                    @foreach ([['food_stands_briefed', __('catering.vk1')], ['food_stands_contracted', __('catering.vk2')], ['food_stands_veg_options_min2', __('catering.vk3')], ['food_stands_50pct_voluntary', __('catering.vk4_50')], ['food_stands_100pct_voluntary', __('catering.vk4_100')]] as [$field, $label])
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="{{ $field }}" value="1"
                                {{ $module->$field ? 'checked' : '' }} class="form-checkbox">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="card">
                <label class="form-label">{{ __('Notes') }}</label>
                <textarea name="notes" rows="3" class="form-input">{{ $module->notes }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">{{ __('Save') }}</button>
                <a href="{{ route('events.show', $event) }}" class="btn-secondary">{{ __('Cancel') }}</a>
            </div>

        </div>
    </form>
@endsection

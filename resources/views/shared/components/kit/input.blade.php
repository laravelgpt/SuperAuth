@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'size' => 'md',
    'variant' => 'default'
])

@php
$baseClasses = 'block w-full rounded-lg border transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0';
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2.5 text-sm',
    'lg' => 'px-4 py-3 text-base'
];
$variantClasses = [
    'default' => 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white',
    'error' => 'border-red-500 focus:border-red-500 focus:ring-red-500 dark:border-red-400',
    'success' => 'border-green-500 focus:border-green-500 focus:ring-green-500 dark:border-green-400'
];
$inputClasses = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . ($error ? $variantClasses['error'] : $variantClasses['default']);
@endphp

<div class="space-y-1">
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-superauth::icons.{{ $icon }} class="h-4 w-4 text-gray-400" />
            </div>
        @endif
        
        <input 
            type="{{ $type }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge(['class' => $inputClasses . ($icon && $iconPosition === 'left' ? ' pl-10' : '') . ($icon && $iconPosition === 'right' ? ' pr-10' : '')]) }}
        />
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-superauth::icons.{{ $icon }} class="h-4 w-4 text-gray-400" />
            </div>
        @endif
    </div>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400">{{ $error }}</p>
    @elseif($help)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>

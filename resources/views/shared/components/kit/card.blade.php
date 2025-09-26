@props([
    'variant' => 'default',
    'padding' => 'md',
    'shadow' => 'md',
    'border' => true,
    'glass' => false
])

@php
$baseClasses = 'rounded-xl transition-all duration-200';
$variantClasses = [
    'default' => 'bg-white dark:bg-gray-800',
    'primary' => 'bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20',
    'success' => 'bg-green-50 dark:bg-green-900/20',
    'warning' => 'bg-yellow-50 dark:bg-yellow-900/20',
    'danger' => 'bg-red-50 dark:bg-red-900/20',
    'info' => 'bg-blue-50 dark:bg-blue-900/20'
];
$paddingClasses = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
    'xl' => 'p-10'
];
$shadowClasses = [
    'none' => '',
    'sm' => 'shadow-sm',
    'md' => 'shadow-lg',
    'lg' => 'shadow-xl',
    'xl' => 'shadow-2xl'
];
$borderClasses = $border ? 'border border-gray-200 dark:border-gray-700' : '';
$glassClasses = $glass ? 'glass-morphism backdrop-blur-sm' : '';

$cardClasses = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $paddingClasses[$padding] . ' ' . $shadowClasses[$shadow] . ' ' . $borderClasses . ' ' . $glassClasses;
@endphp

<div {{ $attributes->merge(['class' => $cardClasses]) }}>
    {{ $slot }}
</div>

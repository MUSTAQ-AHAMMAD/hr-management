@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-cobalt-500 focus:ring-cobalt-500 rounded-md shadow-sm']) }}>

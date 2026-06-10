@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-clinic-500 focus:ring-clinic-500 rounded-xl shadow-sm bg-gray-200/50 focus:bg-white transition-all']) }}>


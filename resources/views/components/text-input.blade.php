@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 focus:border-clinic-500 focus:ring-clinic-500 rounded-xl shadow-sm bg-slate-50/50 focus:bg-white transition-all']) }}>


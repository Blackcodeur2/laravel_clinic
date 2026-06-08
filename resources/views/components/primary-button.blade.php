<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-clinic-600 border border-transparent rounded-xl font-semibold text-sm text-white tracking-wide hover:bg-clinic-700 focus:bg-clinic-700 active:bg-clinic-800 focus:outline-none focus:ring-2 focus:ring-clinic-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md shadow-clinic-600/10 hover:shadow-clinic-600/20']) }}>
    {{ $slot }}
</button>


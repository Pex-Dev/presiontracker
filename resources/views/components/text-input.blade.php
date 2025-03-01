@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full border bg-white border-gray-400 outline-none focus:border-blue-500  rounded p-2']) }}>

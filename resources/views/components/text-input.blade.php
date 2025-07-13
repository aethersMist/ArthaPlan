@props(['disabled' => false, 'autocomplete' => 'on'])

<input 
    @disabled($disabled) 
    {{ $attributes->merge([
        'class' => 'block w-full p-2.5 border-netral-light focus:border-accent focus:ring-accent rounded-lg shadow-lg',
        'autocomplete' => $autocomplete
    ]) }}
>

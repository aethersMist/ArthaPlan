<button {{ $attributes->merge(['type' => 'submit', 'button', 'class' => 'flex items-center justify-center px-4 py-2 text-light bg-accent rounded hover:bg-primary transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

<div class="relative">
    <input 
        type="password" 
        class="form-input w-full pr-10" 
        {{ $attributes }}
    >
    <a 
        class="absolute top-0 right-0 bottom-0 flex items-center justify-center text-xs font-medium pr-2"
        onclick="
            const pwd = event.target.parentNode.querySelector('input');
            
            if (pwd.getAttribute('type') === 'password') {
                pwd.setAttribute('type', 'text');
                event.target.innerHTML = 'hide'
            }
            else {
                pwd.setAttribute('type', 'password')
                event.target.innerHTML = 'show'
            }
        "
    >
        show
    </a>
</div>

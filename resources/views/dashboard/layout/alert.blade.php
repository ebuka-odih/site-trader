
<!-- Toast container in the layout -->
{{--<div id="toast-container" style="position: fixed; top: 50px; right: 100px; z-index: 9999;"></div>--}}
<div id="toast-container" style="position: fixed; bottom: 40px; right: 70px; z-index: 9999;"></div>
{{--<div id="toast-container" style="position: fixed; bottom: 50%; left: 50%; transform: translate(-50%, 50%); z-index: 9999;"></div>--}}

<style>
    /* Toast styling */
    #toast-container .toast {
        background-color: #fff;
        border-left: 5px solid;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        transition: transform 0.5s ease, opacity 0.5s ease;
        opacity: 1;
        transform: translateY(0);
    }

    #toast-container .toast-success {
        border-color: #28a745;
        color: #28a745;
    }

    #toast-container .toast-error {
        border-color: #dc3545;
        color: #dc3545;
    }

    #toast-container .toast button {
        color: #000;
        border: none;
        background: none;
        font-size: 16px;
        cursor: pointer;
    }

    /* Animation for toast removal */
    #toast-container .toast[style*="remove"] {
        opacity: 0;
        transform: translateY(-50px);
    }
</style>

<script>
    // Function to show toast
    function showToast(type, message) {
        // Create the toast element
        const toast = document.createElement('div');
        toast.classList.add('toast');
        toast.classList.add(type === 'success' ? 'toast-success' : 'toast-error');

        // Set the toast content
        toast.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: center; width: 300px">
                <strong>${type === 'success' ? 'Success' : 'Error'}</strong>
                <button onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
            <div>${message}</div>
        `;

        // Append the toast to the container
        document.getElementById('toast-container').appendChild(toast);

        // Auto-remove toast after 5 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-50px)';
            setTimeout(() => toast.remove(), 500);  // Remove after animation
        }, 5000);
    }

    // Show success message if available in session
    @if(session()->has('success'))
        showToast('success', '{{ session()->get('success') }}');
    @endif

    // Show error message if available in session
    @if(session()->has('error'))
        showToast('error', '{{ session()->get('error') }}');
    @endif

    // Show validation errors if available
    @if ($errors->any())
        let errorMessages = `
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>`;
        showToast('error', errorMessages);
    @endif
</script>

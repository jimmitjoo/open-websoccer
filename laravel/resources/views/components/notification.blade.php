<div x-data="{ show: false, message: '' }" x-on:saved="show = true; message = 'Ändringarna har sparats'"
    x-on:role-updated="show = true; message = 'Användarens roll har uppdaterats'" x-show="show" x-transition
    x-init="setTimeout(() => show = false, 2000)" class="fixed bottom-0 right-0 m-6 p-4 rounded-lg bg-green-500 text-white shadow-lg"
    style="display: none;">
    <p x-text="message"></p>
</div>

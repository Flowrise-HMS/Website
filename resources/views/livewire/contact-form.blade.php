<div class="website-form-wrap">
    @if ($submitted)
        <div class="website-alert" role="status">{{ __('Thank you. Your message has been received.') }}</div>
    @else
        <form class="website-form" wire:submit="submit">
            <div>
                <label for="website-contact-name">{{ __('Name') }}</label>
                <input id="website-contact-name" type="text" wire:model="name" required />
                @error('name') <span>{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="website-contact-email">{{ __('Email') }}</label>
                <input id="website-contact-email" type="email" wire:model="email" required />
                @error('email') <span>{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="website-contact-phone">{{ __('Phone') }}</label>
                <input id="website-contact-phone" type="text" wire:model="phone" />
            </div>
            <div>
                <label for="website-contact-subject">{{ __('Subject') }}</label>
                <input id="website-contact-subject" type="text" wire:model="subject" />
            </div>
            <div>
                <label for="website-contact-message">{{ __('Message') }}</label>
                <textarea id="website-contact-message" rows="5" wire:model="message" required></textarea>
                @error('message') <span>{{ $message }}</span> @enderror
            </div>
            <button type="submit" wire:loading.attr="disabled">{{ __('Send message') }}</button>
        </form>
    @endif
</div>

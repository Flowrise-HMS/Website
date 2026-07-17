<div class="website-booking">
    @if (! $bookingAvailable)
        <div class="website-prose">
            <p>{{ $cta['message'] ?? __('Online booking is currently unavailable. Please call or message us.') }}</p>
            @if (! empty($cta['phone']))
                <p><a class="website-btn" href="tel:{{ $cta['phone'] }}">{{ __('Call') }} {{ $cta['phone'] }}</a></p>
            @endif
            @if (! empty($cta['url']))
                <p><a class="website-btn" href="{{ $cta['url'] }}" target="_blank" rel="noopener">WhatsApp</a></p>
            @endif
        </div>
    @elseif ($step === 'done')
        <div class="website-prose" wire:key="done">
            <p>{{ $resultMessage }}</p>
            @if ($resultReference)
                <p><strong>{{ __('Reference') }}:</strong> {{ $resultReference }}</p>
            @endif
            <p>{{ __('We sent confirmation details to your email and/or phone when available.') }}</p>
        </div>
    @else
        @if ($step === 'service')
            <h2 class="website-section__title">{{ __('Choose a service') }}</h2>
            @if ($services === [])
                <p>{{ __('No online-bookable services are configured yet.') }}</p>
            @else
                <ul class="website-booking__list">
                    @foreach ($services as $service)
                        <li>
                            <button type="button" class="website-btn" wire:click="selectService('{{ $service['id'] }}')">
                                {{ $service['name'] }}
                                @if ($service['duration_minutes'])
                                    <span>({{ $service['duration_minutes'] }} {{ __('min') }})</span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif

        @if ($step === 'identity')
            <h2 class="website-section__title">{{ __('Find your record') }}</h2>
            <p>{{ __('Search by MRN, phone, email, or national ID. Matches stay masked until you verify with OTP.') }}</p>
            <div class="website-form">
                <label>
                    <span>{{ __('Your ID') }}</span>
                    <input type="text" wire:model="identifier" />
                    @error('identifier') <span class="website-error">{{ $message }}</span> @enderror
                </label>
                <button type="button" class="website-btn" wire:click="searchPatient">{{ __('Search') }}</button>
                <button type="button" class="website-btn" wire:click="continueAsGuest">{{ __('Continue as guest') }}</button>
            </div>
            @if ($matches !== [])
                <ul class="website-booking__list">
                    @foreach ($matches as $match)
                        <li>
                            <button type="button" class="website-btn" wire:click="chooseMatch('{{ $match['id'] }}')">
                                {{ $match['masked_name'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif

        @if ($step === 'otp')
            <h2 class="website-section__title">{{ __('Verify with OTP') }}</h2>
            <p>{{ __('Enter the code we sent to your email and/or SMS.') }}</p>
            <div class="website-form">
                <label>
                    <span>{{ __('Verification code') }}</span>
                    <input type="text" wire:model="otp" maxlength="6" />
                    @error('otp') <span class="website-error">{{ $message }}</span> @enderror
                </label>
                <button type="button" class="website-btn" wire:click="verifyOtp">{{ __('Verify') }}</button>
            </div>
        @endif

        @if ($step === 'guest')
            <h2 class="website-section__title">{{ __('Guest details') }}</h2>
            <div class="website-form">
                <label><span>{{ __('First name') }}</span><input type="text" wire:model="guestFirstName" />@error('guestFirstName') <span class="website-error">{{ $message }}</span> @enderror</label>
                <label><span>{{ __('Last name') }}</span><input type="text" wire:model="guestLastName" />@error('guestLastName') <span class="website-error">{{ $message }}</span> @enderror</label>
                <label><span>{{ __('Phone') }}</span><input type="text" wire:model="guestPhone" />@error('guestPhone') <span class="website-error">{{ $message }}</span> @enderror</label>
                <label><span>{{ __('Email') }}</span><input type="email" wire:model="guestEmail" />@error('guestEmail') <span class="website-error">{{ $message }}</span> @enderror</label>
                <label><span>{{ __('National ID (optional)') }}</span><input type="text" wire:model="guestNationalId" /></label>
                <button type="button" class="website-btn" wire:click="submitGuest">{{ __('Continue') }}</button>
            </div>
        @endif

        @if ($step === 'slots')
            <h2 class="website-section__title">{{ __('Choose a time') }}</h2>
            <p>{{ __('Booking for') }}: <strong>{{ $patientName }}</strong></p>
            @if ($slots !== [])
                <ul class="website-booking__list">
                    @foreach ($slots as $slot)
                        <li>
                            <button
                                type="button"
                                class="website-btn {{ $selectedSlotStartsAt === $slot['starts_at'] ? 'is-active' : '' }}"
                                wire:click="selectSlot('{{ $slot['starts_at'] }}', '{{ $slot['ends_at'] }}')"
                            >
                                {{ $slot['label'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="website-form">
                    <label><span>{{ __('Notes (optional)') }}</span><textarea wire:model="notes" rows="3"></textarea></label>
                    <button type="button" class="website-btn" wire:click="confirmBooking" @disabled(! $selectedSlotStartsAt)>{{ __('Confirm appointment') }}</button>
                </div>
            @else
                <p>{{ __('No open slots right now. Join the waitlist or request a preferred time.') }}</p>
                <div class="website-form">
                    <button type="button" class="website-btn" wire:click="chooseWaitlist">{{ __('Join waitlist') }}</button>
                    <button type="button" class="website-btn" wire:click="choosePreferred">{{ __('Request preferred time') }}</button>
                    @if ($fallbackMode === 'preferred')
                        <label><span>{{ __('Preferred date & time') }}</span><input type="datetime-local" wire:model="preferredStartsAt" />@error('preferredStartsAt') <span class="website-error">{{ $message }}</span> @enderror</label>
                    @endif
                    <label><span>{{ __('Notes (optional)') }}</span><textarea wire:model="notes" rows="3"></textarea></label>
                    <button type="button" class="website-btn" wire:click="confirmBooking" @disabled($fallbackMode === '')>{{ __('Submit request') }}</button>
                </div>
            @endif
            @error('selectedSlotStartsAt') <span class="website-error">{{ $message }}</span> @enderror
            @error('slot') <span class="website-error">{{ $message }}</span> @enderror
            @error('serviceId') <span class="website-error">{{ $message }}</span> @enderror
        @endif
    @endif
</div>

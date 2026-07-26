@component('mail::message')
# New referral received

**Referred person:** {{ $referral->referred_first_name }}
**Cohort:** {{ $referral->cohort ?? 'not stated' }}
**Contact (consented):** {{ $referral->referred_contact ?? 'none provided, follow up via referrer' }}

---

**Referrer:** {{ $referral->referrer_name }} ({{ $referral->referrer_email }})
**Organisation:** {{ $referral->referrer_organisation ?? 'not stated' }}
**Role:** {{ $referral->referrer_role ?? 'not stated' }}

---

**Context:**

{{ $referral->context ?? 'none provided' }}

Submitted {{ $referral->created_at->format('j F Y, H:i') }} UK time.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

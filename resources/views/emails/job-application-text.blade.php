NEW JOB APPLICATION

{!! $applicantName !!} applied for {!! $roleTitle !!}.

CONTACT
{!! $applicantEmail !!}
@if ($phone){!! $phone !!}
@endif

WHY THIS ROLE FITS THEM
{!! $coverNote !!}

CV
{!! $cvName ? $cvName.' (open it from the applications screen)' : 'None attached' !!}
@if ($portfolioUrl)

PORTFOLIO
{!! $portfolioUrl !!}
@endif

Applied {!! $appliedAt !!}.

Applications screen: {!! $adminUrl !!}

--
Skills Co-op
{!! $supportEmail !!}

{!! $footerNote !!}

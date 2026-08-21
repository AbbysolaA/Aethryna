@extends('layouts.aethryna')

@section('title', 'Jobs at Skills Co-op | Careers')
@section('meta_description', 'Open roles at Skills Co-op, a UK community interest company delivering free digital skills and AI literacy training. Remote first.')
@section('og_title', 'Careers at Skills Co-op')

@push('styles')
    @include('careers._styles')
@endpush

@section('content')

<div class="cr">

    <section class="cr-hero">
        <div class="cr-wrap">
            <p class="cr-eyebrow">Careers</p>
            <h1>Work with us</h1>
            <p class="cr-lede">
                We are a community interest company delivering free digital skills, AI literacy
                and employability training to people the labour market overlooks. Small team,
                remote first, and everyone here owns something real.
            </p>
        </div>
    </section>

    <section class="cr-body">
        <div class="cr-wrap">

            @if ($roles->isEmpty())
                <div class="cr-empty">
                    <p><strong>No roles are open at the moment.</strong></p>
                    <p>
                        We are still glad to hear from people who want to get involved.
                        <a href="{{ route('volunteer.apply') }}">See how you can volunteer</a>,
                        or <a href="{{ route('mentors') }}">read what mentoring involves</a>.
                    </p>
                </div>
            @else
                <ul class="cr-list">
                    @foreach ($roles as $role)
                        <li class="cr-card">
                            <h2><a href="{{ $role->url() }}">{{ $role->title }}</a></h2>

                            <ul class="cr-facts">
                                @if ($role->employment_basis)
                                    <li class="cr-fact">{{ $role->employment_basis }}</li>
                                @endif
                                @if ($role->location)
                                    <li class="cr-fact">{{ \Illuminate\Support\Str::before($role->location, ',') }}</li>
                                @endif
                                {{-- Only when there is a figure. An empty "Salary:" row
                                     invites the reader to assume the worst. --}}
                                @if ($role->compensation)
                                    <li class="cr-fact">{{ $role->compensation }}</li>
                                @endif
                            </ul>

                            <p>{{ $role->summary }}</p>

                            <a href="{{ $role->url() }}" class="ath-btn ath-btn-primary">
                                Read the full description
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- Volunteering runs through a different form, so it is signposted
                 rather than mixed into the list above. Framed as another door
                 rather than as the unpaid one: nobody is looking for a way to
                 work for nothing, and saying so out loud reads badly whichever
                 way it is meant. --}}
            <p style="margin-top:32px;color:#59626A;line-height:1.7;">
                Not what you are looking for?
                <a href="{{ route('volunteer.apply') }}">There are other ways to get involved</a>.
            </p>

        </div>
    </section>

</div>

@endsection

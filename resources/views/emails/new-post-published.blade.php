{{--
    A new blog post, to a subscriber.

    Data expected (see App\Mail\NewPostPublished):
        postTitle, standfirst, authorName, minutes, postUrl, unsubscribeUrl
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                New on the blog
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                {{ $postTitle }}
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                {{ $standfirst }}
            </p>
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; line-height:21px; color:#8a8f86;">
                {{ $authorName }} &middot; about a {{ $minutes }} minute read
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 36px 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="background-color:#ee9d1d; border-radius:8px;">
                        <a href="{{ $postUrl }}" style="display:inline-block; padding:13px 26px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#08444A; text-decoration:none;">
                            Read the post
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:0 32px 36px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; line-height:20px; color:#8a8f86;">
                Had enough of these?
                <a href="{{ $unsubscribeUrl }}" style="color:#055860;">Unsubscribe with one click</a>
                and we will not write again.
            </p>
        </td>
    </tr>

@endsection

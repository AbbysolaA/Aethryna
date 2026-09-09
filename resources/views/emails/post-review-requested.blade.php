{{--
    To the admin inbox when a content writer submits a post for review.

    Data expected (see App\Mail\PostReviewRequested):
        writerName, postTitle, standfirst, minutes, previewUrl, editUrl
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                The blog
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                A post is waiting for review
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                {{ $writerName }} has finished a post and submitted it for review.
                Nothing is public until you press publish.
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:8px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:18px 22px;">
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:17px; font-weight:700; line-height:25px; color:#055860;">
                            {{ $postTitle }}
                        </p>
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                            {{ $standfirst }}
                        </p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; line-height:21px; color:#8a8f86;">
                            About a {{ $minutes }} minute read.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="background-color:#ee9d1d; border-radius:8px;">
                        <a href="{{ $previewUrl }}" style="display:inline-block; padding:13px 26px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#08444A; text-decoration:none;">
                            Read the post
                        </a>
                    </td>
                    <td style="padding-left:14px;">
                        <a href="{{ $editUrl }}" style="font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; color:#055860; text-decoration:underline;">
                            Open it in admin
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection

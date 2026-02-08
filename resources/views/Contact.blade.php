@extends('layout')

@section('title', 'Electronic Mart')
@section('content')
<style>
    /* From Uiverse.io by zymantas-katinas */
    .button {
        position: relative;
        border: none;
        background: transparent;
        padding: 0;
        outline: none;
        cursor: pointer;
        font-family: sans-serif;
    }

    /* Shadow layer */
    .button .shadow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.25);
        border-radius: 8px;
        transform: translateY(2px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
    }

    /* Edge layer */
    .button .edge {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 8px;
        background: linear-gradient(to left,
                hsl(217, 33%, 16%) 0%,
                hsl(217, 33%, 32%) 8%,
                hsl(217, 33%, 32%) 92%,
                hsl(217, 33%, 16%) 100%);
    }

    /* Front layer */
    .button .front {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        font-size: 1.25rem;
        color: white;
        background: hsl(218, 18%, 9%);
        border-radius: 8px;
        transform: translateY(-4px);
        transition: transform 600ms cubic-bezier(0.3, 0.7, 0.4, 1);
    }

    /* Hover and active states */
    .button:hover .shadow {
        transform: translateY(4px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }

    .button:hover .front {
        transform: translateY(-6px);
        transition: transform 250ms cubic-bezier(0.3, 0.7, 0.4, 1.5);
    }

    .button:active .shadow {
        transform: translateY(1px);
        transition: transform 34ms;
    }

    .button:active .front {
        transform: translateY(-2px);
        transition: transform 34ms;
    }

    /* Disable text selection */
    .button .front span {
        user-select: none;
    }
</style>
<div class="container">
    <div class="row my-5">
        <div class="col-lg-5">
            <h2 style="font-weight: 900;">Connect Us</h2>
            <div class="d-flex">
                <span class="fa fa-globe mt-2" style="font-size: 25px;color: rgba(0, 0, 0, 0.08);"></span>
                <div class="para ms-2">
                    <h4 style="font-weight: 700;">Company Adress</h4>
                    <p style="color: rgb(168, 168, 150);">1001,5th Avenue</p>
                </div>
            </div>
            <div class="d-flex mt-2">
                <span class="fa fa-phone mt-2" style="font-size: 25px;color: rgba(0, 0, 0, 0.08);"></span>
                <div class="para ms-2">
                    <h4 style="font-weight: 700;">Call Us</h4>
                    <p style="color: rgb(168, 168, 150);">0301-3946090</p>
                </div>
            </div>
            <div class="d-flex mt-2">
                <span class="fa fa-envelope-open mt-2" style="font-size: 25px;color: rgba(0, 0, 0, 0.08);"></span>
                <div class="para ms-2">
                    <h4 style="font-weight: 700;">Email Us</h4>
                    <p style="color: rgb(168, 168, 150);">info@gmail.com</p>
                </div>
            </div>
            <div class="d-flex mt-2">
                <span class="fa fa-headphones mt-2" style="font-size: 25px;color: rgba(0, 0, 0, 0.08);"></span>
                <div class="para ms-2">
                    <h4 style="font-weight: 700;">For Support</h4>
                    <p style="color: rgb(168, 168, 150);">info@support.com</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="col-md-7 contact-right mt-md-0 mt-4">
                <form action="https://sendmail.w3layouts.com/submitForm" method="post" class="signin-form">
                    <div class="input-grids">
                        <input type="text" class="input-group-text px-5 mt-3" placeholder="Your Name*"
                            class="contact-input" required="" />
                        <input type="email" class="input-group-text mt-3 px-5" placeholder="Your Email*"
                            class="contact-input" required="" />
                        <input type="text" class="input-group-text mt-3 px-5" placeholder="Subject*"
                            class="contact-input" required="" />
                        <input type="text" class="input-group-text mt-3 px-5" placeholder="Website URL*"
                            class="contact-input" required="" />
                    </div>
                    <div class="form-input">
                        <textarea placeholder="Type your message here*" class="input-group-text mt-4  px-5"
                            required=""></textarea>
                    </div>
                    <!-- From Uiverse.io by zymantas-katinas -->
                    <button class="button mt-5">
                        <span class="shadow"></span>
                        <span class="edge"></span>
                        <div class="front">
                            <span>Send Message</span>
                        </div>
                    </button>

            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.master')

@section('extra-css')
    
        <style>

            .full-height {
                height: 100vh;
            }

            .flex-center {
                top: -100px;
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

 
            .content {
                border-radius: 10px;
                padding: 70px 30px;
                text-align: center;
                background: #76d2c0;
                animation: boxfade 15s ease -2s normal;
            }

            .title {
                font-size: 84px;
            }

            @keyframes boxfade{
                from{
                    opacity: 0;
                }
                to{
                    opacity: 1;
                }
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
@endsection

@section('content')

    <section class="intro">
        <h3 style=" text-align: center; margin: 0px;color: #e6e6e6; font-size: 180px; font-weight: 800; letter-spacing: -10px; line-height: 60px;">Welcome</h3>


        <div class="flex-center position-ref full-height">
        

            <div class="content">

                <div class="title m-b-md">
                    Unified Collaboration PBX
                </div>
                <span>
                    Powered By TME.
                </span>

                <div class="links">
                    <a href="https://www.techmadeeazy.com"> TECHMADEEAZY SOLUTIONS </a>
                    <a href="{{ url('api/documentation') }}">API </a>
                </div>
            </div>
        </div>
    </section>
@endsection
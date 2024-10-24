@extends('layouts.app')

@section('contents')
    <div class="container-fluid">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a class="btn btn-danger" href="/" role="button">ย้อนกลับ</a>
        </div>
        <hr />
        <h1 class="text-center mb-4" style="color: #555555"></h1>
        <div class="row row-cols-1 row-cols-lg-3">
            <div class="col mb-3">
                <a href="{{ route('outsidewarehouses') }}" class="card-link">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <span style="font-size: 20px; white-space: nowrap;">วัสดุนอกคลัง</span>
                            <span style="font-size: 16px; white-space: nowrap;">จำนวน: {{ $PLAN_1 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col mb-3">
                <a href="{{ route('insidewarehouses') }}" class="card-link">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <span style="font-size: 20px; white-space: nowrap;">วัสดุในคลัง</span>
                            <span style="font-size: 16px; white-space: nowrap;">จำนวน: {{ $PLAN_2 }}</span>
                        </div>
                    </div>
                </a>

            </div>
        </div>
        <style>
            .card-link {
                text-decoration: none;
                transition: transform 0.3s ease;
                display: block;
            }


            .card {
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.5s, box-shadow 0.2s;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                background-color: rgb(76, 164, 247);
                color: #000000
            }
        </style>

    </div>
@endsection

@extends('layouts.app')

@section('contents')
   <div class="container-fluid">
      <h1 class="section-title">ขออนุมัติ</h1>
      <div class="row row-cols-1 row-cols-lg-2">
         <div class="col mb-4 text-center">
            <a href="{{ route('approved_items_plans') }}" class="card-link">
               <div class="card h-100">
                  <div class="card-body">
                     <span class="card-title">แผนจัดซื้อจัดจ้าง</span>
                     <span>จำนวน: <strong>{{ $E_PLAN_COUNT }}</strong> รายการ</span>

                  </div>
               </div>
            </a>
         </div>
         <div class="col mb-4 text-center">
            <a href="{{ route('approved_items_stores') }}" class="card-link">
               <div class="card h-100">
                  <div class="card-body">
                     <span class="card-title">แผนวัสดุคลังย่อย</span>
                     <span>จำนวน: <strong>{{ $E_PLAN_PRODUCT_COUNT }}</strong> รายการ</span>
                  </div>
               </div>
            </a>
         </div>
      </div>
      <hr>

      <h1 class="section-title">ผ่านอนุมัติ</h1>
      <div class="row row-cols-1 row-cols-lg-2">
         <div class="col mb-4 text-center">
            <a href="{{ route('passed_approved_items') }}" class="card-link">
               <div class="card h-100">
                  <div class="card-body">
                     <span class="card-title">แผนจัดซื้อจัดจ้าง</span>
                     <span>จำนวน: <strong>{{ $E_PLAN_PASSED_COUNT }}</strong> รายการ</span>

                  </div>
               </div>
            </a>
         </div>
         {{-- <div class="col mb-4 text-center">
            <a href="" class="card-link">
               <div class="card h-100">
                  <div class="card-body">
                     <span class="card-title">แผนวัสดุคลังย่อย</span>
                  </div>
               </div>
            </a>
         </div> --}}
      </div>
      <hr>

      <h1 class="section-title">ไม่ผ่านอนุมัติ</h1>
      <div class="row row-cols-1 row-cols-lg-2">
         <div class="col mb-4 text-center">
            <a href="{{ route('no_passed_approved_items') }}" class="card-link">
               <div class="card h-100">
                  <div class="card-body">
                     <span class="card-title">แผนจัดซื้อจัดจ้าง</span>
                     <span>จำนวน: <strong>{{ $E_PLAN_NO_PASSED_COUNT }}</strong> รายการ</span>

                  </div>
               </div>
            </a>
         </div>
         {{-- <div class="col mb-4 text-center">
            <a href="" class="card-link">
               <div class="card h-100">
                  <div class="card-body">
                     <span class="card-title">แผนวัสดุคลังย่อย</span>
                  </div>
               </div>
            </a>
         </div> --}}
      </div>

      <style>
         .section-title {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
         }

         .card-link {
            text-decoration: none;
            transition: transform 0.2s ease, background-color 0.2s ease;
            display: block;
         }

         .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            min-height: 150px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
         }

         .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
            background-color: #f0f0f0;
         }

         .card-title {
            font-size: 18px;
            font-weight: 500;
            color: #333;
         }

         .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
         }
      </style>

   </div>
@endsection

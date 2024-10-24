@extends('layouts.app')

@section('title', 'ผ่านอนุมัติ - แผนจัดซื้อจัดจ้าง')

@section('contents')
   <style>
      .btn1 {
         font-size: 0.75em;
         padding: 0.05em 0.5em;
      }
   </style>
   <div style="display: flex; justify-content: space-between; align-items: center;">
      <a class="btn btn-danger" href="javascript:history.back()">ย้อนกลับ</a>
   </div>
   <hr>
   <div style="max-width: 100%; overflow-x: auto; overflow-y: auto;">

      <table id="plan_table" class="table-bordered table" style="width:100%">
         <thead class="table-primary">
            <tr>
               <th class="text-center">รายการแผนงาน</th>
               <th class="text-center">ราคาโดยประมาณ</th>
               <th class="text-center">เหตุผลและความจำเป็น</th>
               <th class="text-center">รายละเอียดเพิ่มเติม</th>
               <th class="text-center">การจัดการ</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($E_PLAN_PASSED as $item)
               <tr>
                  <td>
                     เลขที่แผน: ผ.{{ $item->PLAN_ID }} <br>
                     ชื่อแผน: {{ $item->PLAN_NAME }}<br>
                     ปีงบประมาณ: {{ $item->PLAN_SET_YEAR }} <br>

                     แผนฯ: @if ($item->USAGE_STATUS_ID == 1)
                        <span class="badge bg-success">จริง</span>
                     @elseif($item->USAGE_STATUS_ID == 2)
                        <span class="badge bg-secondary">สำรอง</span>
                     @endif

                  </td>
                  <td style="white-space: nowrap;">
                     <span style="color: #2a60f3">วงเงินรวม:
                        {{ number_format(round($item->PLAN_PRICE_PER * $item->PLAN_QTY, 2), 2) }}
                        บาท</span>
                     <br>
                     @if ($item->Total_Used === null || $item->Total_Used == 0)
                        จำนวนครุภัณฑ์ที่ตั้งไว้:
                        0/{{ number_format(round($item->PLAN_QTY, 2), 0) }}
                        <br> ใช้ไปแล้ว: 0 บาท
                        <br> คงเหลือ: 0 บาท
                     @else
                        จำนวนครุภัณฑ์ที่ตั้งไว้:
                        {{ $item->Total_Used }}/{{ number_format(round($item->PLAN_QTY, 2), 0) }}
                        <br>ใช้ไปแล้ว:<span style="color: green">
                           {{ number_format(round($item->Total_Current_Price, 2), 2) }}
                        </span>บาท
                        <br>คงเหลือ:
                        <span style="color: red">{{ number_format(round($item->Remaining_Price, 2), 2) }}
                        </span>บาท
                     @endif

                  </td>
                  <td>
                     {{ $item->PLAN_REASON }}
                  </td>
                  <td style="text-align: left;">
                     วันที่สร้างแผน
                     {{ \Carbon\Carbon::createFromFormat('d/m/Y H:i', $item->CREATE_DATE)->addYears(543)->format('d/m/Y H:i') }}

                     <br>
                     หน่วยงานที่เบิก: {{ $item->TCHN_LOCAT_NAME }} <br>
                     ประเภทงบ: {{ $item->Budget_NAME }}
                  </td>
                  <td class="text-center align-middle">
                     <div class="btn-group" role="group">
                        <a href="{{ route('maintenances.show', $item->PLAN_ID) }}" type="button" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">ดูข้อมูล</a>
                        <form action="{{ route('maintenances.destroy', $item->PLAN_ID) }}" method="POST" onsubmit="return confirm('Delete?')" class="p-0">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-outline-danger btn-sm" style="white-space: nowrap;">ปิดใช้งาน</button>
                        </form>
                     </div>
                     <br><br>
                     @if ($item->USAGE_STATUS_ID == 1 && Auth::user()->id == '114000041')
                        <form action="{{ route('maintenances.approved', $item->PLAN_ID) }}" method="POST">
                           @csrf
                           @method('PATCH')
                           @if ($item->REQ_APPROVED_ID == 0)
                              <span class="badge bg-secondary">ไม่ได้ขออนุมัติ</span>
                           @elseif($item->REQ_APPROVED_ID == 1 || $item->REQ_APPROVED_ID == 4)
                              <div class="btn-group" role="group">
                                 <button type="submit" name="REQ_APPROVED_ID" value="2" class="btn btn-success btn-sm">อนุมัติ</button>
                                 <button type="submit" name="REQ_APPROVED_ID" value="3" class="btn btn-secondary btn-sm">ไม่อนุมัติ</button>
                              </div>
                           @elseif($item->REQ_APPROVED_ID == 2)
                              <span class="badge bg-success">ผ่านอนุมัติ <button type="submit" name="REQ_APPROVED_ID" value="4" class="btn btn1 btn-warning"><i
                                       class="fa-solid fa-xmark"></i></button>
                              </span>
                           @elseif($item->REQ_APPROVED_ID == 3)
                              <span class="badge bg-danger">ไม่ผ่านอนุมัติ <button type="submit" name="REQ_APPROVED_ID" value="4" class="btn btn1 btn-warning"><i
                                       class="fa-solid fa-xmark"></i></button>
                              </span>
                           @endif
                        </form>
                     @endif

                     @if ($item->USAGE_STATUS_ID == 1 && Auth::user()->id != '114000041')
                        @if ($item->REQ_APPROVED_ID == 0)
                           <form action="{{ route('maintenances.approved', $item->PLAN_ID) }}" method="POST">
                              @csrf
                              @method('PATCH')
                              <button type="submit" name="REQ_APPROVED_ID" value="1" class="btn btn-primary btn-sm" style="white-space: nowrap;">
                                 ส่งขออนุมัติ
                              </button>
                           </form>
                        @elseif($item->REQ_APPROVED_ID == 1 || $item->REQ_APPROVED_ID == 4)
                           <span class="badge bg-warning text-dark">รออนุมัติ ติดต่อหน.การเงิน</span>
                        @elseif($item->REQ_APPROVED_ID == 2)
                           <span class="badge bg-success">ผ่านอนุมัติ</span>
                        @elseif($item->REQ_APPROVED_ID == 3)
                           <span class="badge bg-danger">ไม่ผ่านอนุมัติ</span>
                        @endif
                     @endif
                  </td>
               </tr>
            @endforeach
         </tbody>
      </table>
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', function() {

         $('#plan_table').DataTable({
            language: {
               "sProcessing": "กำลังดำเนินการ...",
               "sLengthMenu": "แสดง _MENU_ รายการ",
               "sZeroRecords": "ไม่พบข้อมูลในตาราง",
               "sEmptyTable": "ไม่มีข้อมูลในตาราง",
               "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
               "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
               "sInfoFiltered": "(กรองข้อมูลทั้งหมด _MAX_ รายการ)",
               "sSearch": "ค้นหา:",
               "sInfoThousands": ",",
               "sLoadingRecords": "กำลังโหลด...",
               "oPaginate": {
                  "sFirst": "หน้าแรก",
                  "sLast": "หน้าสุดท้าย",
                  "sNext": "ถัดไป",
                  "sPrevious": "ก่อนหน้า"
               }
            },
            order: [],
            lengthMenu: [
               [50, 100, -1],
               ['50', '100', 'ทั้งหมด']
            ],
            columnDefs: [{
                  // กำหนดคอลัมน์แรก
                  width: '40vh',
                  targets: 0
               },
               {
                  // กำหนดคอลัมน์แรก
                  width: '20vh',
                  targets: 1
               },
               {
                  // กำหนดคอลัมน์แรก
                  width: '40vh',
                  targets: 2
               },
               {
                  // กำหนดคอลัมน์แรก
                  width: '30vh',
                  targets: 3
               },
               {
                  width: '20vh',
                  orderable: false,
                  targets: 4
               }
            ],

         });
      });
   </script>
@endsection

@extends('layouts.app')

@section('title', 'แผนซ่อม')

@section('contents')
   <style>
      .btn1 {
         font-size: 0.75em;
         padding: 0.05em 0.5em;
      }
   </style>

   <div class="mb-3">
      @if ($E_CLOSE_PLAN->status == 'Y')
         <button type="button" class="btn btn-primary" id="addItemButton" data-bs-toggle="modal" data-bs-target="#addModal" data-action="{{ route('repairs.store') }}">
            + เพิ่มรายการ
         </button>
      @endif

      @if (Auth::user()->id == '114000041')
         <div class="ml-4 mt-2">
            <div class="form-check form-switch mr-5">
               <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" @if ($E_CLOSE_PLAN->status == 'Y') checked @endif data-id="{{ $E_CLOSE_PLAN->id }}">
               <label class="form-check-label" for="flexSwitchCheckDefault">เปิดการเพิ่มแผนฯ</label>
            </div>
         </div>
      @endif
   </div>

   <div style="display: flex; justify-content: space-between; align-items: center;">

      <p>
         <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <i class="fa-solid fa-magnifying-glass"></i> กรองข้อมูล
         </button>
      </p>

      <hr />
      <a href="{{ route('export.data', ['type' => 'repairs']) }}" class="btn btn-success mb-2">Export to
         Excel</a>
   </div>
   <div class="collapse mb-3" id="collapseExample">
      <div class="card card-body">
         <div class="row">
            <div class="col-md-3">
               <select class="form-select" id="filterSelectID">
                  <option value="" selected hidden>เลือกปีงบประมาณ</option>
                  <!-- ตัวเลือกจะถูกเติมที่นี่โดย JavaScript -->
               </select>
            </div>
            <div class="col-md-3">
               <select class="form-select" id="filterSelectTCHN">
                  <option value="" selected hidden>เลือกหน่วยงาน</option>
                  <!-- ตัวเลือกจะถูกเติมที่นี่โดย JavaScript -->
               </select>
            </div>
            <div class="col-md-3">

               <button id="resetButton" class="btn btn-secondary">Reset</button>
            </div>

         </div>
      </div>
   </div>
   <hr />
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
            @foreach ($E_PLAN as $item)
               <tr>
                  <td>
                     เลขที่แผน: ผ.{{ $item->PLAN_ID }} <br>
                     ชื่อแผน: {{ $item->PLAN_NAME }}<br>
                     ปีงบประมาณ: {{ $item->PLAN_SET_YEAR }} <br>

                     ประเภทแผน: {{ $item->TYPE_NAME }}

                     @if ($item->USAGE_STATUS_ID == 1)
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
                        <a href="{{ route('repairs.show', $item->PLAN_ID) }}" type="button" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">ดูข้อมูล</a>
                        <form action="{{ route('repairs.destroy', $item->PLAN_ID) }}" method="POST" onsubmit="return confirm('Delete?')" class="p-0">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="btn btn-outline-danger btn-sm" style="white-space: nowrap;">ปิดใช้งาน</button>
                        </form>
                     </div>
                     <br><br>
                     @if ($item->USAGE_STATUS_ID == 1 && Auth::user()->id == '114000041')
                        <form id="approval-form-{{ $item->PLAN_ID }}" action="{{ route('repairs.approved', $item->PLAN_ID) }}" method="POST">
                           @csrf
                           @method('PATCH')
                           @if ($item->REQ_APPROVED_ID == 0)
                              <span class="badge bg-secondary">ไม่ได้ขออนุมัติ</span>
                           @elseif($item->REQ_APPROVED_ID == 1 || $item->REQ_APPROVED_ID == 4)
                              <div class="btn-group" id="button-group-{{ $item->PLAN_ID }}" role="group">
                                 <button class="btn btn-success btn-sm" type="button" onclick="submitApproval({{ $item->PLAN_ID }}, 2)">อนุมัติ</button>
                                 <button class="btn btn-secondary btn-sm" type="button" onclick="submitApproval({{ $item->PLAN_ID }}, 3)">ไม่อนุมัติ</button>
                              </div>
                           @elseif($item->REQ_APPROVED_ID == 2)
                              <div class="btn-group" id="button-group-{{ $item->PLAN_ID }}" role="group">

                                 <span class="badge bg-success">ผ่านอนุมัติ
                                    <button class="btn btn1 btn-warning" type="button" onclick="submitApproval({{ $item->PLAN_ID }}, 4)"><i class="fa-solid fa-xmark"></i></button>
                                 </span>
                              </div>
                           @elseif($item->REQ_APPROVED_ID == 3)
                              <div class="btn-group" id="button-group-{{ $item->PLAN_ID }}" role="group">

                                 <span class="badge bg-danger">ไม่ผ่านอนุมัติ
                                    <button class="btn btn1 btn-warning" type="button" onclick="submitApproval({{ $item->PLAN_ID }}, 4)"><i class="fa-solid fa-xmark"></i></button>
                                 </span>
                              </div>
                           @endif
                        </form>
                     @endif

                     @if ($item->USAGE_STATUS_ID == 1 && Auth::user()->id != '114000041')
                        @if ($item->REQ_APPROVED_ID == 0)
                           <form id="approval-form-{{ $item->PLAN_ID }}" action="{{ route('repairs.approved', $item->PLAN_ID) }}" method="POST">
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

   <!-- Include the modal -->
   @include('repairs.modal')
   <script>
      function submitApproval(planId, reqApprovedId) {
         const form = document.getElementById(`approval-form-${planId}`);
         const formData = new FormData(form);
         formData.append('REQ_APPROVED_ID', reqApprovedId);

         fetch(form.action, {
               method: 'POST',
               headers: {
                  'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                  'Accept': 'application/json',
               },
               body: formData
            })
            .then(response => response.json())
            .then(data => {
               if (data.success) {
                  toastr.success(data.message);

                  const buttonGroup = document.querySelector(`#button-group-${planId}`);

                  if (buttonGroup) {
                     if (reqApprovedId == 2) {
                        buttonGroup.innerHTML = `
                        <span class="badge bg-success">ผ่านอนุมัติ
                           <button type="button" class="btn btn1 btn-warning" onclick="submitApproval(${planId}, 4)"><i class="fa-solid fa-xmark"></i></button>
                        </span>
                     `;
                     } else if (reqApprovedId == 3) {
                        buttonGroup.innerHTML = `
                        <span class="badge bg-danger">ไม่ผ่านอนุมัติ
                           <button type="button" class="btn btn1 btn-warning" onclick="submitApproval(${planId}, 4)"><i class="fa-solid fa-xmark"></i></button>
                        </span>
                     `;
                     } else if (reqApprovedId == 1) {
                        buttonGroup.innerHTML = `
                        <button type="button" class="btn btn-success btn-sm" onclick="submitApproval(${planId}, 2)">อนุมัติ</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="submitApproval(${planId}, 3)">ไม่อนุมัติ</button>
                     `;
                     } else if (reqApprovedId == 4) {
                        buttonGroup.innerHTML = `
                        <button type="button" class="btn btn-success btn-sm" onclick="submitApproval(${planId}, 2)">อนุมัติ</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="submitApproval(${planId}, 3)">ไม่อนุมัติ</button>
                     `;
                     }
                  }
               } else {
                  toastr.error('เกิดข้อผิดพลาดในการส่งข้อมูล!');
               }
            })
            .catch(error => {
               console.error('Error:', error);
               toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์!');
            });
      }

      document.addEventListener('DOMContentLoaded', function() {
         var addModal = document.getElementById('addModal');
         var dynamicForm = document.getElementById('dynamicForm');

         addModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // ปุ่มที่คลิกเปิด Modal
            var action = button.getAttribute('data-action'); // ดึง action จาก data-attribute

            // ตั้งค่า action ของฟอร์มใหม่
            dynamicForm.setAttribute('action', action);
         });

         const table = $('#plan_table').DataTable({
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
               [10, 50, 100, -1],
               ['10', '50', '100', 'ทั้งหมด']
            ],
            columnDefs: [{
                  // กำหนดคอลัมน์แรก
                  width: '50vh',
                  orderable: false,
                  targets: 0
               },
               {
                  // กำหนดคอลัมน์แรก
                  width: '20vh',
                  orderable: false,
                  targets: 1
               },
               {
                  // กำหนดคอลัมน์แรก
                  width: '40vh',
                  orderable: false,
                  targets: 2
               },
               {
                  // กำหนดคอลัมน์แรก
                  width: '30vh',
                  orderable: false,
                  targets: 3
               },
               {
                  width: '20vh',
                  orderable: false,
                  targets: 4
               }
            ],

         });
         // Handle the filtering for the year select
         $('#filterSelectID').on('change', function() {
            const year = $(this).val();
            const location = $('#filterSelectTCHN').val();
            table.columns(0).search(year ? `ปีงบประมาณ: ${year}` : '', true, false);
            table.columns(3).search(location ? `หน่วยงานที่เบิก: ${location}` : '', true, false);
            table.draw();
         });

         // Handle the filtering for the location select
         $('#filterSelectTCHN').on('change', function() {
            const location = $(this).val();
            const year = $('#filterSelectID').val();
            table.columns(3).search(location ? `หน่วยงานที่เบิก: ${location}` : '', true, false);
            table.columns(0).search(year ? `ปีงบประมาณ: ${year}` : '', true, false);
            table.draw();
         });



         // Populate filter selects with unique values from the table
         const planTable = document.getElementById('plan_table');
         const filterSelectID = document.getElementById('filterSelectID');
         const filterSelectTCHN = document.getElementById('filterSelectTCHN');
         const uniqueYears = new Set();
         const uniqueLocations = new Set();

         // รวบรวมข้อมูลจากตาราง
         const rows = planTable.querySelectorAll('tbody tr');
         rows.forEach(row => {
            const year = row.cells[0].textContent.match(/ปีงบประมาณ: (.+)/)[1]?.trim();
            const location = row.cells[3].textContent.match(/หน่วยงานที่เบิก: (.+)/)[1]?.trim();
            if (year) uniqueYears.add(year);
            if (location) uniqueLocations.add(location);
         });

         // เติมค่าใน select ปีงบประมาณ
         uniqueYears.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            filterSelectID.appendChild(option);
         });

         // เติมค่าใน select หน่วยงาน
         uniqueLocations.forEach(location => {
            const option = document.createElement('option');
            option.value = location;
            option.textContent = location;
            filterSelectTCHN.appendChild(option);
         });

         document.getElementById('resetButton').addEventListener('click', function() {
            // Reset the filter dropdowns
            document.getElementById('filterSelectID').selectedIndex = 0; // Reset ปีงบประมาณ
            document.getElementById('filterSelectTCHN').selectedIndex = 0; // Reset หน่วยงาน

            table.search('').columns().search('')
               .draw(); // Clear all searches and redraw the table
         });

         document.querySelectorAll('.form-check-input').forEach((checkbox) => {
            checkbox.addEventListener('change', function() {
               const id = this.getAttribute('data-id');
               const status = this.checked ? 'Y' : 'N';

               fetch('{{ route('repairs.close_plan') }}', {
                     method: 'POST',
                     headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                     },
                     body: JSON.stringify({
                        id: id,
                        status: status
                     })
                  })
                  .then(response => response.json())
                  .then(data => {
                     console.log(data.message);

                     // Toggle the button visibility based on the status
                     const addItemButton = document.getElementById('addItemButton');
                     if (status === 'Y') {
                        if (!addItemButton) {
                           const button = document.createElement('button');
                           button.type = 'button';
                           button.classList.add('btn', 'btn-primary');
                           button.id = 'addItemButton';
                           button.setAttribute('data-bs-toggle', 'modal');
                           button.setAttribute('data-bs-target', '#addModal');
                           button.setAttribute('data-action',
                              '{{ route('repairs.store') }}');
                           button.textContent = '+ เพิ่มรายการ';
                           document.querySelector('.mb-3').prepend(button);
                        }
                     } else {
                        if (addItemButton) {
                           addItemButton.remove();
                        }
                     }
                  })
                  .catch(error => console.error('Error:', error));
            });
         });

      });
   </script>

@endsection

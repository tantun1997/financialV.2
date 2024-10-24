    @extends('layouts.app')

    @section('title', 'แผนวัสดุในคลัง')

    @section('contents')
       <style>
          .btn1 {
             font-size: 0.75em;
             padding: 0.05em 0.5em;
          }
       </style>

       <div class="mb-3">
          @if ($E_CLOSE_PLAN->status == 'Y')
             <button type="button" class="btn btn-primary" id="addItemButton" data-bs-toggle="modal" data-bs-target="#addModal_product" data-action="{{ route('insidewarehouses.store') }}">
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
          <a href="{{ route('export.data', ['type' => 'insidewarehouses']) }}" class="btn btn-success mb-2">Export to
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
                   <th class="text-center">หมวดวัสดุ</th>
                   <th class="text-center">จำนวนรายการ</th>
                   <th class="text-center">รายละเอียดเพิ่มเติม</th>
                   <th class="text-center">การจัดการ</th>
                </tr>
             </thead>
             <tbody>
                @foreach ($E_PLAN as $item)
                   <tr>
                      <td>
                         เลขที่: {{ $item->PLAN_ID }} <br>
                         ชื่อหมวด:<strong> {{ $item->PRODCT_CAT_NAME }}</strong><br>
                         ปีงบประมาณ: {{ $item->BUDG_YEAR }} <br>
                      </td>
                      <td>
                         <span style="color: #2a60f3">วงเงินรวม:
                            {{ number_format(round($item->Total_Price, 2), 2) }}
                            บาท</span><br>
                         จำนวนวัสดุ: {{ $item->Total_QTY }}

                      </td>

                      <td style="text-align: left;">
                         วันที่สร้าง:
                         {{ \Carbon\Carbon::parse($item->CREATE_DATE)->addYears(543)->format('d/m/Y') }}

                         <br>
                         หน่วยงานที่เบิก: {{ $item->TCHN_LOCAT_NAME }} <br>
                         ประเภทแผน: {{ $item->TYPE_NAME }}
                      </td>
                      <td class="text-center align-middle">
                         <div class="btn-group mb-2" role="group">
                            <a href="{{ route('insidewarehouses.show', $item->PLAN_ID) }}" type="button" class="btn btn-outline-primary btn-sm" style="white-space: nowrap;">ดูข้อมูล</a>
                            <form action="{{ route('insidewarehouses.destroy', $item->PLAN_ID) }}" method="POST" onsubmit="return confirm('Delete?')" class="p-0">
                               @csrf
                               @method('DELETE')
                               <button type="submit" class="btn btn-outline-danger btn-sm" style="white-space: nowrap;">ปิดใช้งาน</button>
                            </form>
                         </div>
                         <div>
                            @if (Auth::user()->id != '114000041')
                               <form id="approval-form" action="{{ route('outsidewarehouses.approved', $item->PLAN_ID) }}" method="POST">
                                  @csrf
                                  @method('PATCH')
                                  @if ($item->REQ_APPROVED_ID == 0)
                                     <span class="badge bg-primary">ตรวจสอบวัสดุก่อนขออนุมัติ</span>
                                  @elseif($item->REQ_APPROVED_ID == 1)
                                     <span class="badge bg-warning text-dark">รออนุมัติ ติดต่อหน.การเงิน</span>
                                  @endif
                               </form>
                            @endif

                            @if (Auth::user()->id == '114000041')
                               @if ($item->REQ_APPROVED_ID == 1)
                                  <form id="approval-form-{{ $item->PLAN_ID }}" action="{{ route('approved_items_stores.approved', $item->PLAN_ID) }}" method="POST">
                                     @csrf
                                     @method('PATCH')
                                     <div class="btn-group" id="button-group-{{ $item->PLAN_ID }}" role="group">
                                        <button class="btn btn-primary btn-sm" type="button" onclick="submitApproval({{ $item->PLAN_ID }}, 0)">อนุมัติเสร็จสิ้น</button>
                                     </div>
                                  </form>
                               @endif
                            @endif
                         </div>
                      </td>
                   </tr>
                @endforeach
             </tbody>
          </table>
       </div>

       <!-- Include the modal -->
       @include('insidewarehouses.modal')

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
                         if (reqApprovedId == 0) {
                            buttonGroup.innerHTML = `
                     <span class="badge bg-success">ผ่านอนุมัติ</span>
                  `;
                         }
                      }
                   } else {
                      toastr.error(data.message || 'เกิดข้อผิดพลาดในการส่งข้อมูล!');
                   }
                })
                .catch(error => {
                   console.error('Error:', error);
                   toastr.error('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์!');
                });
          }
          document.addEventListener('DOMContentLoaded', function() {
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
                      width: '40vh',
                      targets: 0
                   },
                   {
                      // กำหนดคอลัมน์แรก
                      width: '30vh',
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
                      width: '20vh',
                      orderable: false,
                      targets: 3
                   }
                ],

             });
             // Handle the filtering for the year select
             $('#filterSelectID').on('change', function() {
                const year = $(this).val();
                const location = $('#filterSelectTCHN').val();
                table.columns(0).search(year ? `ปีงบประมาณ: ${year}` : '', true, false);
                table.columns(2).search(location ? `หน่วยงานที่เบิก: ${location}` : '', true, false);
                table.draw();
             });

             // Handle the filtering for the location select
             $('#filterSelectTCHN').on('change', function() {
                const location = $(this).val();
                const year = $('#filterSelectID').val();
                table.columns(2).search(location ? `หน่วยงานที่เบิก: ${location}` : '', true, false);
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
                const location = row.cells[2].textContent.match(/หน่วยงานที่เบิก: (.+)/)[1]?.trim();
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

                   fetch('{{ route('insidewarehouses.close_plan') }}', {
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
                               button.setAttribute('data-bs-target', '#addModal_product');
                               button.setAttribute('data-action',
                                  '{{ route('insidewarehouses.store') }}');
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

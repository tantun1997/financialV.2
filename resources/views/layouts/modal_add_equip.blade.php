    <div class="modal fade" id="add_equip_Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="add_equip_ModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_equip_ModalLabel">เพิ่มรายการ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <h5 class="card-header" style="background-color: rgb(24, 138, 245); color: white;"><i
                                class="fa-regular fa-magnifying-glass"></i>
                            ค้นหารายการครุภัณฑ์</h5>
                        <div class="card-body">
                            <input class="form-control" id="customSearchInput" type="search" placeholder="ค้นหารายการ">
                            <br>
                            <table id='add_equip_table' class="table table-custom table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">เลือก</th>
                                        <th style="text-align: left;">รายการ</th>
                                        <th style="text-align: center;">เพิ่มเติม</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($VW_EQUIPMENT as $item)
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle; width: 5%;">
                                                <button class="btn btn-outline-success btn-sm"
                                                    onclick="addEquipment('{{ $item->EQUP_LINK_NO }}', '{{ $E_PLAN->PLAN_ID }}', '{{ $E_PLAN->TYPE_NUMBER }}')">
                                                    เพิ่ม
                                                </button>
                                            </td>
                                            <td style="text-align: left; width: 70%;">
                                                <div><strong>หมายเลข:</strong> {{ $item->EQUP_ID }}</div>
                                                <div><strong>ชื่อรายการ:</strong> {{ $item->EQUP_NAME }}</div>
                                                <div><strong>ราคา:</strong> {{ number_format($item->EQUP_PRICE) }}
                                                    บาท</div>
                                            </td>
                                            <td style="text-align: left;">
                                                <div><strong>แผนก:</strong> {{ $item->TCHN_LOCAT_NAME }}</div>
                                                <div><strong>อายุการใช้งาน:</strong> {{ number_format($item->age) }}
                                                    ปี</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .is-invalid {
            border-color: #dc3545;
        }

        .is-invalid::placeholder {
            color: #dc3545;
        }
    </style>
    <style>
        .hidden-row {
            display: none;
        }
    </style>

    <script>
        var myModalEl = document.getElementById('add_equip_Modal');
        myModalEl.addEventListener('hidden.bs.modal', function(event) {
            location.reload(); // Refresh the page
        });

        function addEquipment(EQUP_LINK_NO, PLAN_ID, TYPE_NUMBER) {
            let url;

            // Determine the URL based on TYPE_NUMBER
            if (TYPE_NUMBER == 1) {
                url = '/maintenances/create/' + EQUP_LINK_NO + '/' + PLAN_ID;
            } else if (TYPE_NUMBER == 2) {
                url = '/repairs/create/' + EQUP_LINK_NO + '/' + PLAN_ID;
            } else if (TYPE_NUMBER == 3) {
                url = '/contractservices/create/' + EQUP_LINK_NO + '/' + PLAN_ID;
            } else if (TYPE_NUMBER == 4) {
                url = '/calibrations/create/' + EQUP_LINK_NO + '/' + PLAN_ID;
            } else if (TYPE_NUMBER == 5) {
                url = '/potentials/create/' + EQUP_LINK_NO + '/' + PLAN_ID;
            } else if (TYPE_NUMBER == 6) {
                url = '/replacements/create/' + EQUP_LINK_NO + '/' + PLAN_ID;
            } else if (TYPE_NUMBER == 7) {
                url = '/noserials/create/' + EQUP_LINK_NO + '/' + PLAN_ID;
            } else {
                toastr.error('ประเภทไม่ถูกต้อง');
                return; // Exit the function if TYPE_NUMBER is not valid
            }

            // Make the AJAX request
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    toastr.success('เพิ่มครุภัณฑ์สำเร็จ!!');
                },
                error: function(xhr) {
                    toastr.error('เกิดข้อผิดพลาดในการเพิ่มครุภัณฑ์');
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            var qtyInput = document.getElementById('PLAN_QTY');
            var priceInput = document.getElementById('PLAN_PRICE_PER');
            var totalInput = document.querySelector('input[disabled][placeholder="ราคาทั้งหมด"]');

            function calculateTotal() {
                var qty = parseFloat(qtyInput.value) || 0;
                var price = parseFloat(priceInput.value) || 0;
                var total = qty * price;
                totalInput.value = total.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            qtyInput.addEventListener('input', calculateTotal);
            priceInput.addEventListener('input', calculateTotal);

            var table = $('#add_equip_table').DataTable({
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
                autoWidth: true,
                dom: 'dom', // ซ่อนช่องค้นหาเริ่มต้นของ DataTables
                order: [],
                pageLength: 10, // ตั้งค่าจำนวนแถวเริ่มต้นต่อหน้า
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2]
                }]
            });
            $('#customSearchInput').on('keyup', function() {
                table.search(this.value).draw();
            });
            var searchInput = document.getElementById('customSearchInput');

            // ฟังก์ชันในการกรองตาราง
            function filterTable() {
                var searchTerm = searchInput.value;
                if (searchTerm) {
                    table.search(searchTerm).draw();
                    table.rows().nodes().to$().show();
                } else {
                    table.search('').draw();
                    table.rows().nodes().to$().hide();
                }
            }

            // ฟังการเปลี่ยนแปลงของกล่องค้นหา
            searchInput.addEventListener('input', filterTable);

            // ซ่อนแถวทั้งหมดในตารางตอนโหลดหน้า
            table.rows().nodes().to$().hide();

        });
    </script>

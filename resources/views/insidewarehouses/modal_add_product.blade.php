    <div class="modal fade" id="add_product_Modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="add_product_ModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_product_ModalLabel">เพิ่มรายการ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <h5 class="card-header" style="background-color: rgb(24, 138, 245); color: white;"><i
                                class="fa-regular fa-magnifying-glass"></i>
                            ค้นหารายการวัสดุ</h5>
                        <div class="card-body">
                            <input class="form-control" id="customSearchInput" type="search" placeholder="ค้นหารายการ">
                            <br>
                            <table id='add_equip_table' class="table table-custom table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">เลือก</th>
                                        <th style="text-align: left;">รายการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($VW_PRODUCT as $item)
                                        <tr>
                                            <td style="text-align: center; vertical-align: middle; width: 5%;">
                                                <button class="btn btn-outline-success btn-sm"
                                                    onclick="addEquipment('{{ $item->PRODCT_LINK_NO }}', '{{ $E_PLAN->PLAN_ID }}')">
                                                    เพิ่ม
                                                </button>
                                            </td>
                                            <td style="text-align: left; width: 70%;">
                                                <div><strong>หมวดสินค้า:</strong> {{ $item->PRODCT_CAT_NAME }}</div>
                                                <div><strong>รหัส:</strong> {{ $item->PRODCT_ID }}</div>
                                                <div><strong>ชื่อรายการ:</strong> {{ $item->PRODCT_NAME }}</div>
                                                <div><strong>ราคา:</strong>
                                                    {{ number_format($item->PRODCT_MIN_COSTRATE) }}
                                                    บาท</div>
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
        var myModalEl = document.getElementById('add_product_Modal');
        myModalEl.addEventListener('hidden.bs.modal', function(event) {
            location.reload(); // Refresh the page
        });

        function addEquipment(PRODCT_LINK_NO, PLAN_ID) {

            let url = '/outsidewarehouses/create/' + PRODCT_LINK_NO + '/' + PLAN_ID;

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
                    targets: [0, 1]
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

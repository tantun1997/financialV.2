    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="addModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">เพิ่มรายการ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('noserials.store') }}">
                        @csrf
                        @method('POST')
                        <div class="row row-cols-1 row-cols-lg-4">
                            <div class="col mb-3">
                                <span>ปีงบประมาณ</span>
                                <select class="form-select" id="PLAN_SET_YEAR" name="PLAN_SET_YEAR" required>
                                    <option value="" selected hidden>ปีงบประมาณ</option>
                                    @php
                                        $currentYear = date('Y');
                                        $nextYear2 = $currentYear + 2;
                                        $nextYear = $currentYear + 1;
                                        $displayedNextYear = $nextYear + 543;
                                        $displayedNextYear2 = $nextYear2 + 543;
                                    @endphp
                                    <option value="{{ $nextYear2 + 543 }}">{{ $displayedNextYear2 }}</option>
                                    <option value="{{ $nextYear + 543 }}">{{ $displayedNextYear }}</option>
                                    <option value="{{ $currentYear + 543 }}">{{ $currentYear + 543 }}</option>
                                </select>
                            </div>
                            <div class="col mb-3">
                                <span>แผนฯ</span>
                                <select class="form-select" id="USAGE_STATUS_ID" name="USAGE_STATUS_ID" required>
                                    <option value="" selected hidden>แผนฯ</option>
                                    @foreach ($E_USAGE_STATUS as $USAGE_STATUS)
                                        <option value="{{ $USAGE_STATUS->USAGE_STATUS_ID }}">
                                            {{ $USAGE_STATUS->USAGE_STATUS_NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <span>ประเภทงบ</span>
                                <select class="form-select" id="Budget_ID" name="Budget_ID" required>
                                    <option value="" selected hidden>ประเภทงบ</option>
                                    @foreach ($E_BUDGET as $BUDGET)
                                        <option value="{{ $BUDGET->Budget_ID }}">{{ $BUDGET->Budget_NAME }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <span>ประเภทแผน</span>
                                <select class="form-select" id="TYPE_ID" name="TYPE_ID" required>
                                    <option value="" selected hidden>ประเภทแผน</option>
                                    @foreach ($E_TYPE as $type)
                                        <option value="{{ $type->TYPE_ID }}" selected>{{ $type->TYPE_NAME }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col mb-3">
                                <span>ชื่อแผนงาน</span>
                                <textarea class="form-control" id="PLAN_NAME" name="PLAN_NAME" placeholder="ชื่อแผนงาน" rows="3" required></textarea>

                            </div>
                            <div class="col mb-3">
                                <span>ราคาต่อหน่วย </span>
                                <input class="form-control" type="number" id="PLAN_PRICE_PER" name="PLAN_PRICE_PER"
                                    placeholder="ราคาต่อหน่วย" min="1" required>
                            </div>
                            <div class="col mb-3">
                                <span>จำนวนครุภัณฑ์</span>
                                <input class="form-control" type="number" id="PLAN_QTY" name="PLAN_QTY"
                                    placeholder="จำนวนครุภัณฑ์" min="1" required>
                            </div>
                            <div class="col mb-3">
                                <span>ราคาทั้งหมด</span>
                                <input class="form-control" type="text" placeholder="ราคาทั้งหมด" disabled>
                            </div>
                            <div class="col mb-3">
                                <span>เหตุผลและความจำเป็น</span>
                                <textarea class="form-control" id="PLAN_REASON" name="PLAN_REASON" placeholder="เหตุผลและความจำเป็น" rows="3"
                                    required></textarea>
                            </div>
                            <div class="col mb-3">
                                <span>หมายเหตุ</span>
                                <textarea class="form-control" id="PLAN_REMARK" name="PLAN_REMARK" placeholder="หมายเหตุ (ถ้ามี)" rows="3"></textarea>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <script>
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
        });
    </script>

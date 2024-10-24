    <div class="modal fade" id="addModal_product" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="addModal_productLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModal_productLabel">เพิ่มรายการ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('insidewarehouses.store') }}">
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
                                <span>หมวดวัสดุ</span>
                                <input class="form-control" list="categories" id="PLAN_NAME" name="PLAN_NAME"
                                    placeholder="หมวดวัสดุ" required>
                                <datalist id="categories">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->PRODCT_CAT_NAME }}">
                                    @endforeach
                                </datalist>
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

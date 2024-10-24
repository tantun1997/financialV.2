    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="editModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form method="POST" action="{{ route('outsidewarehouses.update', $E_PLAN->PLAN_ID) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">แก้ไขรายการ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row row-cols-1 row-cols-lg-4">
                            <div class="col mb-3">
                                <span>ปีงบประมาณ</span>
                                <select class="form-select" id="BUDG_YEAR" name="BUDG_YEAR" required>
                                    <option value="" selected hidden>ปีงบประมาณ</option>
                                    @php
                                        $currentYear = date('Y');
                                        $nextYear2 = $currentYear + 2;
                                        $nextYear = $currentYear + 1;
                                        $displayedNextYear = $nextYear + 543;
                                        $displayedNextYear2 = $nextYear2 + 543;
                                    @endphp
                                    <option value="{{ $nextYear2 + 543 }}"
                                        {{ $E_PLAN->BUDG_YEAR == $nextYear2 + 543 ? 'selected' : '' }}>
                                        {{ $displayedNextYear2 }}</option>
                                    <option value="{{ $nextYear + 543 }}"
                                        {{ $E_PLAN->BUDG_YEAR == $nextYear + 543 ? 'selected' : '' }}>
                                        {{ $displayedNextYear }}</option>
                                    <option value="{{ $currentYear + 543 }}"
                                        {{ $E_PLAN->BUDG_YEAR == $currentYear + 543 ? 'selected' : '' }}>
                                        {{ $currentYear + 543 }}</option>
                                </select>
                            </div>
                            <div class="col mb-3">
                                <span>ประเภทงบ</span>
                                <select class="form-select" id="Budget_ID" name="Budget_ID" required>
                                    <option value="" selected hidden>ประเภทงบ</option>
                                    @foreach ($E_BUDGET as $BUDGET)
                                        <option value="{{ $BUDGET->Budget_ID }}"
                                            {{ $E_PLAN->Budget_ID == $BUDGET->Budget_ID ? 'selected' : '' }}>
                                            {{ $BUDGET->Budget_NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <span>ประเภทแผน</span>
                                <select class="form-select" id="TYPE_ID" name="TYPE_ID" required>
                                    <option value="" selected hidden>ประเภทแผน</option>
                                    @foreach ($E_TYPE as $type)
                                        <option value="{{ $type->TYPE_ID }}"
                                            {{ $E_PLAN->TYPE_ID == $type->TYPE_ID ? 'selected' : '' }}>
                                            {{ $type->TYPE_NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <span>หมวดวัสดุ</span>
                                <input class="form-control" list="categories" id="PRODCT_CAT_ID" name="PRODCT_CAT_ID"
                                    placeholder="หมวดวัสดุ" value="{{ $E_PLAN->PRODCT_CAT_ID }}" required>
                                <datalist id="categories">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->PRODCT_CAT_ID }}"
                                            data-name="{{ $category->PRODCT_CAT_NAME }}"
                                            {{ $E_PLAN->PRODCT_CAT_ID == $category->PRODCT_CAT_ID ? 'selected' : '' }}>
                                            {{ $category->PRODCT_CAT_NAME }}
                                        </option>
                                    @endforeach
                                </datalist>
                                <input type="hidden" id="PRODCT_CAT_NAME" name="PRODCT_CAT_NAME"
                                    value="{{ $E_PLAN->PRODCT_CAT_NAME }}">
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
        document.getElementById('PRODCT_CAT_ID').addEventListener('input', function() {
            var options = document.querySelectorAll('#categories option');
            var input = this.value;
            var nameField = document.getElementById('PRODCT_CAT_NAME');

            options.forEach(function(option) {
                if (option.value === input) {
                    nameField.value = option.getAttribute('data-name');
                }
            });
        });

        // Set initial value for PRODCT_CAT_NAME based on the selected PRODCT_CAT_ID
        window.onload = function() {
            var selectedOption = document.querySelector('#categories option[value="{{ $E_PLAN->PRODCT_CAT_ID }}"]');
            if (selectedOption) {
                document.getElementById('PRODCT_CAT_NAME').value = selectedOption.getAttribute('data-name');
            }
        };
    </script>

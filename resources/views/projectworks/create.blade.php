@extends('layouts.app')

@section('title',
    'เพิ่มแผนงานโครงการ
    ')

@section('contents')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a class="btn btn-danger" href="{{ route('projectworks') }}" role="button">ย้อนกลับ</a>
    </div>
    <hr />
    <form action="{{ route('projectworks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row row-cols-1 row-cols-lg-4">
            <div class="col mb-3">
                <span>ปีงบประมาณ</span>
                <select class="form-control form-control-sm" id="Plan_YEAR" required>
                    <option value="" selected disabled>ปีงบประมาณ</option>
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
                <select class="form-control form-control-sm" id="Plan_LEVEL" required>
                    <option value="" selected disabled>แผนฯ</option>
                    <option value="1">จริง</option>
                    <option value="2">สำรอง</option>
                </select>
            </div>
            <div class="col mb-3">
                <span>ประเภทงบ</span>
                <select class="form-control form-control-sm" id="Plan_BUDGET" required>
                    <option value="" selected disabled>ประเภทงบ</option>

                </select>
            </div>
            <div class="col mb-3">
                <span>ประเภทแผน</span>
                <select class="form-control form-control-sm" id="Plan_TYPE_ID" required>
                    <option value="" selected disabled>ประเภทแผน</option>

                </select>
            </div>

            <div class="col mb-3">
                <span>ชื่อแผนงาน</span>
                <textarea class="form-control form-control-sm" id="Plan_NAME" placeholder="ชื่อแผนงาน" required rows="3"></textarea>

            </div>
            <div class="col mb-3">
                <span>ราคาต่อหน่วย </span>
                <input class="form-control form-control-sm" type="number" id="Plan_PRICE_OVERALL"
                    placeholder="ราคาต่อหน่วย" required>
            </div>
            <div class="col mb-3">
                <span>จำนวนครุภัณฑ์</span>
                <input class="form-control form-control-sm" type="number" id="Plan_AMOUNT" placeholder="จำนวนครุภัณฑ์"
                    required>
            </div>
            <div class="col mb-3">
                <span>ราคารวม</span>
                <input class="form-control form-control-sm" type="text" id="Plan_AMOUNT" placeholder="ราคารวม" disabled>
            </div>
            <div class="col mb-3">
                <span>เหตุผลและความจำเป็น</span>
                <textarea class="form-control form-control-sm" id="Plan_REASON" placeholder="เหตุผลและความจำเป็น" required
                    rows="3"></textarea>
            </div>
            <div class="col mb-3">
                <span>หมายเหตุ</span>
                <textarea class="form-control form-control-sm" id="Plan_REMARK" placeholder="หมายเหตุ (ถ้ามี)" rows="3"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="d-grid ">
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </div>
    </form>
@endsection

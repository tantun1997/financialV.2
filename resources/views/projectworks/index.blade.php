@extends('layouts.app')

@section('title', 'แผนงานโครงการ')

@section('contents')
    <div class="d-flex align-items-center justify-content-between">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"
            data-action="{{ route('projectworks.store') }}">
            + เพิ่มรายการ
        </button>
    </div>
    <hr />

    <table id='dataTable' class="table table-bordered table-hover table-sm" style="width: 100%;">
        <thead class="table-primary">
            <tr>
                <th class="text-center">ชื่อแผนงาน</th>
                <th class="text-center">ประเมินราคา</th>
                <th class="text-center">เหตุผลและความจำเป็น</th>
                <th class="text-center">เพิ่มเติม</th>
                <th class="text-center">จัดการ</th>
            </tr>
        </thead>
        {{-- <tbody>
            @if ($product->count() > 0)
                @foreach ($product as $rs)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $rs->title }}</td>
                        <td class="align-middle">{{ $rs->price }}</td>
                        <td class="align-middle">{{ $rs->product_code }}</td>
                        <td class="align-middle">{{ $rs->description }}</td>
                        <td class="align-middle">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('products.show', $rs->id) }}" type="button"
                                    class="btn btn-secondary">Detail</a>
                                <a href="{{ route('products.edit', $rs->id) }}" type="button"
                                    class="btn btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $rs->id) }}" method="POST" type="button"
                                    class="btn btn-danger p-0" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger m-0">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="5">Product not found</td>
                </tr>
            @endif
        </tbody> --}}
    </table>
    <!-- Include the modal -->
    @include('layouts.modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var addModal = document.getElementById('addModal');
            var dynamicForm = document.getElementById('dynamicForm');

            addModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // ปุ่มที่คลิกเปิด Modal
                var action = button.getAttribute('data-action'); // ดึง action จาก data-attribute

                // ตั้งค่า action ของฟอร์มใหม่
                dynamicForm.setAttribute('action', action);
            });
        });
    </script>
@endsection

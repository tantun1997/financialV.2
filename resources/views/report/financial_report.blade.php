@extends('layouts.app')

@section('title', 'รายงานข้อมูลงบประมาณ')

@section('contents')
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <a href="{{ route('report', ['name_report' => 'administration_report']) }}" class="card-link">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size: 22px; color: #007bff;">ด้านอำนวยการ</span>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>แผนจริง</th>
                                    <th>แผนสำรอง</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>วงเงิน(บาท)</td>
                                    <td> {{ number_format(round($Administration_true), 0) }}</td>
                                    <td> {{ number_format(round($Administration_spare), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>ใช้แล้ว(บาท)</td>
                                    <td> {{ number_format(round($Administration_true_used), 0) }}</td>
                                    <td> {{ number_format(round($Administration_spare_used), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>คงเหลือ(บาท)</td>
                                    <td> {{ number_format(round($Administration_true_remaining), 0) }}</td>
                                    <td> {{ number_format(round($Administration_spare_remaining), 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: center">วงเงินรวม
                                        {{ number_format(round($Administration_total), 0) }}
                                        บาท</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('report', ['name_report' => 'nursing_report']) }}" class="card-link">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size: 22px; color: #007bff;">ด้านการพยาบาล</span>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>แผนจริง</th>
                                    <th>แผนสำรอง</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>วงเงิน(บาท)</td>
                                    <td> {{ number_format(round($Nursing_true), 0) }}</td>
                                    <td> {{ number_format(round($Nursing_spare), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>ใช้แล้ว(บาท)</td>
                                    <td> {{ number_format(round($Nursing_true_used), 0) }}</td>
                                    <td> {{ number_format(round($Nursing_spare_used), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>คงเหลือ(บาท)</td>
                                    <td> {{ number_format(round($Nursing_true_remaining), 0) }}</td>
                                    <td> {{ number_format(round($Nursing_spare_remaining), 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: center">วงเงินรวม
                                        {{ number_format(round($Nursing_total), 0) }}
                                        บาท</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('report', ['name_report' => 'secondary_report']) }}" class="card-link">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size: 22px; color: #007bff;">ด้านบริการทุติยภูมิและตติยภูมิ</span>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>แผนจริง</th>
                                    <th>แผนสำรอง</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>วงเงิน(บาท)</td>
                                    <td> {{ number_format(round($Secondary_true), 0) }}</td>
                                    <td> {{ number_format(round($Secondary_spare), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>ใช้แล้ว(บาท)</td>
                                    <td> {{ number_format(round($Secondary_true_used), 0) }}</td>
                                    <td> {{ number_format(round($Secondary_spare_used), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>คงเหลือ(บาท)</td>
                                    <td> {{ number_format(round($Secondary_true_remaining), 0) }}</td>
                                    <td> {{ number_format(round($Secondary_spare_remaining), 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: center">วงเงินรวม
                                        {{ number_format(round($Secondary_total), 0) }}
                                        บาท</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </a>

        </div>
        <div class="col">
            <a href="{{ route('report', ['name_report' => 'primary_report']) }}" class="card-link">
                <div class="card">
                    <div class="card-body">
                        <span style="font-size: 22px; color: #007bff;">ด้านบริการปฐมภูมิ</span>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>แผนจริง</th>
                                    <th>แผนสำรอง</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>วงเงิน(บาท)</td>
                                    <td> {{ number_format(round($Primary_true), 0) }}</td>
                                    <td> {{ number_format(round($Primary_spare), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>ใช้แล้ว(บาท)</td>
                                    <td> {{ number_format(round($Primary_true_used), 0) }}</td>
                                    <td> {{ number_format(round($Primary_spare_used), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>คงเหลือ(บาท)</td>
                                    <td> {{ number_format(round($Primary_true_remaining), 0) }}</td>
                                    <td> {{ number_format(round($Primary_spare_remaining), 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: center">วงเงินรวม
                                        {{ number_format(round($Primary_total), 0) }}
                                        บาท</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </a>

        </div>
        <div class="col">
            <a href="{{ route('report', ['name_report' => 'supporting_report']) }}" class="card-link">
                <div class="card">
                    <div class="card-body">
                        <h4 style="font-size: 22px; color: #007bff;">ด้านพัฒนาระบบบริการและสนับสนุนบริการสุขภาพ</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>แผนจริง</th>
                                    <th>แผนสำรอง</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>วงเงิน(บาท)</td>
                                    <td> {{ number_format(round($Supporting_true), 0) }}</td>
                                    <td> {{ number_format(round($Supporting_spare), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>ใช้แล้ว(บาท)</td>
                                    <td> {{ number_format(round($Supporting_true_used), 0) }}</td>
                                    <td> {{ number_format(round($Supporting_spare_used), 0) }}</td>
                                </tr>
                                <tr>
                                    <td>คงเหลือ(บาท)</td>
                                    <td> {{ number_format(round($Supporting_true_remaining), 0) }}</td>
                                    <td> {{ number_format(round($Supporting_spare_remaining), 0) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: center">วงเงินรวม
                                        {{ number_format(round($Supporting_total), 0) }}
                                        บาท</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <style>
        .card-link {
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s ease;
            display: block;
        }


        .card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            background-color: rgb(159, 221, 250);
            color: #000000
        }
    </style>

@endsection

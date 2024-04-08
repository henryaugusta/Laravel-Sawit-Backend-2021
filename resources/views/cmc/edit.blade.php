@extends('template')

@section('page-heading')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Permintaan</h3>
                    <p class="text-subtitle text-muted">Edit Permintaan</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('') }}">Permintaan</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-content')
    <section class="section">
        @include('components.message')
    </section>

    <section class="section">

        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Disiapkan Oleh</h4>
                    </div>
                    <div class="card-body py-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="https://placeimg.com/200/300/animals" alt="Panda Image"
                                     onerror="this.onerror=null; this.src='https://avatarsb.s3.amazonaws.com/others/panda-black-toy1-31-min.png';">
                            </div>
                            <div class="ms-3 name">
                                @php
                                    $userDisiapkanOleh = \App\Models\User::findOrFail($data->disiapkan_oleh);
                                @endphp
                                <h5 class="font-bold">{{$userDisiapkanOleh->name}}</h5>
                                <h6 class="text-muted mb-0">{{$userDisiapkanOleh->email}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Ekspedisi</h4>
                    </div>
                    <div class="card-body py-4 px-4">
                        <div class="d-flex align-items-center">
                            <div class="ms-3 name">
                                @php
                                    $armada = \App\Models\Truck::findOrFail($data->id_armada);
                                @endphp
                                <div class="border p-3">
                                    <img height="300px" style="border-radius: 20px" src="/razky_samples/truck/colt_diesel.png" alt="" srcset="">
                                    <ul>
                                        <li class="mt-3">Nama : {{$armada->name}}</li>
                                        <li>Nopol : {{$armada->nopol}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Permintaan</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('new_po_request.update', $data->id) }}" enctype="multipart/form-data"
                      method="post">
                    @csrf
                    <div class="row">

                        <div class="col-12">
                            <h3 class="card-title text-black">
                                Status Terakhir : Dibuat Oleh Customer.
                            </h3>
                        </div>


                        <div class="col-12 @if(Auth::user()->role!=2) d-none @endif mb-4">
                            <div style="border: 1px solid lightgrey; border-radius: 20px; padding: 10px;">
                                <h3 class="card-title">
                                    Masukkan Nomor PO (Commercial)
                                </h3>


                                <div class="form-group">
                                    <label for="basicInput">Nomor PO</label>
                                    <input type="text" name="po_number" class="form-control"
                                           value="{{ old('po_number', $data->po_number) }}"
                                           id="basicInput" placeholder="Nomor PO dari Sistem ERP">
                                    <p class="form-text text-muted">
                                        Nomor PO diambil dari sistem Enterprise Resource Planning (ERP). ERP adalah
                                        sistem manajemen yang digunakan oleh perusahaan untuk mengelola dan
                                        mengotomatisasi berbagai proses bisnis, termasuk manajemen inventaris,
                                        pembelian, dan penjualan. Pastikan untuk memasukkan nomor PO yang benar sesuai
                                        dengan yang ada di sistem ERP
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label for="basicInput">Komentar</label>
                                    <textarea name="comment_commercial" class="form-control" id="basicInput"
                                              placeholder="Komentar">{{$data->comment_commercial}}</textarea>
                                    <p class="form-text text-muted">
                                        Komentar dari Tim Commercial
                                    </p>
                                </div>

                                <div class="col-12">
                                    <button type="button" class="btn btn-primary block" data-bs-toggle="modal"
                                            data-bs-target="#myConfirmationModal">
                                        Simpan Nomor PO
                                    </button>
                                </div>

                                <!-- Vertically Centered modal Modal -->
                                <div class="modal fade" id="myConfirmationModal" tabindex="-1" role="dialog"
                                     aria-labelledby="myConfirmationModalTitle" aria-hidden="true">
                                    <div
                                        class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                                        role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myConfirmationModalTitle">
                                                    Anda Yakin Ingin Menambahkan Nomor PO ?
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    Setelah Nomor Purchase Order (PO) Terinput, permintaan ini akan
                                                    diteruskan kepada departemen gudang untuk pengolahan selanjutnya
                                                    oleh tim gudang. <strong>Proses ini memastikan</strong> bahwa barang
                                                    yang diminta <strong>dapat disiapkan dengan cepat dan
                                                        akurat</strong> untuk pengiriman kepada pelanggan. Tim gudang
                                                    akan melakukan langkah-langkah berikut:
                                                </p>
                                                <ul>
                                                    <li>
                                                        <strong>Verifikasi PO:</strong> Tim gudang akan memeriksa detail
                                                        PO untuk memastikan kesesuaian dengan inventaris yang tersedia.
                                                    </li>
                                                    <li>
                                                        <strong>Persiapan Barang:</strong> Setelah verifikasi, barang
                                                        akan dipersiapkan dengan cermat untuk pengiriman. Ini mencakup
                                                        proses pengepakan yang aman dan sesuai standar.
                                                    </li>
                                                    <li>
                                                        <strong>Pengiriman kepada Pelanggan:</strong> Barang yang sudah
                                                        siap akan dikirimkan kepada pelanggan sesuai dengan informasi
                                                        pengiriman yang tercantum dalam PO.
                                                    </li>
                                                </ul>
                                                <p>
                                                    Proses ini <strong>dilakukan dengan teliti dan efisien</strong>
                                                    untuk memastikan kepuasan pelanggan tercapai. Kami berkomitmen untuk
                                                    <strong>menyediakan layanan yang berkualitas</strong> dan <strong>memenuhi
                                                        kebutuhan pelanggan</strong> dengan baik.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary"
                                                        data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="submit" onclick="submitForm()" class="btn-primary btn">
                                                    Accept
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @push('script')
                                    <script>
                                        // Function to show confirmation modal
                                        function showConfirmationModal() {
                                            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                                            myModal.show();
                                        }

                                        function dismissConfirmationModal() {
                                            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                                            $('#confirmationModal').modal('hide');
                                        }

                                        // Function to submit the form after confirmation
                                        function submitForm() {
                                            document.getElementById("myForm").submit();
                                        }
                                    </script>
                                @endpush

                            </div>


                        </div>

                        <div class="col-12 @if(Auth::user()->role!=4) d-none @endif mb-4">
                            <div style="border: 1px solid lightgrey; border-radius: 20px; padding: 10px;">
                                <h3 class="card-title">
                                    Masukkan Informasi Armada & Driver
                                </h3>


                                <fieldset class="form-group">
                                    <label for="basicInput">Ekspedisi</label>
                                    <select name="id_armada" class="form-select">
                                        @foreach($ekspedisiList as $ekspedisi)
                                            <option
                                                value="{{ $ekspedisi->id }}" {{ old('id_armada', $data->id_armada) == $ekspedisi->id ? 'selected' : '' }}>
                                                {{ $ekspedisi->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>

                                <fieldset class="form-group">
                                    <label for="basicInput">Pilih Driver</label>
                                    <select name="id_driver" class="form-select">
                                        @foreach($users as $item)
                                            <option
                                                value="{{ $item->id }}" {{ old('id_driver', $item->id_driver) == $item->id_driver ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </fieldset>


                                <div class="form-group">
                                    <label for="basicInput">Komentar Warehouse</label>
                                    <textarea name="comment_commercial" class="form-control" id="basicInput"
                                              placeholder="Komentar Tim Warehouse">{{$data->comment_warehouse}}</textarea>
                                    <p class="form-text text-muted">
                                        Komentar dari Tim Warehouse
                                    </p>
                                </div>

                                <div class="col-12">
                                    <button type="button" class="btn btn-primary block" data-bs-toggle="modal"
                                            data-bs-target="#myEkspedisiConfirmationModal">
                                        Simpan Informasi Ekspedisi
                                    </button>
                                </div>

                                <!-- Vertically Centered modal Modal -->
                                <div class="modal fade" id="myEkspedisiConfirmationModal" tabindex="-1" role="dialog"
                                     aria-labelledby="myEkspedisiConfirmationModalTitle" aria-hidden="true">
                                    <div
                                        class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
                                        role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myEkspedisiConfirmationModalTitle">
                                                    Anda Yakin Ingin Menyimpan Informasi Ekspedisi Ini ?
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    Setelah Nomor Purchase Order (PO) Terinput, permintaan ini akan
                                                    diteruskan kepada departemen gudang untuk pengolahan selanjutnya
                                                    oleh tim gudang. <strong>Proses ini memastikan</strong> bahwa barang
                                                    yang diminta <strong>dapat disiapkan dengan cepat dan
                                                        akurat</strong> untuk pengiriman kepada pelanggan. Tim gudang
                                                    akan melakukan langkah-langkah berikut:
                                                </p>
                                                <ul>
                                                    <li>
                                                        <strong>Verifikasi PO:</strong> Tim gudang akan memeriksa detail
                                                        PO untuk memastikan kesesuaian dengan inventaris yang tersedia.
                                                    </li>
                                                    <li>
                                                        <strong>Persiapan Barang:</strong> Setelah verifikasi, barang
                                                        akan dipersiapkan dengan cermat untuk pengiriman. Ini mencakup
                                                        proses pengepakan yang aman dan sesuai standar.
                                                    </li>
                                                    <li>
                                                        <strong>Pengiriman kepada Pelanggan:</strong> Barang yang sudah
                                                        siap akan dikirimkan kepada pelanggan sesuai dengan informasi
                                                        pengiriman yang tercantum dalam PO.
                                                    </li>
                                                </ul>
                                                <p>
                                                    Proses ini <strong>dilakukan dengan teliti dan efisien</strong>
                                                    untuk memastikan kepuasan pelanggan tercapai. Kami berkomitmen untuk
                                                    <strong>menyediakan layanan yang berkualitas</strong> dan <strong>memenuhi
                                                        kebutuhan pelanggan</strong> dengan baik.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light-secondary"
                                                        data-bs-dismiss="modal">
                                                    <i class="bx bx-x d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Close</span>
                                                </button>
                                                <button type="submit" onclick="submitForm()" class="btn-primary btn">
                                                    Accept
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @push('script')
                                    <script>
                                        // Function to show confirmation modal
                                        function showConfirmationModal() {
                                            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                                            myModal.show();
                                        }

                                        function dismissConfirmationModal() {
                                            var myModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                                            $('#confirmationModal').modal('hide');
                                        }

                                        // Function to submit the form after confirmation
                                        function submitForm() {
                                            document.getElementById("myForm").submit();
                                        }
                                    </script>
                                @endpush

                            </div>


                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="basicInput">Nomor Surat Jalan</label>
                                <input type="text" name="nomor_surat_jalan" class="form-control"
                                       value="{{ old('nomor_surat_jalan', $data->nomor_surat_jalan) }}" id="basicInput"
                                       placeholder="Nomor Surat Jalan">
                            </div>
                            <div class="form-group">
                                <label for="basicInput">Tanggal Nomor Surat Jalan</label>
                                <input type="datetime-local" name="nomor_surat_jalan_date" class="form-control"
                                       value="{{ old('nomor_surat_jalan_date', $data->nomor_surat_jalan_date) }}"
                                       id="basicInput" placeholder="Nomor Surat Jalan Date">
                            </div>
                            <div class="form-group d-none">
                                <label for="basicInput">Order Reference</label>
                                <input type="text" name="order_reference" class="form-control"
                                       value="{{ old('order_reference', $data->order_reference) }}" id="basicInput"
                                       placeholder="Order Reference">
                            </div>
                            <div class="form-group d-none">
                                <label for="basicInput">Order Penjualan No</label>
                                <input type="text" name="order_penjualan_nomor" class="form-control"
                                       value="{{ old('order_penjualan_nomor', $data->order_penjualan_nomor) }}"
                                       id="basicInput" placeholder="Order Penjualan No">
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="form-group d-none">
                                <label for="basicInput">Alamat Pengambilan</label>
                                <textarea name="alamat_pengambilan" class="form-control"
                                          placeholder="Alamat Pengambilan">{{ old('alamat_pengambilan', $data->alamat_pengambilan) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="basicInput">Deadline</label>
                                <input type="datetime-local" name="deadline" class="form-control"
                                       value="{{ old('deadline', $data->deadline) }}"
                                       id="basicInput" placeholder="Deadline">
                            </div>
                            <div class="form-group d-none">
                                <label for="basicInput">Dijual Kepada</label>
                                <textarea name="dijual_kepada" class="form-control"
                                          placeholder="Dijual Kepada">{{ old('dijual_kepada', $data->dijual_kepada) }}</textarea>
                            </div>
                            <div class="form-group d-none">
                                <label for="basicInput">Dikirim Ke</label>
                                <textarea name="dikirim_ke" class="form-control"
                                          placeholder="Dikirim Ke">{{ old('dikirim_ke', $data->dikirim_ke) }}</textarea>
                            </div>
                            <div class="form-group d-none">
                                <label for="basicInput">Komentar Cust</label>
                                <textarea name="comment_customer" class="form-control"
                                          placeholder="Komentar">{{ old('comment_customer', $data->comment_customer) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <table class="table" id="barangTable">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Uraian</th>
                                        <th>Jumlah</th>
                                        <th>Unit</th>
                                        <th>Berat Kotor (KG)</th>
                                        <th>Volume (Liter)</th>
                                        @if(Auth::user()->role==2)
                                            <th>Action</th>
                                        @endif

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($data->products)
                                        @foreach(json_decode($data->products, true) as $index => $product)
                                            <tr>
                                                <td></td>
                                                <td>{{ $product['kode_produk'] }}</td>
                                                <td>{{ $product['uraian'] }}</td>
                                                <td>{{ $product['jumlah'] }}</td>
                                                <td>{{ $product['unit'] }}</td>
                                                <td>{{ $product['berat_kotor'] }}</td>
                                                <td>{{ $product['volume'] }}</td>
                                                @if(Auth::user()->role==2)
                                                    <td>
                                                        <button type="button" class="btn btn-danger remove-btn"
                                                                onclick="removeRow(this)">Remove
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8">No products available.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                @if(Auth::user()->role==2)
                                    <button type="button" class="btn btn-outline-primary" id="addRow">Tambah Barang
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($data->attachment!="")
                            <div class="col-md-12 mt-2 mb-5">
                                <h3 class="card-title text-black">
                                    Attachment
                                </h3>
                                <div class="pdf-container">
                                    <!-- Using the embed tag -->
                                    <embed src="{{asset("$data->attachment")}}" width="800px" height="600px"/>

                                    <!-- Or using the iframe tag -->
                                    <!-- <iframe src="path_to_your_pdf_file.pdf" width="800px" height="600px"></iframe> -->
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="">Riwayat Proses Transaksi</h3>
            </div>

            <div class="card-body">
                <ul class="timeline">
                    @foreach($timelineEntries as $entry)
                        <li>
                            <div class="timeline-badge bg-primary"></div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h6 class="timeline-title">{{ $entry->action }}</h6>
                                    <h6><small
                                            class="text-muted">{{ $entry->created_at->format('M d, Y H:i A') }}</small>
                                    </h6>
                                </div>
                                <div class="timeline-body">
                                    @php

                                        $endUserObj = \App\Models\User::find($entry->last_man)
                                    @endphp

                                    @if($endUserObj!=null)
                                        <h6><small
                                                class="text-muted">{{$endUserObj->name}}
                                                - {{$entry->action}}</small>
                                        </h6>
                                    @endif

                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </section>
@endsection


@push('script')
    <script>
        // JavaScript for dynamic table
        var counter = 1;

        document.addEventListener('DOMContentLoaded', function () {
            var tableBody = document.querySelector('tbody');

            document.getElementById('addRow').addEventListener('click', function () {
                var newRow = document.createElement('tr');
                var cols = '';

                cols += '<td>' + '</td>';
                cols += '<td><input type="text" name="kode_produk[]" class="form-control"></td>';
                cols += '<td><input type="text" name="uraian[]" class="form-control"></td>';
                cols += '<td><input type="text" name="jumlah[]" class="form-control"></td>';
                cols += '<td><input type="text" name="unit[]" class="form-control"></td>';
                cols += '<td><input type="text" name="berat_kotor[]" class="form-control"></td>';
                cols += '<td><input type="text" name="volume[]" class="form-control"></td>';
                cols += '<td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>';

                newRow.innerHTML = cols;
                tableBody.appendChild(newRow);
                counter++;
            });
        });

        function removeRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            counter--;
        }
    </script>
@endpush

@push('script')
    <script>
        var el = document.getElementById('formFile');
        el.onchange = function () {
            var fileReader = new FileReader();
            fileReader.readAsDataURL(document.getElementById("formFile").files[0])
            fileReader.onload = function (oFREvent) {
                document.getElementById("imgPreview").src = oFREvent.target.result;
            };
        }


        $(document).ready(function () {
            $.myfunction = function () {
                $("#previewName").text($("#inputTitle").val());
                var title = $.trim($("#inputTitle").val())
                if (title == "") {
                    $("#previewName").text("Judul")
                }
            };

            $("#inputTitle").keyup(function () {
                $.myfunction();
            });

        });
    </script>
@endpush

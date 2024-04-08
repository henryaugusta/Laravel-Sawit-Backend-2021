@extends('template')

@section('page-heading')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Permintaan</h3>
                    <p class="text-subtitle text-muted">Kirim Permintaan Baru</p>
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
            <div class="card-header">
                <h4 class="card-title">Add New Request</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('new_po_request.store') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="basicInput">Nomor Surat Jalan</label>
                                <input type="text" name="nomor_surat_jalan" class="form-control"
                                       value="{{ old('nomor_surat_jalan') }}" id="basicInput"
                                       placeholder="Nomor Surat Jalan">
                            </div>

                            <div class="form-group">
                                <label for="basicInput">Tanggal Nomor Surat Jalan</label>
                                <input type="datetime-local" name="nomor_surat_jalan_date" class="form-control"
                                       value="{{ old('nomor_surat_jalan_date') }}" id="basicInput"
                                       placeholder="Nomor Surat Jalan Date">
                                <!-- You might want to use a date input type if it's a date -->
                            </div>

                            <div class="form-group d-none">
                                <label for="basicInput">Order Reference</label>
                                <input type="text" name="order_reference" class="form-control"
                                       value="{{ old('order_reference') }}" id="basicInput"
                                       placeholder="Order Reference">
                            </div>

                            <div class="form-group d-none">
                                <label for="basicInput">Order Penjualan No</label>
                                <input type="text" name="order_penjualan_nomor" class="form-control"
                                       value="{{ old('order_penjualan_nomor') }}" id="basicInput"
                                       placeholder="Order Penjualan No">
                            </div>

                            <div class="form-group">
                                <label for="basicInput">Deadline</label>
                                <input type="datetime-local" name="deadline" class="form-control"
                                       value="{{ old('deadline') }}" id="basicInput" placeholder="Deadline">
                            </div>

                            <!-- Add other fields in a similar manner... -->

                            <div class="form-group d-none">
                                <label for="basicInput">Ekspedisi</label>
                                <select name="ekspedisi" class="form-control">
                                    <!-- Assuming you have a list of ekspedisi, you can dynamically populate the options -->
                                    @foreach($ekspedisiList as $ekspedisi)
                                        <option value="{{ $ekspedisi->id }}">{{ $ekspedisi->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Add other fields in a similar manner... -->

                            <div class="form-group">
                                <label for="formFile" class="form-label">Attachment</label>
                                <input name="attachment" class="form-control" type="file" id="formFile"
                                       accept="application/pdf" onchange="">
                            </div>

                            <iframe src="" id="iframe-pdf" width="120px" height="10px"></iframe>

                            <div class="form-group d-none">
                                <label for="basicInput">Alamat Pengambilan</label>
                                <textarea name="alamat_pengambilan" class="form-control"
                                          placeholder="Alamat Pengambilan">{{ old('alamat_pengambilan') }}</textarea>
                            </div>

                            <div class="form-group d-none">
                                <label for="basicInput">Dijual Kepada</label>
                                <textarea name="dijual_kepada" class="form-control"
                                          placeholder="Dijual Kepada">{{ old('dijual_kepada') }}</textarea>
                            </div>
                            <div class="form-group d-none">
                                <label for="basicInput">Dikirim Ke</label>
                                <textarea name="dikirim_ke" class="form-control"
                                          placeholder="Dikirim Ke">{{ old('dikirim_ke') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="basicInput">Komentar</label>
                                <textarea name="comment_customer" class="form-control"
                                          placeholder="Komentar">{{ old('comment_customer') }}</textarea>
                            </div>


                            <!-- Add other fields in a similar manner... -->
                        </div>

                        <div class="col-md-12">
                            <!-- Other fields... -->

                            <!-- Dynamic Table for Barang -->
                            <div class="form-group">
                                <label for="barangTable">Barang</label>
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
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Rows will be dynamically added here -->
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-primary" id="addRow">Tambah Barang</button>
                            </div>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Add Data</button>
                        </div>
                    </div>
                </form>
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

                cols += '<td>' + counter + '</td>';
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Serverside Codeigniter 3</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

</head>
<body>
    <div class="container">
            <h2>Belajar Serverside Codeigneter 3</h2>
        <button type="button" class="btn btn-primary" onclick="add()">
            Tambah Data
        </button>
        <br><br>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped text-center" id="mytable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Depan</th>
                            <th>Nama Belakang</th>
                            <th>Alamat</th>
                            <th>No Hp</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalData" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Modal title</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body form">
            <form action="#" id="formData">
                <div class="form-group">
                    <input type="hidden" id="id" name="id" value="">
                    <label for="nama_depan">Nama Depan</label>
                    <input type="text" class="form-control" id="nama_depan" name="nama_depan" placeholder="Masukan Nama Depan Anda">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="nama_belakang">Nama Belakang</label>
                    <input type="text" class="form-control" id="nama_belakang" name="nama_belakang" placeholder="Masukan Nama Belakang Anda">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukan Alamat Anda">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="no_hp">No Telpon</label>
                    <input type="number" class="form-control" id="no_hp" name="no_hp" placeholder="Masukan No Telpone Anda">
                    <div class="invalid-feedback"></div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" id="btnSave" onclick="save()">Simpan</button>
        </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <script>
        var saveData;
        var modal = $('#modalData');
        var tableData = $('#mytable');
        var formData = $('#formData');
        var modalTitle = $('#modalTitle');
        var btnSave = $('#btnSave');

        $(document).ready(function(){
            tableData.DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?= base_url('serverside/getData')?>",
                    "type": "POST"
                },
                "columnDefs": [{
                    "target": [-1],
                    "orderable": false
                }]
            })
        });

        function reloadTable(){
            tableData.DataTable().ajax.reload();
        }

        function message(icon, text){
            Swal.fire({
                position: 'center',
                icon: icon,
                title: text,
                showConfirmButton: true,
            })
        }

        function deleteAsk(id, name_depan, nama_belakang){
            Swal.fire({
                text: 'Apakah Anda Ingin Menghapus Data '+ name_depan + ' '+ nama_belakang +' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteData(id);
                }
            })
        }

        function add(){
            saveData = "tambah";
            formData[0].reset();
            modal.modal('show');
            modalTitle.text('Tambah Data');
        }

        function save(){
            btnSave.text('Mohon Tunggu...');
            btnSave.attr('disabled', true);
            if(saveData == 'tambah'){
                url = "<?= base_url('serverside/add')?>"
            }else{
                url = "<?= base_url('serverside/update')?>"
            }
            $.ajax({
                type: "POST",
                url: url,
                data: formData.serialize(),
                dataType: "json",
                success: function (response) {
                    if(response.status == 'success'){
                        modal.modal('hide');
                        btnSave.text('Simpan');
                        btnSave.attr('disabled', false);
                        reloadTable();
                        if(saveData == 'tambah'){
                            message('success', 'Data Berhasil Di Tambah');
                        }else{
                            message('success', 'Data Berhasil Di Ubah');
                        }
                    }else{
                        for(var i = 0; i < response.inputerror.length; i++){
                            $('[name="' + response.inputerror[i] +'"]').addClass('is-invalid');
                            $('[name="' + response.inputerror[i] +'"]').next().text(response.error_string[i]);
                        }
                    }
                    btnSave.text('Simpan');
                    btnSave.attr('disabled', false);
                },
                error: function(){
                    message('error', 'Sedang Gangguan Server');
                }
            });
        }

        function byid(id, type){
            if(type == 'edit'){
                saveData = 'edit';
                formData[0].reset();
            }

            $.ajax({
                type: "GET",
                url: "<?= base_url('serverside/byid/')?>" + id,
                dataType: "JSON",
                success: function (response) {
                    if(type == 'edit'){
                        formData.find('input').removeClass('is-invalid');
                        modal.modal('show');
                        modalTitle.text('Simpan');
                        btnSave.text('Simpan');
                        btnSave.attr('disabled', false);
                        $('[name="id"]').val(response.id);
                        $('[name="nama_depan"]').val(response.nama_depan);
                        $('[name="nama_belakang"]').val(response.nama_belakang);
                        $('[name="alamat"]').val(response.alamat);
                        $('[name="no_hp"]').val(response.no_hp);
                    }else{
                        deleteAsk(response.id, response.nama_depan, response.nama_belakang);
                    }
                },
                error: function(){
                    message('error', 'Sedang Gangguan Server');
                }
            });
        }

        function deleteData(id){
            $.ajax({
                type: "POST",
                url: "<?= base_url('serverside/delete/')?>" + id,
                dataType: "json",
                success: function (response) {
                    message('success', 'Data Berhasil Di Hapus');
                    reloadTable();
                },
                error: function(){
                    message('erroe', 'Sedang Gangguan Server');
                }
            });
        }
    </script>
</body>
</html>
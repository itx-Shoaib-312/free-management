<x-admin-layout title="Area List">
    <x-slot name="plugins_css">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    </x-slot>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Area List</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Available Areas</h3>

                            <div class="card-tools">
                                <button class="btn btn-success btn-sm" data-toggle="modal"
                                    data-target="#createAreaModal">
                                    <i class="fas fa-plus">
                                    </i>
                                    Add Area
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped projects" id="areaListTable">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">
                                            Reference Code
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th style="width: 20%">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

    @include('admin.area.modals.create')
    @include('admin.area.modals.edit')


    <x-slot name="plugins_js">
        <!-- DataTables  & Plugins -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    </x-slot>
    <x-slot name="scripts">
        <script>
            $(function() {
                $("#areaListTable").DataTable({
                    ajax: {
                        url: "{{ route('admin.area.index.ajax') }}",
                        dataSrc: 'areas',
                    },
                    columns: [{
                            data: "ref",
                        },
                        {
                            data: "name",
                        },
                        {
                            data: "action",
                            searchable: false,
                            orderable: false,
                        },
                    ],
                    deferRender: true,
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    responsive: true,
                    info: true,
                    paging: true,
                    order: [
                        [0, 'asc']
                    ],
                });
            });

            $(document).on('submit', '#createAreaForm', async function(event) {
                event.preventDefault();
                const createAreaForm = $(this);
                const formData = new FormData(createAreaForm[0]);

                createAreaForm.find('input, textarea, select, button').attr('disabled', true);
                createAreaForm.find('.is-invalid').removeClass('is-invalid');
                createAreaForm.find('.invalid-feedback').remove();

                try {
                    const response = await $.ajax({
                        method: 'POST',
                        url: "{{ route('admin.area.store') }}",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                    })

                    $('#createAreaModal').modal('hide');

                    // Create success sweet alert toast
                    swalToast.fire({
                        icon: 'success',
                        title: response.content,
                    });

                    $("#areaListTable").DataTable().ajax.reload(null, false);
                    createAreaForm.get(0).reset();
                } catch (err) {
                    if (err.status === 422) {
                        const errors = err.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            const err = errors[key];
                            const input = createAreaForm.find(`[name="${key}"]`);
                            if (input.length > 0) {
                                input.addClass('is-invalid');
                                input.parent().append(
                                    $("<span class='invalid-feedback'>").html(err.join('<br/>'))
                                );
                            } else {
                                console.error(`Input with name ${key} doesn't exist!`);
                            }
                        });
                    }
                }
                createAreaForm.find('input, textarea, select, button').removeAttr(
                    'disabled');
            });

            $(document).on('click', '.area-edit-btn', async function(event) {
                const btn = $(this)[0];
                try {
                    const area_id = btn.getAttribute('data-area-id');
                    btn.setAttribute('disabled', true);

                    const response = await $.ajax({
                        method: 'GET',
                        url: "{{ route('admin.area.show', '__AREA_ID__') }}".replace('__AREA_ID__', area_id),
                    });

                    const areaDetails = response.area;

                    $('#editAreaModal [name="ref"]').val(areaDetails.ref);
                    $('#editAreaModal [name="name"]').val(areaDetails.name);
                    $('#editAreaModal').attr('data-area-id', area_id);
                    $('#editAreaModal').modal('show');

                } catch (err) {
                    console.log(err)
                }
                btn.removeAttribute('disabled');
            });

            $(document).on('submit', '#editAreaForm', async function(event) {
                event.preventDefault();
                const editAreaForm = $(this);
                const area_id = $('#editAreaModal').attr('data-area-id');
                const formData = new FormData(editAreaForm.get(0));

                try {
                    editAreaForm.find('input, textarea, select, button').attr('disabled', true);

                    editAreaForm.find('.is-invalid').removeClass('is-invalid');
                    editAreaForm.find('.invalid-feedback').remove();

                    const response = await $.ajax({
                        method: 'POST',
                        url: "{{ route('admin.area.update', '__AREA_ID__') }}".replace('__AREA_ID__', area_id),
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        },
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                    })

                    $('#editAreaModal').modal('hide');

                    // Create success sweet alert toast
                    swalToast.fire({
                        icon: 'success',
                        title: response.content,
                    });

                    $("#areaListTable").DataTable().ajax.reload(null, false);
                    editAreaForm.get(0).reset();

                } catch (err) {
                    if (err.status === 422) {
                        const errors = err.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            const err = errors[key];
                            const input = editAreaForm.find(`[name="${key}"]`);
                            if (input.length > 0) {
                                input.addClass('is-invalid');
                                input.parent().append(
                                    $("<span class='invalid-feedback'>").html(err.join('<br/>'))
                                );
                            } else {
                                console.error(`Input with name ${key} doesn't exist!`);
                            }
                        });
                    }
                }

                editAreaForm.find('input, textarea, select, button').removeAttr(
                    'disabled');
            });

            $(document).on('click', '.delete-area-btn', async function(event) {
                const area_id = $(this).attr('data-area-id');

                const result = await Swal.fire({
                    icon: 'warning',
                    title: "Are you sure you want to delete this record?",
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    denyButtonText: "No",
                });

                const formData = new FormData();
                formData.append('_method', 'DELETE');

                if (result.isConfirmed) {
                    try {
                        const response = await $.ajax({
                            method: 'POST',
                            url: "{{ route('admin.area.destroy', '__AREA_ID__') }}".replace('__AREA_ID__',
                                area_id),
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            },
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                        });

                        // Create success sweet alert toast
                        swalToast.fire({
                            icon: 'success',
                            title: response.content,
                        });


                        $("#areaListTable").DataTable().ajax.reload(null, false);
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: `${err.status} Error occured while deleting record!`,
                            html: err.message
                        });
                    }
                }
            });
        </script>
    </x-slot>
</x-admin-layout>

@extends('admin.base.base')

@section('content')
    <link href="{{ asset('css/admin/files.css') }}" rel="stylesheet">
    @if (session('notify_success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('notify_success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('notify_failure'))
        <div class="alert alert-warning alert-dismissible fade show">
            {{ session('notify_failure') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div id="notSelected" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение загрузки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы не выбрали файлы для загрузки</p>
                </div>
            </div>
        </div>
    </div>
    <div id="confirmDelete" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение удаления</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вы действительно хотите удалить файл?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn confirm-delete" data-dismiss="modal">Да</button>
                    <button type="button" class="btn" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    <div id="isDeleted" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Успех</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Файл успешно удален</p>
                </div>
            </div>
        </div>
    </div>
    <div id="isFailure" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Неудача</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Не удалось удалить файл</p>
                </div>
            </div>
        </div>
    </div>

    <div class="files-container">
        <div class="container-fluid p-3">
            <div class="row">
                <h3 class="col-12 col-lg-6 mt-2">Файлы</h3>
            </div>
        </div>
        <form action="{{ route('file_upload') }}" method="POST" id="uploadFile" enctype="multipart/form-data">
            @csrf
            <div class="input-group pl-3 pb-3 pr-3">
                <div class="custom-file">
                    <input class="custom-file-input" type="file" multiple id="files" name="files[]">
                    <label class="custom-file-label" for="files" id="file-label" data-browse="Добавить файл">Файлы не выбраны</label>
                </div>
                <div class="input-group-append">
                    <input class="btn btn-outline-secondary" type="submit" name="submit" value="Загрузить">
                </div>
            </div>
        </form>
        @if(!$files->isEmpty())
            <div class="p-3">
                <table class="table bg-light table-hover" id="files-table">
                    <thead class="thead-dark">
                        <tr>
                            <th class="d-none">id</th>
                            <th scope="col" class="pl-8">Название файла</th>
                            <th scope="col" class="pl-8">Дата загрузки</th>
                            <th scope="col" class="pl-8">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            <tr>
                                <td class="d-none file-id">{{ $file->id }}</td>
                                <td class="align-bottom p-8">{{ $file->name }}.{{ $file->extension }}</td>
                                <td class="align-bottom p-8 date">{{ $file->created_at }}</td>
                                <td>
                                    <a href="{{ route('file_download', ['fileId' => $file->id]) }}">
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <a href="#" class="delete" file-id="{{ $file->id }}" data-toggle="modal" data-target="#confirmDelete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="pt-3 pl-2">Нет загруженных файлов</div>
        @endif
    </div>
    <script src="{{ asset('js/local-time.js') }}"></script>
    <script src="{{ asset('js/admin/files-browse.js') }}"></script>
    <script src="{{ asset('js/admin/files-upload.js') }}"></script>
    <script src="{{ asset('js/admin/files-delete.js') }}"></script>
@endsection

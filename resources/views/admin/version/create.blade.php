@extends('admin.common.app')

@section('styles')
    <link href="{{ asset('assets/admin/css/plugins/chosen/chosen.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/plugins/webuploader/webuploader.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加新版本</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="form_basic.html#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="form_basic.html#">选项1</a>
                            </li>
                            <li><a href="form_basic.html#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('admin.version.store') }}" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            @if( count($errors) >0)
                                @foreach($errors->all() as $error)
                                    <p class="text-danger text-center">{{ $error }}</p>
                                @endforeach
                            @endif
                        </div>

                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称：</label>

                            <div class="col-sm-6">
                                <input name="name" id="name" type="text" class="form-control" value="{{ old('name',$version->name) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本号：</label>

                            <div class="col-sm-6">
                                <input type="text" id="version_number" name="version_number" class="form-control" value="{{ old('version_number',$version->version_number) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本说明：</label>
                            <div class="col-sm-6">
                                <input name="description" id="description" type="text" class="form-control" value="{{ old('description',$version->description) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">上传文件：</label>
                            <div id="uploader" class="row">
                                <!--用来存放文件信息-->
                                <div id="thelist" class="col-xs-6 col-sm-6">
                                </div>
                                <div class="col-xs-3 col-sm-3">
                                    <div id="picker">选择文件</div>

                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="file_address" name="version_url" value="">
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary" id="add_device">提交</button>
                            </div>
                            <p>等待文件上传完成后再点击提交</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <!-- Fancy box -->
    <script src="{{ asset('assets/admin/js/plugins/fancybox/jquery.fancybox.js') }}"></script>
    <!-- Chosen -->
    <script src="{{ asset('assets/admin/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <!-- wenUploader -->
    <script type="text/javascript" src="{{ asset('assets/admin/js/plugins/webuploader/webuploader.js') }}"></script>

@endsection
<!-- 自定义js -->
{{--<script src="{{ asset('assets/admin/js/content.js?v=1.0.0') }}"></script>--}}
@section('javascript')
    <script>
        $(document).ready(function () {
            var uploader = WebUploader.create({

                // swf文件路径
                swf: '{{ asset('assets/admin/js/plugins/webuploader/Uploader.swf') }}',

                // 文件接收服务端。
                server: '{{ env('APP_URL') }}' + '/api/fileUpload',

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#picker',

                // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                resize: false,
                auto: true,
                chunked: true,
                chunkSize: 1024*1024*2,
                threads:1
            });
            uploader.on( 'fileQueued', function( file ) {
                $('#thelist').append( '<div id="' + file.id + '" class="col-xs-9">' +
                    '<h1>' + file.name + '</h1>' +
                    '<p>上传中....</p>' +
                    '</div>' );
            });
            // 文件上传过程中创建进度条实时显示。
            uploader.on( 'uploadProgress', function( file, percentage ) {
                var $li = $( '#'+file.id ),
                    $percent = $li.find('.progress .progress-bar');

                // 避免重复创建
                if ( !$percent.length ) {
                    $percent = $('<div class="progress progress-striped active">' +
                        '<div class="progress-bar" role="progressbar" style="width: 0%">' +
                        '</div>' +
                        '</div>').appendTo( $li ).find('.progress-bar');
                }

                $li.find('p.state').text('上传中');

                $percent.css( 'width', percentage * 100 + '%' );
            });

            uploader.on( 'uploadAccept', function ( file, response) {
                if (response.status == 1) { //1表示上传完成
                    $( '#'+file.file.id ).addClass('upload-state-done');
                    $( '#'+file.file.id ).find('p').text('上传完成');
                    $('#file_address').val(response.address);
                }
                if (response.status != 1 && response.status != 2) {
                    $( '#'+file.file.id ).find('p').text('上传失败');
                }
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            /*uploader.on( 'uploadSuccess', function( file ) {
                $( '#'+file.id ).addClass('upload-state-done');
            });*/

            // 上传失败
            /*uploader.on('uploadError', function(file) {
                console.log(file);
                // alert('上传失败');
            });*/

            // 上传完成（不论成功或失败都会执行）
            /*uploader.on( 'uploadComplete', function( file ) {
                console.log(file);
            });*/

            // 上传错误
            /*uploader.on('error', function(status) {
                console.log(status);
                var errorTxt = '';
                if(status == 'Q_TYPE_DENIED') {
                    errorTxt = '文件类型错误';
                } else if(status == 'Q_EXCEED_SIZE_LIMIT') {
                    errorTxt = '文件大小超出限制，请控制在30M以内哦';
                } else {
                    errorTxt = '其他错误';
                }
                alert('提示:'+ errorTxt);
            });*/

            $('.fancybox').fancybox({
                openEffect: 'none',
                closeEffect: 'none'
            });
            var config = {
                '.chosen-select': {},
                '.chosen-select-deselect': {
                    allow_single_deselect: true
                },
                '.chosen-select-no-single': {
                    disable_search_threshold: 10
                },
                '.chosen-select-no-results': {
                    no_results_text: 'Oops, nothing found!'
                },
                '.chosen-select-width': {
                    width: "95%"
                }
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
        });
    </script>
@endsection

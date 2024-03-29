@extends('backend.layout')
@section('content')
    <section class="content-header">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{ request()->get('role')=='user'?'Öğrenciler':'Yöneticiler' }}</h3>

                <div align="right">
                    <a href="{{route('user.create')}}">
                        <button class="btn btn-success">Ekle</button>
                    </a>
                    <a href="{{route('user.import')}}?role={{ request()->get('role') }}">
                        <button class="btn btn-success">Excel İle Ekle</button>
                    </a>
                    <a href="{{ url('/files/kullanici-ekleme-format.xlsx')  }}">
                        <button class="btn btn-info">Excel Şablonunu İndir</button>
                    </a>
                </div>


            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Görsel</th>
                        <th>Ad Soyad</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @foreach($data['users'] as $user)
                        <tr id="item-{{$user->id}}">
                            <td>
                                @if($user['user_file'])
                                    <img width="120" src="/images/users/{{$user['user_file']}}" alt="">
                                @else
                                    {{$user->user_file}}
                                @endif
                            </td>
                            <td class="sortable">{{$user->name}}</td>
                            <td width="5"><a href="{{route('user.edit',$user->id)}}"><i class="fa fa-pencil-square"></i></a>
                            </td>
                            <td width="5"><a href="javascript:void(0)"><i id="@php echo $user->id @endphp"
                                                                          class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#sortable').sortable({
                revert: true,
                handle: ".sortable",
                stop: function (event, ui) {
                    var data = $(this).sortable('serialize');
                    $.ajax({
                        type: "POST",
                        data: data,
                        url: "{{route('user.Sortable')}}",
                        success: function (msg) {
                            // console.log(msg);
                            if (msg) {
                                alertify.success("İşlem Başarılı");
                            } else {
                                alertify.error("İşlem Başarısız");
                            }
                        }
                    });

                }
            });
            $('#sortable').disableSelection();

        });

        $(".fa-trash-o").click(function () {
            destroy_id = $(this).attr('id');

            alertify.confirm('Silme işlemini onaylayın!', 'Bu işlem geri alınamaz',
                function () {

                    $.ajax({
                        type: "DELETE",
                        url: "user/" + destroy_id,
                        success: function (msg) {
                            if (msg) {
                                $("#item-" + destroy_id).remove();
                                alertify.success("Silme İşlemi Başarılı");

                            } else {
                                alertify.error("İşlem Tamamlanamadı");
                            }
                        }
                    });

                },
                function () {
                    alertify.error('Silme işlemi iptal edildi')
                }
            )

        });
    </script>

@endsection
@section('css')@endsection
@section('js')@endsection

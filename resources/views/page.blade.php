@extends('base.base')

@section('content')
    <link href="{{ asset('css/page.css') }}" rel="stylesheet">
    <div class="bg-white p-3 rounded page-container">
        <h3>{{ $page->title }}</h3>
        <p>
            Здравствуйте! Высылаю вам список руководителей преддипломной практики и количество человек, которых руководитель может взять. Вам необходимо встретится с руководителями и договорится о руководстве. После этого подойти на кафедру и вписаться в список к выбранному руководителю, указав Ф.И.О. и группу. В течение дня список будет лежать в красной папке на системнике, в комнате, где сидит Галина Петровна. Вписаться вам необходимо до 05.10.19 Кто до этого срока не впишится, тех я распределю сама и сообщу кого к кому записала.
            Майя Львовна
        </p>
        <p>
            {!! $page->content  !!}
        </p>
    </div>
@endsection
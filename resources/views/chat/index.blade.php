@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="chat-box">
            @foreach ($chats as $chat)
                @if(Auth::user()->id == $chat->user_id)
                <div class="offset-md-4 col-md-1">
                    {{ Auth::user()->name }}
                </div>
                <div class="offset-md-4 alert alert-danger">
                    {{ $chat->message }}
                </div>

                @else
                <div class="col-md-1">
                    {{ $users->name }}
                </div>
                <div class="col-md-4 alert alert-info">
                    {{ $chat->message }}
                </div>
                @endif
            @endforeach
            </div>
            <input type="text" class="form-control send">
        </div>
    </div>
</div>
<script>
    $(document).on('keydown', '.send', function(e){
        var message = $(this).val();
        if(!message == '' && e.keyCode == 13 && !e.shiftKey){
            $('.chat-box').append('<div class="offset-md-4 alert alert-danger">'+message+'</div>');
            $(this).val(' ');
            $.ajax({
                url : "{{ route('conversations.store') }}",
                type : "post",
                data : {
                        _token:'{{ csrf_token() }}',
                        message: message,
                        user_id: {{$conversations}}
                        }
            });
        }
    });
    $(function(){
        liveChat();
    })

    function liveChat(){
        @if($existingconid){
            console.log('im in live chat');
            $.ajax({
                url: "{{ route('ajax') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: {{$conversations}},
                    conversation_id: {{$existingconid}}
                    },
                success: function(data){
                    if(data != ''){
                        $('.chat-box').append('<div class="alert alert-info">'+data+'</div>');
                    }
                    setTimeout(liveChat, 1000);

                },
                error: function(){
                    setTimeout(liveChat, 5000);
                }
            });
        }
        @endif
    }
</script>
@endsection

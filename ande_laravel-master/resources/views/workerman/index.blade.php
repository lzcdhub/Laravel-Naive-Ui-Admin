<html>
<header></header>
<body>
<h1>Hello, {{ $name }}</h1>
<button type="button" id="but1">单个</button>
<button type="button" id="but2">全部</button>
<div id="text_panel"></div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
    var socket = new WebSocket("ws://localhost:2345//ws");

    socket.onopen = function (event) {
        $('#text_panel').append('<p>连接开始...</p>');
        socket.send('{"type":"login","uid":{{$id}}}');
    }

    socket.onmessage = function (event) {
        var msg = event.data;
        $('#text_panel').append('<p>' + msg + '</p>');
    }

    socket.onclose = function (event) {
        console.log(event);
        $('#text_panel').append('<p>关闭连接</p>');
    }

    $('#but1').click(function () {
        var data = {"type": "send_message", "to_uid": 2, "message": "我是{{ $name }}"};
        socket.send(JSON.stringify(data));
    });

    $('#but2').click(function () {
        var data = {"type": "send_message_all", "message": "我是{{ $name }}"};
        socket.send(JSON.stringify(data));
    });


</script>
</body>
</html>

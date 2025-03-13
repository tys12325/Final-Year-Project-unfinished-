
@extends('layout.app')

@section('title', 'Live Chat With Us')
@section('head')
    @vite(['resources/css/feedback/AIBot.css'])
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
@endsection

@section('content')
<a href="{{ route('feedBackSupport') }}" id="backButton"><i class="fas fa-chevron-left fa-2x"></i></a>
<div class="AIBody">
    <div class="chat-container">
        <h2 class="thunder-effect">
            T<i class="fa fa-bolt" aria-hidden="true"></i>under Chatbot
        </h2>
    <div class="chat-box" id="chat-box">
        <div class="message bot first-message">
            <i class="fa fa-bolt bot-logo" aria-hidden="true"></i>
            <div class="message-content">Hello! I am Thunder Bot.How can I assist you with finding a university? </div>
          
        </div>
    </div>
    <input type="text" id="user-message" class="input-box" placeholder="Type your message...">
    <button class="send-btn" onclick="sendMessage()">Send</button>
</div>
</div>

@endsection
@section('scripts')

<script>
    $(document).ready(function () {

        scrollToBottom();
    });


    function scrollToBottom() {
        var chatBox = $('#chat-box')[0];
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function sendMessage() {
        var userMessage = $("#user-message").val();
        if (userMessage.trim() === "")
            return;

        $("#chat-box").append(
                '<div class="message user">' +
                '<div class="message-content">' + userMessage + '</div>' +
                '</div>'
                );
        $("#user-message").val("").focus();


        scrollToBottom();


        $.ajax({
            url: "/chatbot",
            type: "POST",
            data: {message: userMessage, _token: "{{ csrf_token() }}"},
            success: function (response) {
                var botMessage = response.message || "Sorry, I couldn't understand that.";

                $("#chat-box").append(
                        '<div class="message bot second-message">' +
                        '<i class="fa fa-bolt bot-logo" aria-hidden="true"></i>' +
                        '<div class="message-content">' + botMessage + '</div>' +
                        '</div>'
                        );


                scrollToBottom();
            },
            error: function () {

                $("#chat-box").append(
                        '<div class="message bot second-message">' +
                        '<i class="fa fa-bolt bot-logo" aria-hidden="true"></i>' +
                        '<div class="message-content">Error: Unable to process request.</div>' +
                        '</div>'
                        );


                scrollToBottom();
            }
        });
    }
$(document).ready(function () {
   
    scrollToBottom();

   
    $('#user-message').on('keydown', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) { 
            e.preventDefault(); 
            sendMessage(); 
        }
    });
});
document.addEventListener("DOMContentLoaded", function() {
    let boltIcon = document.querySelector(".thunder-effect i");
    boltIcon.style.animation = "boltShake 0.5s ease-in-out infinite alternate"; // Continuous shaking effect
});
</script>
@endsection









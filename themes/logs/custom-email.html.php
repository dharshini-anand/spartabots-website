<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#23b550" />
    <meta name="robots" content="noindex, nofollow">
    <title>Spartabots Email</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
    }
    body {
        padding: 0 20%;
        height: 100%;
        background: #1c1c1c;
        font-family: "Roboto", monospace;
        font-size: 22px;
        color: #bbb;
    }
    h1 {
        margin: 20px 0 -15px;
        padding: 25px;
        font: inherit;
        font-size: 1.5em;
        text-align: center;
    }
    form {
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td:first-child {
        width: 23%;
    }
    td {
        padding: 10px;
        text-align: left;
    }
    .icon:not(.ion-paper-airplane) {
        margin-right: 8px;
        font-size: 0.8em;
    }
    .ion-paper-airplane {
        margin-right: 3px;
        font-size: 0.9em;
    }
    label {
        opacity: 0.9;
    }
    input, #from {
        width: 100%;
        outline: 0;
        border: 0;
        border-bottom: 1px solid #555;
        color: #23b550;
    }
    input:focus, #from:focus, #message:focus {
        border-color: #1aa123;
    }
    input {
        font-family: "Roboto", monospace;
        font-size: 22px;
        background: transparent;
    }
    ::-webkit-input-placeholder {
        color: #444;
    }
    ::-moz-placeholder {
        color: #444;
        opacity: 1;
    }
    #message {
        width: 99%;
        height: 20vh;
        border: 1px solid #ccc;
        outline: 0;
        margin-top: 8px;
        padding: 1% 0 0 1%;
        background: #f5f5f5;
        font: inherit;
    }
    #message::-webkit-input-placeholder {
        position: relative;
        top: 50%;
        transform: translateY(-60%);
        color: #bebebe;
        text-align:center;
    }
    #message::-moz-placeholder {
        color: #bebebe;
        text-align:center;
    }
    #message:invalid {
        box-shadow: none;
    }
    #submit {
        margin-right: 10px;
        padding: 12px 20px;
        width: auto;
        float: right;
        border: 0;
        border: 3px solid #23b550;
        background: transparent;
        color: #eee;
        font-family: "Roboto", monospace;
        font-size: 22px;
        transition: 0.3s;
    }
    #submit:hover {
        background: #23b550;
    }
    #submit:active, #submit:focus {
        background: #0e711b;
        outline: 0;
    }
    #submit[disabled] {
        background: transparent;
        color: #999;
        border-color: #666;
        cursor: not-allowed;
    }
    @media only screen and (max-width: 1000px) {
        body {
            padding: 0 10%;
        }
        .icon {
            display: none;
        }
    }
    @media only screen and (max-width: 700px) {
        body {
            padding: 0 4%;
        }
        td {
            padding: 8px;
        }
    }
    @media only screen and (max-width: 500px) {
        body {
            margin: 0;
        }
        form {
            padding: 0;
        }
        td {
            padding: 5px;
        }
        #submit {
            padding: 8px 15px;
        }
    }
    </style>
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
</head>
<body>
    <h1>Spartabots Email</h1>
    <form method="POST" id="emailForm">
        <table>
            <tbody>
                <tr>
                    <td><label for="name"><i class="icon ion-android-person"></i>Name</label></td>
                    <td><input id="name" type="text" name="name" placeholder="Spartabots Exec Board" value="Spartabots" required></td>
                </tr>
                <tr>
                    <td><i class="icon ion-at"></i><label>From</label></td>
                    <td><span id="from" contenteditable="true" spellcheck="false">spartabots-no-reply</span>@spartabots.org</td>
                </tr>
                <tr>
                    <td><i class="icon ion-reply"></i><label for="recipient">Reply to</label></td>
                    <td><input id="replyTo" type="email" name="reply_to" value="skyline.spartabots@gmail.com"></td>
                </tr>
                <tr>
                    <td><i class="icon ion-android-people"></i><label for="recipient">Recipient(s)</label></td>
                    <td><input id="recipient" type="text" name="recipient" placeholder="roboticsmember@example.com" required></td>
                </tr>
                <tr>
                    <td><i class="icon ion-edit"></i><label for="subject">Subject</label></td>
                    <td><input id="subject" type="text" name="subject" placeholder="Something important" required></td>
                </tr>
                <tr>
                    <td><i class="icon ion-ios-keypad"></i><label for="password">Password</label></td>
                    <td><input type="password" id="password" name="password" placeholder="password123" required></td>
                </tr>
                <tr>
                    <td><i class="icon ion-quote"></i><label for="subject">Message</label></td>
                    <td><textarea id="message" name="message" placeholder="You can use HTML" required></textarea></td>
                </tr>
            </tbody>
        </table>
        <button id="submit" disabled type="submit"><i class="icon ion-paper-airplane"></i> Send email</button>
    </form>
    <script>
        var nameValid = true;
        var fromValid = true;
        var recipientsValid = false;
        var subjectValid = false;
        var passwordValid = false;
        var messageValid = false;
        function changeValidity() {
            var isValid = nameValid && fromValid && recipientsValid && subjectValid && passwordValid && messageValid;
            $("#submit").prop("disabled", !isValid);
        }
        function isNotEmpty(element) {
            return $.trim($(element).val()).length > 0;
        }
        $("#name").on("input", function() {
            nameValid = isNotEmpty(this);
            changeValidity();
        });
        $("#from").on("input", function() {
            fromValid = $.trim($(this).text()).length > 0;
            changeValidity();
        });
        $("#recipient").on("input", function() {
            recipientsValid = isNotEmpty(this);
            changeValidity();
        });
        $("#subject").on("input", function() {
            subjectValid = isNotEmpty(this);
            changeValidity();
        });
        $("#password").on("input", function() {
            passwordValid = $.trim($(this).val()).length > 5;
            changeValidity();
        });
        $("#message").change(function() {
            messageValid = isNotEmpty(this);
            changeValidity();
        });
        $("#emailForm").on("submit", function(event) {
            event.preventDefault();

            var senderName = $("#name").val();
            var sender = $("#from").text();
            var replyTo = $("#replyTo").val();
            if($.trim(replyTo).length === 0) replyTo = "skyline.spartabots@gmail.com";
            var recipient = $("#recipient").val();
            var subject = $("#subject").val();
            var message = $("#message").val();
            var password = $("#password").val();

            $.post("/spartabots-custom-email", {
                "sender_name": senderName,
                "sender": sender,
                "reply_to": replyTo,
                "recipient": recipient,
                "subject": subject,
                "message": message,
                "password": password
            }, function(data) {
                alert("Email sent successfully!");
            });
        });
    </script>
</body>
</html>

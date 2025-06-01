<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .email-container {
            max-width: 100%;
            width: 100%;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }

        .message {
            padding: 10px;
            border-radius: 10px;
            margin: 5px 0;
            max-width: 70%;
            position: relative;
            font-size: 14px;
            line-height: 1.4;
        }
        .sender {
            background-color: #d1e7fd;
            align-self: flex-start;
        }
        .receiver {
            background-color: #dcf8c6;
            align-self: flex-end;
        }
        .meta {
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 20px; background-color: #f4f4f4;">
                <table role="presentation" class="email-container" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td class="header">Conversation Transcript</td>
                    </tr>
                    
                            
                    @foreach($body as $conversion)
                        @if($conversion->ai_msg)
                            <tr>
                                <td>
                                    <div class="message sender">
                                        {{ $conversion->message }}
                                        <div class="meta">AI - <small>{{  $conversion->created_at }}</small></div>
                                    </div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    <div class="message receiver">
                                        {{ $conversion->message }}
                                        <div class="meta">Customer - <small>{{  $conversion->created_at }}</small></div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                        
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

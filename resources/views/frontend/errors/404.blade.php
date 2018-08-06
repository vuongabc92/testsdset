<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>
        <meta charset="UTF-8">
        <meta id="viewport" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="msapplication-tap-highlight" content="no"/>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/frontend/css/app.css') }}">
        <style>
            main {
                position: absolute;
                width: 404px;
                height: 404px;
                top: 50%;
                left: 50%;
                margin-top: -202px;
                margin-left: -202px;
                font-family: 'Roboto Mono', monospace;
            }
            .sorry {
                margin-top: 20px;
                text-align: center;
                font-size: 17px;
                line-height: 25px;
                text-transform: uppercase;
                color: #333;
            }
            .gotohomepage-btn {
                padding: 13px 25px;
                background-color: #333;
                color: #fff;
                border-radius: 2px;
                -webkit-border-radius: 2px;
            }
            .gotohomepage-btn:hover,
            .gotohomepage-btn:focus {
                color: #ddd;
                text-decoration: none;
            }
            @media screen and (max-width: 680px) {
                main {
                    position: relative;
                    width: 100%;
                    height: auto;
                    float: left;
                    left: 0;
                    top: 0;
                    margin: 0;
                    margin-top: 20%;
                }
                .wrapper {
                    padding: 15px;
                }
            }
            
            @media screen and (max-width: 480px) {
                main {
                    position: relative;
                    width: 100%;
                    height: auto;
                    float: left;
                    left: 0;
                    top: 0;
                    margin: 0;
                    margin-top: 20%;
                }
                .wrapper {
                    padding: 15px;
                }
            }

        </style>
    </head>
    <body>
        <main>
            <div class="_fwfl wrapper">
                <img class="_fwfl" src="{{ asset('assets/frontend/images/404.png') }}" />
                <div class="_fwfl sorry">
                    <p>{{ _t('notfoundmsg') }}</p>
                    <br/>
                    <a href="/" class="gotohomepage-btn">{{ _t('notfoundbtn') }}</a>
                </div>
            </div>
        </main>
    </body>
</html>

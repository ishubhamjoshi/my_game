<!DOCTYPE html>
<html lang="en">

<head>
    <title>PLAY NEW GOLD WIN : LIVE</title>
    <meta charset="utf-8">
    <meta name="description" content="WWW.PLAYNEWGOLDWIN.COM : GET LIVE RESULTS">
    <meta name="keywords" content="play new gold win, playnewgoldwin.com, play new golden win, playnewgoldenwin.com, gold win lotto, gold win, golden lotto">
    <meta name="author" content="PLAY NEW GOLD WIN">
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body {
            background-color: #800000;
        }

        .header {
            background-color: #ff732b;
            box-shadow: 0 1px 25px #000;
            text-align: center;
            padding: 10px;
            color: #fff;
        }

        h2,
        h3 {
            font-weight: 600;
            margin: 10px 0;
        }

        .spinwheel {
            margin-top: 4%;
            text-align: center;
        }

        .spinwheel img {
            width: 64%;
        }

        .spinwheel .inner-wheel {
            position: absolute;
            top: 9%;
            left: 26.5%;
            width: 46.5%;
            z-index: -1;
        }

        .spin-animation {
            transform: rotate(7200deg);
        }

        /* .spin-animation {
            transform: rotate(150deg);
        } */

        /* Two full spins */
        .marquee {
            background-color: #fff;
            height: 45px;
            font-size: 25px;
            font-weight: 600;
            color: #1c003f;
            margin-top: 10px;
            border-radius: 5px;
        }

        .footer {
            padding: 15px 0;
            text-align: center;
            color: #fff;
            background-color: #ff732b;
            font-weight: 600;
        }

        @keyframes blinkingText {

            0%,
            49% {
                color: #ffc91f;
            }

            50%,
            99% {
                color: transparent;
            }

            100% {
                color: #ffc91f;
            }
        }

        .live-result-text {
            animation: blinkingText 1.2s infinite;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="header row">
            <div class="col-xs-3">
                <h2>Next Draw Time</h2>
                <h3 id="nextDrawTime"></h3>
            </div>
            <div class="col-xs-3">
                <h2>Today's Date</h2>
                <h3><span id="todayDate"></span></h3>
            </div>
            <div class="col-xs-3">
                <h2>Current Time</h2>
                <h3 id="digital-clock"></h3>
            </div>
            <div class="col-xs-3">
                <h2>Time to Draw</h2>
                <h3 id="remainingTime"></h3>
            </div>
        </div>
    </div>

    <div class="container">
        <h3 class="text-center">Meghalaya | मेघालय</h3>
        <div class="marquee">
            <marquee>Result will be open every 15 minutes | रिजल्ट हर 15 मिनट में खुलेगा</marquee>
        </div>
    </div>

    <div class="container-fluid text-center spinwheel">
        <div class="row">
            <div class="col-xs-5">
                <img class="inner-wheel" id="img1" src="assets/images/spinwheel-com.png" alt="wheel">
                <img src="assets/images/spinwheel-stand-andar-red-blue.png">
                <div id="resultAndar">   - </div>
            </div>
            <div class="col-xs-2">
                <img src="assets/images/play-new-gol-win.png">
                <h2 class="live-result-text"> LIVE RESULT </h2>
                <div id="drawResult">
                    <p id="finalResult">--</p>
                </div>
                <a class="btn btn-result btn-sm" href="result-history">RESULTS</a>
            </div>
            <div class="col-xs-5">
                <img class="inner-wheel" id="img2" src="assets/images/spinwheel-com.png" alt="wheel">
                <img src="assets/images/spinwheel-stand-bahar-red-blue.png">
                <div id="resultBahar"> - </div>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; 2020 - 2025 Play New Gold Win. All Rights Reserved.
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/game.js"></script>

</body>

</html>

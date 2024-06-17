<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="styles/payment.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(function () {
            $("#header").load("navbar.php");
            $("#footer").load("footer.html");
        });
    </script>
</head>

<body>
    <div id="header"></div>

    <div class="container">
        <div class="col1">
            <div class="card">
                <div class="front">
                    <div class="type">
                        <img class="bankid" />
                    </div>
                    <span class="chip"></span>
                    <span class="card_number">&#x25CF;&#x25CF;&#x25CF;&#x25CF; &#x25CF;&#x25CF;&#x25CF;&#x25CF; &#x25CF;&#x25CF;&#x25CF;&#x25CF; &#x25CF;&#x25CF;&#x25CF;&#x25CF; </span>
                    <div class="date"><span class="date_value">MM / YYYY</span></div>
                    <span class="fullname">FULL NAME</span>
                </div>
                <div class="back">
                    <div class="magnetic"></div>
                    <div class="bar"></div>
                    <span class="seccode">&#x25CF;&#x25CF;&#x25CF;</span>
                    <span class="chip"></span><span class="disclaimer">This card is property of Random Bank of Random corporation. <br> If found please return to Random Bank of Random corporation - 21968 Paris, Verdi Street, 34 </span>
                </div>
            </div>
        </div>
        <div class="col2">
        <form method="post" action="process_payment.php">
    <label for="funds">Funds:</label>
    <input type="number" class="inputFunds" name="funds" id="funds" />
    <button class="buy" type="submit"><i class="material-icons">lock</i> Pay --.-- USD</button>
</form>


        </div>
    </div>
    <div id="footer"></div>

</body>

</html>

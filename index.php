<?php
function aprs_passcode($callsign) {
    $stophere = strpos($callsign, '-');
    if ($stophere) {
        $callsign = substr($callsign, 0, $stophere);
    }
    $realcall = strtoupper(substr($callsign, 0, 10));

    $hash = 0x73e2;
    $i = 0;
    $len = strlen($realcall);

    while ($i < $len) {
        $hash ^= ord(substr($realcall, $i, 1)) << 8;
        if ($i + 1 < $len) {
            $hash ^= ord(substr($realcall, $i + 1, 1));
        }
        $i += 2;
    }

    return $hash & 0x7fff;
}

$callsign = "";
$passcode = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $callsign = isset($_POST["callsign"]) ? trim($_POST["callsign"]) : "";
    $passcode = $callsign ? aprs_passcode($callsign) : null;

    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?') . ($passcode !== null ? "?callsign=" . urlencode($callsign) . "&passcode=" . $passcode : ""));
    exit;
}

if (isset($_GET['callsign']) && isset($_GET['passcode'])) {
    $callsign = urldecode($_GET['callsign']);
    $passcode = $_GET['passcode'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>9M2PJU APRS Passcode Generator</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            width: min(400px, 90%);
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"] {
            width: calc(100% - 12px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .result {
            margin-top: 20px;
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        .result strong {
            font-size: 1.2em;
            color: #007bff;
        }

        footer {
            width: min(400px, 90%);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 20px;
        }

        footer a {
            color: #007bff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><a href="https://passcode.infy.uk/">9M2PJU APRS Passcode Generator</a></h2>
        <form method="post">
            <label for="callsign">Callsign:</label>
            <input type="text" name="callsign" id="callsign" required>
            <input type="submit" value="Generate">
        </form>

        <div class="result">
            <?php if ($passcode !== null): ?>
                <p>Passcode for <?php echo htmlspecialchars($callsign); ?>: <strong><?php echo $passcode; ?></strong></p>
            <?php endif; ?>
        </div>
    </div>

<footer>
    Made for you by <a href="https://hamradio.my" target="_blank">9M2PJU</a> 🥷
    <br>
    <br>
    GitHub repository <a href="https://github.com/9M2PJU/aprs-passcode-generator" target="_blank">https://github.com/9M2PJU/aprs-passcode-generator</a>
    <br>
    <br>
    This project is licensed under the <a href="https://www.gnu.org/licenses/agpl-3.0.html" target="_blank">AGPL 3.0 License</a>.
</footer>


</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="View the NCZ Blockchain">
    <meta property="og:title" content="NCZ OP_RETURN WALL">
    <meta property="og:description" content="View the NCZ Blockchain">
    <meta property="og:image" content="https://info.nanocheeze.com/images/ncz.png"> <!-- Replace with your OG image URL -->
    <link rel="icon" href="https://info.nanocheeze.com/favicon.ico" type="image/x-icon"> <!-- Replace with your favicon URL -->
    <title>NCZ OP_RETURN WALL</title>
<style>
    #userMessage {
        width: 300px;
        height: 50px;
    }
.string-cell {
    word-break: break-word; /* Ensures that long strings will wrap */
    max-width: 250px; /* You can adjust this value as needed */
}

</style>
</head>
<body>
<canvas id="backgroundCanvas" style="position: absolute; width: 100%; height: 50%; left: 0; top: 0; z-index: -1;"></canvas>

<center><br/>
<h1 style="color: red;">NCZ OP_RETURN WALL</h1>
<br/>
<img src="https://info.nanocheeze.com/images/ncz.png">


  <h2 style="color: yellow;">Post Your Own Message On The Blockchain</h2>
    <form id="messageForm">
        <textarea name="message" id="userMessage" placeholder="Enter your message (13-255 characters)" name="message" id="message" maxlength="255" minlength="13" required></textarea><br>
        <button id="SubmitButton" type="submit">Post Message</button>
    </form>
    <div id="successMessage"></div>
<br/>
Post your NCZ address on the <a target="_blank" href="https://airdrop.nanocheeze.com">Proof of Reclamation Airdrop Page</a> to recieve a NanoCheeZe Airdrop!<br/><br/><a target="_blank" href="https://install.nanocheeze.com">Download the NanoCheeZe Coin here!</a><br/><div style="color: red;">(Warning - All posts are permanent)</div><br/><br/>
    <script>
        document.getElementById('messageForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var message = document.getElementById('userMessage').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'https://nanocheeze.com/post-message/', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('successMessage').innerText = 'Your message will post to the NanoCheeZe blockchain and appear on the NCZ wall soon.';
                    document.getElementById('userMessage').value = ''; // Clear the textarea
                }
            };

            xhr.send('message=' + encodeURIComponent(message));
        });
    </script>

<?php


function convertTxtMp4Links($string) {
    $txtMp4Regex = '/\b([a-zA-Z0-9_-]+\.txt\.mp4)\b/';

    return preg_replace_callback($txtMp4Regex, function($matches) {
        $fileName = $matches[1];
        //$iframeUrl = "https://www.xtdevelopment.net/video/video.php?file=" . urlencode($fileName);
        $iframeUrl = "https://www.xtdevelopment.net/video/" . urlencode($fileName);

        // Scale the iframe content
        return "<a href=\"$iframeUrl\" target=\"_blank\">$fileName</a><br/>" . 
               "<div style=\"width:80%; overflow-x: hidden;\">" .
               "<iframe src=\"$iframeUrl\" style=\"width:100%; height:450px; transform: scale(1); transform-origin: 0 0;\" frameborder=\"0\" allowfullscreen></iframe></div>";
    }, $string);
}

function convertUrlsToLinks($string) {
    $string = convertTxtMp4Links($string);

    $urlRegex = '/\b(https?:\/\/)?(?:www\.)?([a-z0-9.-]+\.[a-z]{2,}(\/\S*)?)\b/i';

    return preg_replace_callback($urlRegex, function($matches) use ($string) {
        $domainAndPath = $matches[2];
        $protocol = $matches[1] ?: 'http://';
        $url = $protocol . $domainAndPath;
        $displayText = $matches[0];

        // Check if this part of the string was already processed as a .txt.mp4 link
        if (strpos($string, $displayText) !== false && strpos($string, "<iframe") !== false) {
            return $displayText; // Skip processing this link
        }

        if (preg_match('/\.(gif|jpg|jpeg|png)$/', strtolower($domainAndPath))) {
            return "<a href=\"$url\" target=\"_blank\">$displayText</a><br/><a href=\"$url\" target=\"_blank\"><img src=\"$url\" style=\"max-width:50%;height:auto;\"></a>";
        } else {
            $properUrl = $matches[1] ? $url : "http://$domainAndPath";
            return "<a href=\"$properUrl\" target=\"_blank\">$displayText</a>";
        }
    }, $string);
}

echo "<style>
        body {
            background-color: #202020; /* Set the background color */
            color: white; /* Set the text color */
        }
        a:link, a:visited, a:hover, a:active {
            color: gray; /* Set link colors */
        }
        td, th { 
            padding: 8px; 
            text-align: left; 
            border-bottom: 1px solid #ddd;
        }
        .hex-cell { 
            word-break: break-all; /* Break long strings only in hex cells */
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 0 auto; /* Center the table */
        }
        th {
            background-color: #333; /* Slate color for table header */
        }
      </style>";

$api_url = "https://nanocheeze.com/get-file-content/"; // Replace with your FastAPI URL

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);

if ($response === false) {
    // Display error if curl_exec fails
    echo 'cURL error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_status != 200) {
    // Display error if HTTP status code is not 200 OK
    echo 'HTTP request failed with status ' . $http_status;
    exit;
}

// Decode the JSON response to get the file content
$response_data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    // Display error if JSON decoding fails
    echo 'JSON decoding error: ' . json_last_error_msg();
    exit;
}

$file_content = $response_data['content'] ?? 'No content available';

// Format and display the content


echo "<table><tr><th>Block</th><th>TXID</th><th>Hex</th><th>String</th></tr>";

$lines = explode("\n", $file_content);
$table_rows = [];
$current_row = "";

foreach ($lines as $line) {
    if (strpos($line, "<-------->") !== false) {
        if ($current_row != "") {
            $table_rows[] = $current_row;
            $current_row = "<tr>";
        } else {
            $current_row = "<tr>";
        }
    } elseif (strpos($line, "Block:") !== false) {
        $current_row .= "<td style='color: yellow;'>" . htmlspecialchars(substr($line, 7)) . "</td>";
    } elseif (strpos($line, "TXID:") !== false) {
        $txid = trim(substr($line, 6));
        $txid_short = substr($txid, 0, 5) . "..." . substr($txid, -5);
        $current_row .= "<td><a href='https://nanocheeze.com/explorer/tx/$txid'>$txid_short</a></td>";
    } elseif (strpos($line, "Hex:") !== false) {
        $current_row .= "<td style='color: red;' class='hex-cell' width='20%'><font size=1>" . htmlspecialchars(substr($line, 5)) . "</font></td>";
    }     elseif (strpos($line, "String:") !== false) {
        $stringContent = htmlspecialchars(substr($line, 8));
        $convertedContent = convertUrlsToLinks($stringContent);
        $current_row .= "<td class='string-cell'>" . $convertedContent . "</td></tr>";
    }

}

if ($current_row != "") {
    $table_rows[] = $current_row;
}

// Output the rows in reverse order
foreach (array_reverse($table_rows) as $row) {
    echo $row;
}

echo "</table>";
?>
</center>

<script>
    var lastContent = null;
    var fileUrl = "https://nanocheeze.com/get-file-content/"; // URL to your text file

    function fetchFileContent() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', fileUrl, true);

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var currentContent = this.responseText;
                if (lastContent !== null && currentContent !== lastContent) {
                    // Check if the textarea is empty or not focused before refreshing
                    if (isTextAreaEmptyAndNotFocused()) {
                        lastContent = currentContent; 
                        window.location.reload();
                    }
                }
                //lastContent = currentContent;
            }
        };

        xhr.send();
    }

    function isTextAreaEmptyAndNotFocused() {
        var userMessage = document.getElementById('userMessage');
        return userMessage.value.trim() === '' && document.activeElement !== userMessage;
    }

    // Fetch file content when the page loads
    window.onload = fetchFileContent;

    // Recheck the file every minute
    setInterval(fetchFileContent, 60000); // 60000 milliseconds = 1 minute
</script>

<script>
    var fileUrl = "https://nanocheeze.com/get-file-content/";
    var canvas = document.getElementById('backgroundCanvas');
    var ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    var maxStrings = 25;
    var textObjects = [];

    function updateCanvasSize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    }

    window.addEventListener('resize', updateCanvasSize);

    function wrapText(context, text, x, y, maxWidth, lineHeight) {
        var words = text.split(' ');
        var line = '';

        for (var n = 0; n < words.length; n++) {
            var testLine = line + words[n] + ' ';
            var metrics = context.measureText(testLine);
            var testWidth = metrics.width;
            if (testWidth > maxWidth && n > 0) {
                context.fillText(line, x, y);
                line = words[n] + ' ';
                y += lineHeight;
            } else {
                line = testLine;
            }
        }
        context.fillText(line, x, y);
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillStyle = 'rgba(0, 255, 0, 0.7)';
        
        textObjects.forEach(function(textObj) {
            textObj.y += textObj.speedY;
            textObj.x += textObj.speedX;

            if (textObj.y > canvas.height || textObj.x > canvas.width) {
                textObj.y = Math.random() * canvas.height;
                textObj.x = Math.random() * canvas.width;
            }

            wrapText(ctx, textObj.text, textObj.x, textObj.y, 200, 20);
        });

        requestAnimationFrame(draw);
    }

    function createTextObjects(stringArray) {
        stringArray.forEach(function(text, index) {
            if (index < maxStrings) {
                var textObj = {
                    text: text,
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    speedX: Math.random() * 2 - 1,
                    speedY: Math.random() * 2 - 1
                };
                textObjects.push(textObj);
            }
        });
    }

function fetchFileContent() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', fileUrl, true);
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var content = JSON.parse(this.responseText).content;
            var blocks = content.split('<-------->');
            var stringArray = [];
            blocks.forEach(function(block) {
                var stringStart = block.indexOf("String:");
                if (stringStart !== -1) {
                    var stringEnd = block.indexOf("\n", stringStart);
                    if (stringEnd === -1) stringEnd = block.length;
                    var extractedText = block.substring(stringStart + "String:".length, stringEnd).trim();
                    stringArray.push(extractedText);
                }
            });
            stringArray = stringArray.slice(0, maxStrings);
            createTextObjects(stringArray);
        }
    };
    xhr.send();
}




</script>



<script>


    var maxStrings = 25;
    var textObjects2 = [];

  






function fetchFileContent2() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', fileUrl, true);
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var blocks = this.responseText.split('<-------->');
            var stringArray = [];
            blocks.forEach(function(block) {
                var stringStart = block.indexOf("String:");
                if (stringStart !== -1) {
                    var stringEnd = block.indexOf("\n", stringStart);
                    var extractedText = block.substring(stringStart + "String:".length, stringEnd).trim();
                    // Replace \r\n and \n with " - "
                    //extractedText = extractedText.replace("\\n", "");
                    extractedText = extractedText.replaceAll("\\n", " ");
                    //extractedText = extractedText.replaceAll("", " - ");
                    if (extractedText.charAt(0) === ' ') {
  extractedText = extractedText.slice(1);
}
if (extractedText.endsWith('String:')) {
  extractedText = extractedText.slice(0, -7); // Remove the last 7 characters ("String:")
}
                    stringArray.push(extractedText);
                }
            });
            stringArray = stringArray.slice(0, maxStrings);
            createTextObjects(stringArray);
        }
    };
    xhr.send();
}


    window.onload = function() {
        fetchFileContent();

        fetchFileContent2();
        draw();
    };
</script>

</body>
</html>

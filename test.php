<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Value with JavaScript</title>
    <style>
        #myText {
            display: inline-block;
            padding: 8px;
            cursor: default;
            border: none;
            outline: none;
            background-color: transparent;
        }
    </style>
</head>
<body>

    <span id="myText" contenteditable="false"></span>
    <button onclick="setValue()">Set Value</button>

    <script>
        function setValue() {
            // Get the span element by its ID
            var textSpan = document.getElementById("myText");

            // Set the content of the span
            textSpan.textContent = "Hello, World!";
        }
    </script>

</body>
</html>

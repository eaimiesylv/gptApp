<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Gpt Application</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            body {
                font-family: 'Arial', sans-serif;
                margin: 20px;
            }

            h1 {
                text-align: center;
            }

            label {
                display: block;
                margin-bottom: 8px;
            }

            input, select {
                width: 100%;
                padding: 8px;
                margin-bottom: 16px;
                box-sizing: border-box;
            }

            textarea {
                width: 100%;
                height: 150px;
                padding: 8px;
                margin-bottom: 16px;
                box-sizing: border-box;
            }

            button {
                background-color: #4CAF50;
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }

            button:hover {
                background-color: #45a049;
            }

            #output {
                margin-top: 20px;
            }

            img {
                max-width: 100%;
                height: auto;
                margin-bottom: 10px;
            }

            #loadingMessage {
                display: none;
                text-align: center;
                margin-top: 20px;
                color: #888;
            }
        </style>
    </head>
    <body>
        
        <h1>Content Generator</h1>

        <label for="keywordDensity">Select Keyword Density </label>
        <select id="keywordDensity">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>

        <label for="tone">Select Tone/Style:</label>
        <select id="tone">
            <option value="narrative">Narrative</option>
            <option value="authoritative">Authoritative</option>
            <option value="sad">Sad</option>
            <option value="emotional">Emotional</option>
            <option value="inspiring">Inspiring</option>
            <option value="professional">Professional</option>
            <option value="happy">Happy</option>
        </select>

        <label for="prompt">Enter Content:</label>
        <textarea id="prompt" placeholder="Enter your prompt" required></textarea>

        <button onclick="generateContent()">Generate Content</button>

        <div id="loadingMessage">Please wait...</div>
        <div id="output"></div>
     

       
        <script>
            function generateContent() {
                const keywordDensity = document.getElementById('keywordDensity').value;
                const tone = document.getElementById('tone').value;
                const prompt = document.getElementById('prompt').value;
        
                document.getElementById('loadingMessage').style.display = 'block';
                document.getElementById('output').innerHTML = '';
        
                fetch('http://localhost:8000/api/ask', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        prompt: prompt,
                        keyword_density: keywordDensity,
                        tone: tone,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingMessage').style.display = 'none';
        
                 

                    if(data.result.includes("(")){
                        console.log(data.result.includes("image_src")); 
                        let result = data.result.indexOf("image_src");
                        console.log(result);
                        let word ="";
                        let i = result + 9;
                        let end = result + 400
                        for(let i=result; i< end; i++){
                           
                            if(data.result[i] == ")"){
                                break;
                               
                            }else{
                                word= word + data.result[i];
                            }
                           
                        }
                        console.log(word);
                        console.log(" ")
                    }
                        document.getElementById('output').innerHTML = data.result;
                    
                })
                .catch(error => {
                    console.error(error);
                    // Hide loading message
                    document.getElementById('loadingMessage').style.display = 'none';
                    // Display an error message
                    document.getElementById('output').innerHTML = 'Error occurred while generating content.';
                });
            }
        </script>
        
    </body>
</html>

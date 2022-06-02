<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Budgetter</title>
        <link href="css/style.css" rel="stylesheet">
    </head>
    <body>
        <div>
            <form enctype="multipart/form-data" action="{{ route('upload') }}" method="POST">
                @csrf
                
                <div>
                    <label for="file">Upload PDF</label>
                    <input id="file" type="file" name="file">
                </div>
                
                <div>
                    <label for="tag">Enter tag (optional)</label>
                    <input id="tag" type="text" name="tag">
                </div>
                
                <button type="submit">Parse</button> 
            </form>
            
            <form action="{{ route('write') }}" method="POST">
                @csrf
                
                <label for="tag">Enter tag (optional)</label>
                <input id="tag" type="text" name="tag">
                
                <button type="submit">Write</button> 
            </form>
            
            <div>
                <h3>Read Me</h3>
                <p>
                    <ol>
                        <li>Open MS Excel 2007 - 2012</li>
                        <li>Go to 'Data' tab</li>
                        <li>Select 'From Text' (third option from left) and select the .CSV file you want to import.</li>
                        <li>Select 'Delimited' and 'MS-DOS (PC-8)'</li>
                        <li>Click 'Next' on the pop-up window. Make sure you select 'Comma' in the next window. You should see your data applied into columns below already. You can add any other information if you need to here. Click 'Finish' when you're done with editing.</li>
                    </ol>
                </p>
            </div>
        </div>
    </body>
</html>

<?PHP
//This will create a formatted download page for files within a specified folder.
//The files will be grouped by the top level folders WITHIN the provided folder.

//__DIR__ is the scripts directory.
//Other parts in this script are relative to this path.
$contentLocation = __DIR__ . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR;

//Displays content.
function displayLocalFiles()
{
    global $contentLocation;

    $topLevelFolders = []; //Stores all top level folders.

    //Verify that the given location exists.
    if (!is_dir($contentLocation))
    {
        echo 'Provided content location not found: ' . $contentLocation;
        exit(1);
    }

    $finalOutput = ''; //Initializing return variable.

    //Get list of top level folders.
    $topLevelFolders = getDirectoryContents($contentLocation);
	ksort($topLevelFolders, SORT_FLAG_CASE + SORT_STRING); //Sorting keys. (As case insensitive strings.)

    //Iterating through each top level folder recursively.
    foreach ($topLevelFolders as $folderGroup => $emptyValue)
    {
        //Returns formatted output for this group/top level directory.
        $finalOutput .= '<h2 class="topLevelFolder">' . $folderGroup . '</h2>';
        
        //Create table.
        $finalOutput .= '
        <table>
            <thead>
                <td>File</td>
                <td>File Size</td>
                <td>Download Link</td>
            </thead>
            <tbody>';
        
        //Get contents.
        $finalOutput .= formatOutput($topLevelFolders[$folderGroup]);
        
        //Close table
        $finalOutput .= '
            </tbody>
        </table>';
    }


    
    return $finalOutput;
    // var_dump(getDirContents($contentLocation));
}

//Returns an array of the directory with file information in the provided content folder (recursively).
function getDirectoryContents($contentLocation)
{
    $finalOutput = []; //Stores final array that is returned.

    //Iterating through given directory.
    foreach (new DirectoryIterator($contentLocation) as $fsObject) {
        if ($fsObject->isDir())
        {
            if ($fsObject->isDot()) //Verify it is not '.' or '..'
                continue;
            
            //Use recursion to return the contents of this directory.
            $finalOutput[$fsObject->getBasename()] = getDirectoryContents($fsObject->getPathname());
        }
        else if ($fsObject->isFile())
        {
            $finalOutput[$fsObject->getBasename()] = [
                "Relative Path" => str_replace(__DIR__, '', $fsObject->getRealPath()),
                "Size" => filesize($fsObject->getRealPath())
            ];
        }
    }

    //Returning data.
    return $finalOutput;
}

//Expects the top level folder to group by and prepares all HTML for displaying.
function formatOutput($givenArray, $subLevel = 0)
{
	ksort($givenArray, SORT_FLAG_CASE + SORT_STRING); //Sorting keys. (As case insensitive strings.)

    $finalOutput = ''; //Initializing return variable.

    if (array_key_exists('Relative Path', $givenArray))
    {
        $finalOutput .= '<td>' . $givenArray['Size'] . '</td>'; //File Size.
        $finalOutput .= '<td><a href="' . $givenArray['Relative Path'] . '">' . 'DOWNLOAD' . '</a></td></tr>'; //Download Link
    }
    else
    {
		$foundFolders = []; //Stores what subfolders were found.
        //Iterate through THIS array.
        foreach ($givenArray as $key => $subObject) {
            if (array_key_exists('Relative Path', $subObject))
			{
				$finalOutput .= '<tr><td subLevel="' . $subLevel . '" class="file">' . $key . '</td>'; //File
				$finalOutput .= formatOutput($givenArray[$key]);
			}
            else
				$foundFolders[$key] = ''; //Subfolder.
        }


		if ($foundFolders != [])
		{
			//Iterating through foundFolders recursively.
			foreach ($givenArray as $key => $subObject) {
				if (!array_key_exists($key, $foundFolders))
					continue;
				
				$finalOutput .= '
				<tr>
					<th colspan="3" scope="colgroup" class="subFolderHeader">'
						. $key . 
					'</th>
				</tr>'; //Sub Folder
				$finalOutput .= formatOutput($givenArray[$key], $subLevel + 1);
			}
		}
    }

    return $finalOutput;
}

//Display HTML
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Useful Programs</title>
    <link rel="stylesheet" type="text/css" href="/css/styles.css">
    <script type="text/javascript" src="/js/script.js"></script>
</head>
<body>
    <h1>Useful Programs</h1>
    ' . displayLocalFiles() . '
</body>
</html>
';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attached Files | RFP </title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.8.1/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" />

</head>
<body>

    <div class="container">
        <h2 class="text-center p-4 bg-dark text-white">Attach <span>Documents </span></h2>
    
        <form action="/donee_doc_upload" class="dropzone" id="dropzonewidget" method="POST" enctype="multipart/form-data">
            @csrf
            <input hidden name="documents" id="documents" type="text" />
        </form>    
    </div>


 
    <script>
        var segments = location.href.split('/');
        var action = segments[4];
        console.log(action);
        if (action == 'dropzone') {
            var acceptedFileTypes = "image/*, .psd"; //dropzone requires this param be a comma separated list
            var fileList = new Array;
            var i = 0;
            var callForDzReset = false;
            $("#dropzonewidget").dropzone({
          
                url: "document_upload",
                addRemoveLinks: true,
                maxFiles: 4,
                acceptedFiles: 'image/*',
                maxFilesize: 5,
                init: function () {
                    this.on("success", function (file, serverFileName) {
                        file.serverFn = serverFileName;
                        fileList[i] = {
                            "serverFileName": serverFileName,
                            "fileName": file.name,
                            "fileId": i
                        };
                        i++;
                    });
                }
            });
        }
    </script>
    <script src="http://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.8.1/min/dropzone.min.js" integrity="sha512-OTNPkaN+JCQg2dj6Ht+yuHRHDwsq1WYsU6H0jDYHou/2ZayS2KXCfL28s/p11L0+GSppfPOqwbda47Q97pDP9Q==" crossorigin="anonymous"></script>
  
</body>
</html>
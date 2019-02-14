<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="testpdf.css" />   
</head>
<body>
<header>This is the header</header>
    <div id="content">
      This is the element you only want to capture
    </div>
    <footer>This is the footer</footer>
    <button id="print">Download Pdf</button>

</body>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.debug.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript">

$('#print').click(function() {
    html2canvas(document.querySelector("#content")).then(canvas => {
        var myImage = canvas.toDataURL("image/jpeg,1.0");                   
        var imgWidth = (canvas.width * 20) / 240;
        var imgHeight = (canvas.height * 20) / 240; 
        // jspdf changes
        var pdf = new jsPDF('p', 'mm', 'a4');
        pdf.addImage(myImage, 'JPEG', 15, 2, imgWidth, imgHeight); // 2: 19
        pdf.save('Download.pdf');
});
});
</script>

</html>

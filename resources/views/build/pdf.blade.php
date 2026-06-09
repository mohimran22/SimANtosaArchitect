<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

@page{
    margin:120px 30px 90px 30px;
}

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:10px;
}

.header{
    position:fixed;
    top:-100px;
    left:0;
    right:0;
}

.footer{
    position:fixed;
    bottom:-70px;
    left:0;
    right:0;
}

.page-break{
    page-break-after:always;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #000;
    padding:4px;
    vertical-align: middle;
    word-wrap: break-word;
}

th{
    background:#efefef;
}

.text-center{
    text-align:center;
}

.text-end{
    text-align:right;
}

.group{
    background:#ddd;
    font-weight:bold;
}

</style>
</head>

<body>

<div class="header">
    <img src="{{ public_path('images/header-penawaran.jpg') }}"
         width="100%">
</div>

<div class="footer">
    <img src="{{ public_path('images/footer-penawaran.jpg') }}"
         width="100%">
</div>

@include('build.pdf-kurvas')

<div class="page-break"></div>

@include('build.pdf-rekap')

<div class="page-break"></div>

@include('build.pdf-detail')

</body>
</html>
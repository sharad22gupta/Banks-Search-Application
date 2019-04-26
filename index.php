<?php
$cfg['ExecTimeLimit'] = 6000;
$curl = curl_init();


    curl_setopt_array($curl, array(
        //CURLOPT_URL => "https://musicdemons.com/api/v1/song",

       CURLOPT_URL => "https://vast-shore-74260.herokuapp.com/banks?city=MUMBAI",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
if(isset($_GET['quan'])){
    $quan = $_GET['quan'];
}else {
    $quan = 10;
}
$json = json_decode($response, true);
$no_of_pages = count($json)/$quan;

$no_of_pages = ceil($no_of_pages);

if(isset($_GET['submit'])){

 $num= $_GET['submit'];
    $val = $num * $quan;
}else{
    $val = $quan;
}
?>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Bank Search System</title>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <a class="navbar-brand" href="#">Bank Search Syatem </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
</head>
<body style=" width: 100%">



    <div class="container" style="width:100%;">

        <br /><br />
        <div align="center">
            <input type="text" name="search" id="search" placeholder="Type Bank Info. Here...like name, branch, city, etc." class="form-control" />
        </div>
        <ul class="list-group" id="result"></ul>
        <br />
    </div>

<div id="bigbox" style="width: 100%;">
    <div id ="pagination" style="width: 20%; float: right">

        <div id="info" style="width: 98%; margin: 1%;">

            <br>
            <form method="get" action="index.php">
               <p>Select no. of records per page :
                    <select name='quan'>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                   <button type="submit" class="btn btn-primary">Submit</button>
                </p>
            </form>
            <p>Total no of pages : <?php echo $no_of_pages; ?></p>
            <p>Page No. : <?php

                for ($i= 1; $i < $no_of_pages;$i++){
                    echo "<form method='get' action='#' style='float: left'>";

                    echo "<button type='submit' class='btn btn-primary' name='submit' value='".$i."'>".$i."</button> ";
                    echo "</form>";
                }
                ?></p>
        </div>
    </div>
    <div id="bank" style="width: 80%">
<?php

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo "<div class=\"modal-dialog modal-sm\"> Displaying records from ".($val - $quan)." to ".$val."</div><br>";
    for ($i = ($val - $quan); $i < $val; $i++) {
        echo "<div id='box' style='width: 46%;float: left;margin: 1%; box-shadow: 0 0 10px dimgrey'>";
        echo "<ul class=\"list-group\">";
        echo "<li class=\"list-group-item list-group-item-dark\"><h5>Bank Name: " . $json[$i]['bank_name']."</li></h5>";
        echo " <li class=\"list-group-item\">Address: ". $json[$i]['address']."</li>";
        echo " <li class=\"list-group-item\">District : ". $json[$i]['district']." | City : " .$json[$i]['city']." | State : ". $json[$i]['state']."</li>";
        echo " <li class=\"list-group-item\">Branch : ". $json[$i]['branch']." | Bank ID : ". $json[$i]['bank_id']." | IFSC : ".$json[$i]['ifsc']."</li>";
        echo "</ul>";
        echo "</div>";

    }
}
?>
</div>

</div>
</body>
</html>

<script>

    var obj = $.getJSON('https://vast-shore-74260.herokuapp.com/banks?city=MUMBAI');

    localStorage.setItem('obj',JSON.stringify(obj));
  //  console.log(localStorage);
    $(document).ready(function(){
        $.ajaxSetup({ cache: true });
        $('#search').keyup(function(){
            $('#result').html('');
            $('#state').val('');
            var searchField = $('#search').val();
            var expression = new RegExp(searchField, "i");
            $.getJSON('https://vast-shore-74260.herokuapp.com/banks?city=MUMBAI', function(data) {
                $.each(data, function(key, value){
                    if (value.city.search(expression) != -1 || value.branch.search(expression) != -1 || value.bank_name.search(expression) != -1 || value.ifsc.search(expression) != -1 || value.state.search(expression) != -1 || value.district.search(expression) != -1)
                    {
                        $('#result').append('<li class="list-group-item link-class">'+value.bank_name+' | <span class="text-muted">'+value.branch+' | <span class="text-muted">'+value.city+'</span></span></li>');
                    }
                });
            });
        });

        $('#result').on('click', 'li', function() {
            var click_text = $(this).text().split('|');
            $('#search').val($.trim(click_text[0]));
            $("#result").html('');
        });
    });
</script>

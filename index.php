<?php
require_once 'backend/sdbh.php';
$dbh = new sdbh();

?>
<html>
    <head>
    <meta charset="utf-8" /> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link href="style_form.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <style>
            .container{
                margin-top: 50px;
                border-radius: 15px;
                border: 3px solid #333;
            }
            .col-3{
                background-color: #FF9A00;
                border-radius: 0;
                border-top-left-radius: 13px;
                border-bottom-left-radius: 13px;
                display: flex;
                align-items: center;
                flex-flow: column;
                justify-content: center;
                font-size: 26px;
                font-weight: 900;
            }
            label:not([class="form-check-label"]) {
                font-size: 16px;
                font-weight: 600;
            }
            .form-check-input:checked{
                background-color: #FF9A00;
                border-color: #FF9A00;
            }
            .col-9{
                padding: 25px;
            }
            .btn-primary {
                color: #fff;
                background-color: #FF9A00;
                border-color: #FF9A00;
            }
        </style>
    </head>
    <body>
            <!-- форма расчета -->     
            <div class="container">
            <div class="row row-body">
                <div class="col-3">
                    <span style="text-align: center">Форма обратной связи</span>
                    <i class="bi bi-activity"></i>
                </div> 
                <div class="col-9">
                    <form action="" id="form">
                            <label class="form-label" for="product">Выберите продукт:</label>
                            <select class="form-select" name="product" id="product">
                                <?php
                                    $services = $dbh->make_query('select * from a25_products');
                                    foreach($services as $value) { ?>
                                        <option value=<?php echo $value['ID'] ?>><?php echo $value['NAME'] ?></option>
                                    <?php }
                                ?>
                            </select>

                            <label for="customRange1" class="form-label">Количество дней:</label>
                            <input type="text" class="form-control" id="customRange1" min="1" max="30">

                            <label for="customRange1" class="form-label">Дополнительно:</label>
                            
                            <?php
                                $i = 0;
                                $services = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                                foreach($services as $key => $value) { ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=<?php echo $value?> id=<?php echo "flexCheckChecked".$i?> checked>
                                        <label class="form-check-label" for=<?php echo "flexCheckChecked".$i?>>
                                            <?php echo $key?>
                                        </label>
                                    </div>
                                 <?php $i++; }
                            ?>
                            <button type="submit" class="btn btn-primary" id="btn1">Рассчитать</button>
                    </form>
                </div>   
                <div id="result">

                </div>            
            </div>
        </div>

        <script>
            $("#btn1").on("click", function(event){
            event.preventDefault();
            var vall = [];
            //получил id продукта
            vall.push($("#product").find(":selected").val());
            //получил кол-во дней из textbox'а
            vall.push($("#customRange1").val());
            //получил доп опции
            vall.push($('input:checkbox:checked').map(function() {
                return this.value;
            }).get());
            console.log(vall);
            var posting = $.post( "counter.php", { 'data[]': vall } );
            posting.done(function( response ) {
                $("#result").html(response);
                });    
            })
        </script>
    </body>
</html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link media="print" href="css_solo_para_impresion.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Reenie+Beanie&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <style>
        
        p.saltodepagina{
            page-break-after: always;
        }
        .page {
            border:0.5px solid gray; 
            text-align: center;
            font-family: 'Roboto', sans-serif;
        }
        .page2 {
            text-align: center;
            margin-left: 0px;
            display: inline-block;
            justify-content: center;
        }
        
    </style>
    
    <title>Graficas</title>
</head><body>
    
    <div class="page">
        <br>
        Total Comercios: {{$total_comercios}}
        <br>
        <div>
            <img src="{{$chartTotalComercios}}">
        </div>
        <p style="margin-top: 60px">
            
        </p>
    </div>

    <p class="saltodepagina"></p>

    <div class="page">
        <br>
        Total Comercios Gestionados: {{$total_comercios_gestionados}}
        <br>
        <div>
            <img src="{{$chartTotalComerciosGestionados}}">
        </div>
        <p style="margin-top: 60px">
            
        </p>
    </div>

    
    
  
</body></html>

        

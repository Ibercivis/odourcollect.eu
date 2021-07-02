<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <style type="text/css">
        #outlook a {
            padding: 0;
        }
        body {
            width: 100% !important;
            -webkit-text-size-adjust: none;
            margin: 0;
            padding: 0;
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            font-size: 18px;
            line-height: 125%;
            color:#3b637b;
        }
        img {
            border: none;
            font-size: 14px;
            font-weight: bold;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        p {margin-bottom:20px;}

    </style>
</head>

<body style="margin:0; padding:0; background-color:#FFFFFF;" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
    <p> You are receiving this email as an OdourCollect Zone Administrator because there have been new observations made in your area.</p>
    <p>Zone {{$zone->zone_id}}</p>
    <p>The new observations are:</p>
    <ul>
    <?php 
        $odours = $zone->body;
    ?> 

        <table border="1px">
            <thead>
                <tr>
                    <th><?php echo "Id"?></th>
                    <th><?php echo "User id"?></th>
                    <th><?php echo "Type"?></th>
                    <th><?php echo "Subtype"?></th>
                    <th><?php echo "Intensity"?></th>
                    <th><?php echo "Annoy"?></th>
                    <th><?php echo "Time"?></th>
                    <th><?php echo "Description"?></th>
                    <th><?php echo "Origin"?></th>
                </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($odours); $i++) {?>
                <tr>
                    <td><?php echo $odours[$i]->id_odor; ?></td>
                    <td><?php echo $odours[$i]->id_user; ?></td>
                    <td><?php echo $odours[$i]->type; ?></td>
                    <td><?php echo $odours[$i]->subtype; ?></td>
                    <td><?php echo $odours[$i]->intensity; ?></td>
                    <td><?php echo $odours[$i]->annoy; ?></td>
                    <td><?php echo $odours[$i]->created_at; ?></td>
                    <td><?php echo $odours[$i]->description; ?></td>
                    <td><?php echo $odours[$i]->origin; ?></td>
                    
                </tr>
                
            <?php } ?>
           
       
          </tbody>    
        </table>
    
    </ul>
   
</body>

</html>

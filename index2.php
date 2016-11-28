<?
require 'aws-autoloader.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
  $file = '/tmp/sample-app.log';
  $message = file_get_contents('php://input');
  file_put_contents($file, date('Y-m-d H:i:s') . " Received message: " . $message . "\n", FILE_APPEND);
}
else
{
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Amazon S3 Object Creation Performance Benchmarking</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster+Two" type="text/css">
    <link rel="icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <link rel="shortcut icon" href="https://awsmedia.s3.amazonaws.com/favicon.ico" type="image/ico" >
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="/styles.css" type="text/css">
</head>
<body>
    <section class="congratulations">
        
         <h1>S3 Object Creation Performance Benchmarking</h1>
         <img src="AWSS3.png">
    
    </section>

    <section class="instructions">
  
        <h2>Trace Results: Creation of 100 Objects of 100 bytes</h2>
        <ul>
            
            <?
            
            function mdiff($date1, $date2){
              //Absolute val of Date 1 in seconds from  (EPOCH Time) - Date 2 in seconds from (EPOCH Time)
              $diff = abs(strtotime($date1->format('d-m-Y H:i:s.u'))-strtotime($date2->format('d-m-Y H:i:s.u')));

              //Creates variables for the microseconds of date1 and date2
              $micro1 = $date1->format("u");
              $micro2 = $date2->format("u");

              //Difference between these micro seconds:
              $diffmicro = $micro1 - $micro2;

              list($sec,$micro) = explode('.',((($diff) * 1000000) + $diffmicro )/1000000);

              //Creates the variable that will hold the seconds (?):
              $difference = $sec . "." . str_pad($micro,6,'0');

              return $difference;
            }
           
           $s3ObjectName = "S3-01 - "  . date("Ymd - His") . " - " . $_SERVER['REMOTE_ADDR'] ;
            
           $s3Client = new Aws\S3\S3Client([
           'version'     => 'latest',
           'region'      => 'us-west-2',
            'credentials' => [
            'key'    => 'AKIAJKI7J67CIUAFNRIQ',
             'secret' => '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',
              ],
              ]);
          ?>
          
          <b>Connection Successful<BR><BR>Starting Loop - Creation of 100 Objects</b><BR><BR>
          <?  
            //$startTime = DateTime::createFromFormat('U.u', microtime(true));
            
             
             $now = DateTime::createFromFormat('U.u', microtime(true));
             $startTime = $now;
             
            
            for ($x = 1; $x <= 100; $x++) {
            
              $result = $s3Client->putObject([
                'Bucket' => 'tmpobjects',
                'Key'    => $s3ObjectName,
                'Body'   => 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
                ]);
            
             $now = DateTime::createFromFormat('U.u', microtime(true));
             ?><li><?echo $x;?> - <?echo $now->format("m-d-Y H:i:s.u");?></li><?
             $endTime=$now;                                                     
             } 

             //$endTime = DateTime::createFromFormat('U.u', microtime(true));
              $timeDifference = mdiff($startTime,$endTime);
              $timeperunit = ($timeDifference * 1000)/ 100;
              $unitspersecond = 1000/$timeperunit;
              $unitsperminute = 60*$unitspersecond;
              
              
          ?>
       
        </ul>

        <h2>Results</h2>
        <ul>
            <li>Total Creation Time: <? echo $timeDifference; ?> seconds</a></li>
            <li>Unit Creation Time: <? echo $timeperunit; ?> milliseconds</li>
            <li>Objects per Second: <? echo number_format($unitspersecond); ?></li>
            <li>Objects per Minute: <? echo number_format($unitsperminute); ?></li>
        </ul>
        <?
           
        ?>
        
    </section>

    <!--[if lt IE 9]><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
</body>
</html>
<? 
} 
?>

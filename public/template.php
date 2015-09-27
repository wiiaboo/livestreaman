<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo sprintf( 'Stream do %s', $cur_stream ) ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="/css/style.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <!-- <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->
        <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        
    </head>
    <body>

        <!-- navigation -->
        <nav class="main-nav" id="main-nav">
            <div class="pill">
                <a href="#">QUEM POSSO VISIONAR</a>
            </div>
            
            <?php foreach( $streamers_list as $streamer ): ?>
                <div class="pill streamer">
                    <a href="/<?php echo $streamer['stream_url'] ?>"><?php echo $streamer['name'] ?></a>
                    <?php if( $streamer['live'] ): ?>
                        <span class="live"></span>
                    <?php else: ?>
                        <span class="off"></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </nav>
        <!-- navigation -->
            
        <!-- wrapper -->
        <div class="page-wrap">
            <header class="main-header">
                <a href="#main-nav" id="toggle-nav" class="open-menu">
                    ☰
                </a>
                <a href="#" class="close-menu">
                    ☰
                </a>

                <h1><?php echo sprintf( 'Stream do %s', $cur_stream ) ?></h1>
            </header>
  
            <div class="content">
                <!-- player -->
                <div id='playersXnbojBtEFKm'></div>
                <p><a href=<?php echo sprintf( '"rtmp://ls.fsbn.eu/live/%s.flv"', $cur_stream ) ?>>link rtmp (0 delay)</a>
                <a href=<?php echo sprintf( '"/hls/%s.m3u8"', $cur_stream ) ?>>link hls (aparelhos sem flash) (~40s de delay)</a></p>
            </div>
        </div>    
        <!-- wrapper -->
        
        <div class="main-footer">
            <span>Copyright @2015<span>
        </div>
        
        <!-- scripts -->
        <script src="/js/jquery.js"></script>
        <script src="http://jwpsrv.com/library/QlceYJEcEeOYBCIACi0I_Q.js"></script>
        <script type='text/javascript'>
            jwplayer('playersXnbojBtEFKm').setup({
                file: '<?php echo sprintf( 'rtmp://ls.fsbn.eu/live/flv:%s.flv', $cur_stream ) ?>',
                autostart: 'true',
                width: '100%',
                aspectratio: '16:9',
                rtmp: {
                    bufferlength: 0.1
                },
            });
        </script>
        <script type="text/javascript">
            function thisShitLive() {
                $.ajax({
                    type: "GET",
                url: "/stats/",
                dataType: "xml",
                success: function(xml) {
                    $streamers = $(document).find("#main-nav > div.streamer");
                    for (var i = $streamers.length - 1; i >= 1; i--) {
                        $streamerurl = $streamers[i].children[0].href.split("/")[3];
                        $streaming = $(xml).find("name:contains("+$streamerurl+") ~ publishing");
                        if ($streaming.length > 0) {
                            $streamers[i].children[1].className = "live";
                        } else {
                            $streamers[i].children[1].className = "off";
                        }
                    };
                    setTimeout(thisShitLive,5000);
                }
                });
            };
            $(document).ready(function(){
                <?php if( ! isset( $_GET['stream'] ) ):?>
                    $('.open-menu')[0].click();
                <?php endif; ?>
                thisShitLive();
            });
        </script>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo sprintf( 'Stream do %s', $cur_stream ) ?></title>

        <!-- Bootstrap Core CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="/css/style.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet" type="text/css">
        
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
                <div id='player'></div>
                <p>
                    <a href="/stats">Viewers: <span id="viewers">0</span></a>
                    <a href=<?php echo sprintf( '"rtmp://ls.fsbn.eu/live/%s.flv"', $cur_stream ) ?>>link rtmp (0 delay)</a>
                    <a href=<?php echo sprintf( '"/hls/%s.m3u8"', $cur_stream ) ?>>link hls (aparelhos sem flash) (~40s de delay)</a>
                    <?php if ($own_ip === true) echo '<a onclick="dropStream()" style="cursor:pointer">kill stream</a>' ?>

                </p>
            </div>
        </div>    
        <!-- wrapper -->
        
        <!-- scripts -->
        <!--<script src="//jwpsrv.com/library/QlceYJEcEeOYBCIACi0I_Q.js"></script>-->
        <script src="https://content.jwplatform.com/libraries/So79Dm4F.js"></script>
        <script type='text/javascript'>
            jwplayer('player').setup({
                file: <?php echo sprintf( "'rtmp://ls.fsbn.eu/live/flv:%s.flv'", $cur_stream ) ?>,
                autostart: 'true',
                width: '100%',
                aspectratio: '16:9',
                rtmp: {
                    bufferlength: 0.1
                },
            });
        </script>
        <script type="text/javascript">
<?php if ($own_ip === true): ?>
            function dropStream() {
                $.get("/control/drop/publisher?app=live&name=<?php echo $cur_stream; ?>");
            }
<?php endif; ?>
            $streamers = $(document).find("#main-nav > div.streamer");
            function thisShitLive() {
                $.get("/live", function(data, status){
                    if (status == "success") {
                        $livestreamers = data.split(",");
                        for (var i = $streamers.length - 1; i >= 0; i--) {
                            $streamerurl = $streamers[i].children[0].href.split("/").pop();
                            if ($livestreamers.includes($streamerurl)) {
                                $streamers[i].children[1].className = "live";
                            } else {
                                $streamers[i].children[1].className = "off";
                            };
                        };
                        if ($livestreamers[0] != "") {
                            setTimeout(thisShitLive, 15000);
                        } else {
                            setTimeout(thisShitLive, 5000);
                        };
                    };
                    
                });
            };
            function thisShitPopular(){
                $hito = "<?php echo $cur_stream ?>";
                $.get("/"+$hito+"/nclients", function(data, status) {
                    if (status == "success") {
                        $(document).find("#viewers")[0].innerHTML = data;
                    } else {
                        $(document).find("#viewers")[0].innerHTML = "0";
                    };
                    setTimeout(thisShitPopular,30000);
                });
            }
            $(document).ready(function(){
                <?php if( ! isset( $_GET['stream'] ) ):?>
                    $('.open-menu')[0].click();
                <?php endif; ?>
                thisShitLive();
                thisShitPopular();
            });
        </script>
    </body>
</html>

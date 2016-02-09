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
                <a href="#">STREAMS</a>
            </div>
            
<?php foreach( $streamers_list as $streamer ): ?>
<?php if( $streamer['live'] ): ?>
                <div class="pill streamer live">
<?php else: ?>
                <div class="pill streamer off">
<?php endif; ?>
                    <a href="/<?php echo $streamer['stream_url'] ?>"><?php echo $streamer['name'] ?></a>
                    <span></span>
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
                rtmp: {
                    bufferlength: 0.1
                },
            });
        </script>
        <script type="text/javascript">
            var streamers = document.querySelectorAll("#main-nav > div.streamer");
            function thisShitLive() {
                var request = new XMLHttpRequest();
                request.open('GET', '/live');
                request.onload = function() {
                    var livestreamers;
                    var streamerurl;
                    if (request.status >= 200 && request.status < 400) {
                        livestreamers = request.responseText.split(',');
                        for(i=streamers.length-1; i>=0; i--) {
                            streamerurl = streamers[i].children[0].href.split('/').pop();
                            if (livestreamers.includes(streamerurl)) {
                                streamers[i].setAttribute('class','pill streamer live');
                            } else {
                                streamers[i].setAttribute('class','pill streamer off');
                            };
                        };
                        if (livestreamers[0] != '') {
                            setTimeout(thisShitLive, 15000);
                        } else {
                            setTimeout(thisShitLive, 5000);
                        };
                    };
                };
                request.send();
            };
            function thisShitPopular(){
                var request = new XMLHttpRequest();
                request.open('GET', '<?php echo $cur_stream ?>/nclients');
                request.onload = function() {
                    if (request.status >= 200 && request.status < 400) {
                        document.getElementById('viewers').innerHTML = request.responseText;
                    } else {
                        document.getElementById('viewers').innerHTML = 0;
                    };
                    setTimeout(thisShitPopular,30000);
                };
                request.send();
            }
            document.addEventListener("DOMContentLoaded", function(){
<?php if( ! isset( $_GET['stream'] ) ):?>
                document.querySelector('.open-menu').click();
<?php endif; ?>
                thisShitLive();
                thisShitPopular();
            });
        </script>
    </body>
</html>

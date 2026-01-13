<?php

add_shortcode('pool_detail_scoring_rules', function(){
    
    ob_start(); 
    
    global $post;
    $post_id = $post->ID;
    
    $league = get_post_meta($post_id, 'game_type', true); ?>
    
    <style>
        p{
                font-family: "Aeonik Trial", Sans-serif;
    font-size: 20px;
    font-weight: 600;
    color: #FFFFFF;
    display:flex;
    gap:30px;
        }
        .nba span{
                font-family: "Aeonik Trial", Sans-serif;
    font-size: 20px;
    font-weight:300;
        }
        
    </style>
    
    
    
    
    <?php if( $league == 'nfl') {  ?>
        <div class="nba">
        <p>Passing Yards: <span>1 point per 25 yards</span></p>
        <p>Passing Touchdowns: <span>4 points</span></p>
        <p>Passing Interceptions: <span>-2 points</span></p>
        <p>Rushing Yards: <span>1 point per 10 yards  </span></p>
        <p>Rushing Touchdowns: <span>6 points </span></p>
        <p>Receptions:  <span>1 points (only if using PPR scoring)</span></p>
        <p>Receiving Yards:  <span>1 point per 10 yards</span></p>
        <p>Receiving Touchdowns: <span>6 points </span></p>
        <p>2-Point Conversions:  <span>2 points</span></p>
        <p>Fumbles Lost:  <span>-2 points</span></p>
        <p>Fumble Recovered for a Touchdown:  <span>6 points</span></p>
        <p>Kick and Punt Return Touchdowns:  <span>6 points</span></p>
        <p>Kickers </p>
        <p>PAT Made: <span> 1 point</span></p>
        <p>FG Made (0-49 yards):  <span>3 points</span></p>
        <p>FG Made (50+ yards): <span>5 points </span></p>
        </div>
    
   <?php  } else if ($league == 'nba') { ?>
    <div class="nba">
        <p>Points:<span>1</span></p>
        <p>Total Rebounds:<span>1.2</span></p>
        <p>Assists:<span>1.5</span></p>
        <p>Steals:<span>3</span></p>
        <p>Blocks:<span>3</span></p>
        <p>Turnovers:<span>-1.5</span></p>
    </div>
    
    <?php } else if ($league == 'nhl') { ?>
    <div class="nba">
        <p>Goals: <span>3 points </span></p>
        <p>Assists:  <span>2 points</span></p>
        <P>Shots on Goal: <span>0.4 points </span></P>
        <P>Blocked Shots: <span>0.4 points </span></P>
        <P> Power Play Goals:<span> 1 bonus point </span></P>
        <P>Power Play Assists:<span> 1 bonus point </span></P>
        <P>Short-Handed Goals:  <span>2 bonus points</span></P>
        <P>Short-Handed Assists: <span> 1 bonus point</span></P>
        <P>Penalty Minutes:  <span>-0.5 points per minute</span></P>
        </div>
        
   <?php }
    
    return ob_get_clean();
});
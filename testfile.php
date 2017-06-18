<?php
/**
 * Created by PhpStorm.
 * User: Piyush
 * Date: 6/17/2017
 * Time: 4:53 PM
 */

?>
<!DOCTYPE html>
<html>
<head>
    <script
            src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>
    <script src="http://d3js.org/d3.v4.min.js" type="text/javascript"></script>
    <script src="http://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js" type="text/javascript"></script>
    <script src="assets/js/dTree.min.js" type="text/javascript"></script>
</head>
<style>
    .linage {
        fill: none;
        stroke: black;
    }

    .marriage {
        fill: none;
        stroke: black;
    }

    .node {
        background-color: lightblue;
        border-style: solid;
        border-width: 1px;
        transition: width .5s;
    }
    .node:hover
    {
        width: 300px !important;
        -webkit-transition: width .5s; /* Safari */
        transition: width .5s;
    }

    .nodeText {
        font: 10px sans-serif;
    }

    .diffnode {
        background-color: darkblue;
        border-style: solid;
        border-width: 1px;
    }
</style>
<body>

<script type="text/javascript">

    var options = {
        target: '#graph',
        debug: false,
        width: 1500,
        height: 1000,
        callbacks: {
            /*
             Callbacks should only be overwritten on a need to basis.
             See the section about callbacks below.
             */
        },
        margin: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        },
        nodeWidth: 100,
        styles: {
            node: 'node',
            linage: 'linage',
            marriage: 'marriage',
            text: 'nodeText'
        }
    };
</script>
<div id="graph"></div>
<script type="text/javascript">
    $.getJSON('createjson.php',"", function (data) {
        dTree.init(data, options);
    });

</script>
</body>
</html>

var labelType, useGradients, nativeTextSupport, animate,selected_member, tree;

(function() {
    var ua = navigator.userAgent,
    iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
    typeOfCanvas = typeof HTMLCanvasElement,
    nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
    textSupport = nativeCanvasSupport 
    && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
    //I'm setting this based on the fact that ExCanvas provides text support for IE
    //and that as of today iPhone/iPad current text support is lame
    labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
    nativeTextSupport = labelType == 'Native';
    useGradients = nativeCanvasSupport;
    animate = !(iStuff || !nativeCanvasSupport);
})();


//Function to clear previously displayed data by display_data()
function display_clear_data()
{
    $("#display_name").html("");
    $("#display_dob").html("");
    $("#display_relationship").html("");
    $("#display_alive").html("");
    $("#display_image").src="";
}

//Function to display data in the right container of the current selected_member
function display_data(name,dob,relationship_status,alive,image)
{
    //Remove all previous data
    display_clear_data();
    //display the data
    $("#display_name").html(name);
    $("#display_dob").html(dob);
    $("#display_relationship").html(relationship_status);
    $("#display_alive").html(alive);
    $("#display_image").src="assets/user_images/"+image;
}
function init(){
    var json;
    //init data
    $.getJSON("createjson.php","",function (data)
    {
        json=data;
        //init Spacetree
        //Create a new ST instance
        var st = new $jit.ST({
            //id of viz container element
            injectInto: 'infovis',
            //set duration for the animation
            duration: 500,
            //set animation transition type
            transition: $jit.Trans.Quart.easeInOut,
            //set distance between node and its children
            levelDistance: 50,
            //Top to bottom orientation
            orientation:"top",
            //enable panning
            Navigation: {
                enable:true,
                panning:true,
                zooming:10
            },
            //set node and edge styles
            //set overridable=true for styling individual
            //nodes or edges
            Node: {
                autoHeight:true,    
                autoWidth:true,
                //height: 60,
                //width: 60,
                type: 'rectangle',
                align:"center",
                color: '#aaa',
                overridable: true
            },
        
            Edge: {
                type: 'bezier',
                overridable: true
            },        
            //This method is called on DOM label creation.
            //Use this method to add event handlers and styles to
            //your node.
            onCreateLabel: function(label, node){
                label.id = node.id;            
                label.innerHTML = node.name;
                label.onclick = function()
                {
                    //show the operations toolbar
                    selected_member=node.id;
                    st.onClick(node.id);
                
                    //display data on right container
                    display_data(node.name,node.data.dob,node.data.relationship_status,node.data.alive,node.image);    
                };
                //set label styles
                var style = label.style;
                style.width = 60 + 'px';
                style.height = 25 + 'px';            
                style.cursor = 'pointer';
                style.color = '#333';
                style.fontSize = '0.8em';
                style.textAlign= 'center';
                style.paddingTop = '3px';
            },
            onComplete:function ()
            {
            //alert(selected_member);  
            },
        
            levelsToShow:2,     
            //This method is called right before plotting
            //a node. It's useful for changing an individual node
            //style properties before plotting it.
            //The data properties prefixed with a dollar
            //sign will override the global node style properties.
            onBeforePlotNode: function(node){
                //add some color to the nodes in the path between the
                //root node and the selected node.
                if (node.selected) {
                    node.data.$color = "#ff7";
                
                }
                else {
                    delete node.data.$color;
                    //if the node belongs to the last plotted level
                    if(!node.anySubnode("exist")) {
                        //count children number
                        var count = 0;
                        node.eachSubnode(function(n) {
                            count++;
                        });
                        //assign a node color based on
                        //how many children it has
                        node.data.$color = ['#aaa', '#baa', '#caa', '#daa', '#eaa', '#faa'][count];                    
                    }
                }
            },
        
            //This method is called right before plotting
            //an edge. It's useful for changing an individual edge
            //style properties before plotting it.
            //Edge data proprties prefixed with a dollar sign will
            //override the Edge global style properties.
            onBeforePlotLine: function(adj){
                if (adj.nodeFrom.selected && adj.nodeTo.selected) {
                    adj.data.$color = "#eed";
                    adj.data.$lineWidth = 3;
                }
                else {
                    delete adj.data.$color;
                    delete adj.data.$lineWidth;
                }
            }
        });
        //load json data
        st.loadJSON(json);
        //compute node positions and layout
        st.compute();
        //optional: make a translation of the tree
        st.geom.translate(new $jit.Complex(-200, 0), "current");
        //emulate a click on the root node.
        st.onClick(st.root,{
            onComplete:function () { // When the onlick animation on root is over the perform select
                if (window.location.hash)
                {
                    var window_hash=window.location.hash;
                    var split=window_hash.split("#");
                    st.select(split[1]);
                }
            
                //Display data of root in the right Container
                var tree_root=(st.graph.getNode(st.root));
                display_clear_data();
                display_data(tree_root.name,tree_root.data.dob,tree_root.data.relationship_status,tree_root.data.alive);
            }
        });
        //store the selected member id in selected_member
        selected_member=st.root;
    
    
    
        //end
        
        tree=st;
    
    //end
    });
}
